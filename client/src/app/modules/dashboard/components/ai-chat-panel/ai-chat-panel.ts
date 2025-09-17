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
    emailTemplateId = input.required<number>()

    messages = signal<AiChatMessageData[]>([])
    newMessage = signal('')
    isLoading = signal(false)

    ngOnInit() {
        this.loadMessages()
    }

    loadMessages() {
        this.isLoading.set(true)
        this.http
            .get<LaravelApiResponse<AiChatMessageData[]>>(
                `/api/email-templates/${encodeURIComponent(this.emailTemplateId())}/chats`,
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
        if (this.newMessage().trim()) {
            const messageContent = this.newMessage().trim()

            // Add user message immediately
            const localUserMsg = {
                id: Date.now(), // temporary ID
                user_id: 1,
                content: messageContent,
                created_at: new Date().toISOString(),
                updated_at: new Date().toISOString(),
            }
            this.messages.update(messages => [...messages, localUserMsg])

            const formData = new FormData()
            formData.set('content', messageContent)

            this.http
                .post<LaravelApiResponse<AiChatMessageData>>(
                    `/api/email-templates/${this.emailTemplateId()}/chats`,
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
                        return of<LaravelApiResponse<AiChatMessageData>>({
                            message: '',
                            payload: null,
                        })
                    }),
                )
                .subscribe(response => {
                    if (response.payload) {
                        // Replace temporary user message with actual response
                        this.messages.update(messages =>
                            messages.map(msg =>
                                msg.id === localUserMsg.id ? response.payload! : msg,
                            ),
                        )
                        this.newMessage.set('')
                    }
                })
        }
    }
}
