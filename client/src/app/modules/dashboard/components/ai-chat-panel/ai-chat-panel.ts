import { ChangeDetectionStrategy, Component, input, signal, inject, OnInit } from '@angular/core'
import { FormsModule } from '@angular/forms'
import { HttpClient } from '@angular/common/http'
import { catchError, of } from 'rxjs'
import { Button } from 'primeng/button'
import { InputText } from 'primeng/inputtext'
import { MessageService } from 'primeng/api'
import { ProgressSpinner } from 'primeng/progressspinner'
import { AiChatMessage } from '../ai-chat-message/ai-chat-message'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { Toast } from 'primeng/toast'
import { AiChatMessageData } from '../../shared/interfaces/ai-chat-message-data.interface'

@Component({
    selector: 'app-ai-chat-panel',
    imports: [FormsModule, InputText, Button, ProgressSpinner, AiChatMessage, Toast],
    providers: [MessageService],
    templateUrl: './ai-chat-panel.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class AiChatPanel implements OnInit {
    http = inject(HttpClient)
    messageService = inject(MessageService)

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

    get chatType(): string {
        return this.emailTemplateId() ? 'email-templates' : 'screens'
    }

    loadMessages() {
        if (!this.chatId) return

        this.isLoading.set(true)
        this.http
            .get<LaravelApiResponse<AiChatMessageData[]>>(
                `/api/${this.chatType}/${encodeURIComponent(this.chatId)}/chats`,
            )
            .pipe(
                catchError(err => {
                    console.warn('Failed to load chat messages:', err)
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail:
                            'Failed to load chat. ' + err.error ? err.error.message : err.message,
                        life: 10_000,
                    })
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

        // Add user message immediately
        const userMessage: AiChatMessageData = {
            id: Date.now(),
            user_id: 1,
            content: messageContent,
            sender: 'user',
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString(),
        }

        this.messages.update(messages => [...messages, userMessage])
        this.newMessage.set('')
        this.isWaitingForAiResponse.set(true)

        const formData = new FormData()
        formData.set('content', messageContent)

        this.http
            .post<LaravelApiResponse<{ user: AiChatMessageData; ai: { content: string } }>>(
                `/api/${this.chatType}/${encodeURIComponent(this.chatId)}/chats`,
                formData,
            )
            .pipe(
                catchError(err => {
                    console.warn('Failed to send message:', err)
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail:
                            'Failed to send message. ' + err.error
                                ? err.error.message
                                : err.message,
                        life: 10_000,
                    })
                    return of<
                        LaravelApiResponse<{ user: AiChatMessageData; ai: { content: string } }>
                    >({
                        message: '',
                        payload: null,
                    })
                }),
            )
            .subscribe(response => {
                if (response.payload) {
                    // Add AI message
                    const aiMessage: AiChatMessageData = {
                        id: Date.now() + 1,
                        user_id: null,
                        content: response.payload!.ai.content,
                        sender: 'ai',
                        created_at: new Date().toISOString(),
                        updated_at: new Date().toISOString(),
                    }
                    this.messages.update(messages => [...messages, aiMessage])
                }
                this.isWaitingForAiResponse.set(false)
            })
    }
}
