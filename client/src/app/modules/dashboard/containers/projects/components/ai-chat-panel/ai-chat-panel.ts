import {
    ChangeDetectionStrategy,
    Component,
    input,
    signal,
    inject,
    OnInit,
    DestroyRef,
} from '@angular/core'
import { FormsModule } from '@angular/forms'
import { HttpClient, HttpErrorResponse } from '@angular/common/http'
import { catchError, of } from 'rxjs'
import { Button } from 'primeng/button'
import { InputText } from 'primeng/inputtext'
import { ProgressSpinner } from 'primeng/progressspinner'
import { AiChatMessage } from '../ai-chat-message/ai-chat-message'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { Toast } from 'primeng/toast'
import { AiChatMessageData } from '../../shared/interfaces/ai-chat-message-data.interface'
import { EmptyState } from '~/shared/components/empty-state/empty-state'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { MessageService } from '~/core/services/message.service'
import { AiChatRepository } from '../../shared/repositories/ai-chat.respository'

@Component({
    selector: 'app-ai-chat-panel',
    imports: [FormsModule, InputText, Button, ProgressSpinner, AiChatMessage, Toast, EmptyState],
    templateUrl: './ai-chat-panel.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'h-full' },
})
export class AiChatPanel implements OnInit {
    protected http = inject(HttpClient)
    protected message = inject(MessageService)
    protected aiChatRepository = inject(AiChatRepository)
    destroyRef = inject(DestroyRef)

    emailTemplateId = input<number | undefined>()
    screenId = input<number | undefined>()

    messages = signal<AiChatMessageData[]>([])
    newMessage = signal('')
    isLoading = signal(false)
    isWaitingForAiResponse = signal(false)

    ngOnInit() {
        this.loadMessages()
    }

    get chatId(): number {
        return this.emailTemplateId() ?? this.screenId() ?? 0
    }

    get chatType() {
        return this.emailTemplateId() ? 'email-templates' : 'screens'
    }

    loadMessages() {
        if (!this.chatId) return

        this.isLoading.set(true)
        this.aiChatRepository
            .getChatMessages(this.chatType, this.chatId)
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError((err: HttpErrorResponse) => {
                    this.message.error(
                        'Error',
                        `Failed to load chat. ${err.error?.message || err.message}`,
                    )
                    return of<LaravelApiResponse<AiChatMessageData[]>>({ message: '', payload: [] })
                }),
            )
            .subscribe(response => {
                this.messages.set(response.payload || [])
                this.isLoading.set(false)
            })
    }

    sendMessage() {
        const messageContent = this.newMessage().trim()
        if (!messageContent || !this.chatId) {
            return
        }

        // Add user message immediately (optimistic ui)
        const userMessage: AiChatMessageData = {
            id: Date.now(),
            user_id: 1, // placeholder
            content: messageContent,
            sender: 'user',
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString(),
        }

        this.messages.update(messages => [...messages, userMessage])
        this.newMessage.set('')
        this.isWaitingForAiResponse.set(true)

        this.aiChatRepository
            .sendMessage(this.chatType, this.chatId, {
                content: messageContent,
                update_template: this.chatType === 'email-templates',
            })
            .pipe(
                catchError((err: HttpErrorResponse) => {
                    this.message.error(
                        'Error',
                        `Failed to send message. ${err.error?.message || err.message}`,
                    )
                    return of<LaravelApiResponse<null>>({
                        message: '',
                        payload: null,
                    })
                }),
            )
            .subscribe(response => {
                if (response.payload) {
                    // Add AI message
                    const aiMessage: AiChatMessageData = {
                        id: response.payload.ai.id,
                        user_id: null,
                        content: response.payload.ai.content,
                        sender: 'ai',
                        created_at: response.payload.ai.created_at,
                        updated_at: response.payload.ai.updated_at,
                    }
                    this.messages.update(messages => [...messages, aiMessage])

                    // Show success message if template was updated
                    if (response.payload.template_updated) {
                        this.message.success(
                            'Template Updated',
                            'Email template has been updated with AI-generated content!',
                        )
                    }
                }
                this.isWaitingForAiResponse.set(false)
            })
    }
}
