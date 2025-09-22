import { HttpClient } from '@angular/common/http'
import { inject, Injectable } from '@angular/core'
import { Observable } from 'rxjs'
import { AiChatMessageData } from '../interfaces/ai-chat-message-data.interface'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'

export interface SendMessageResponse {
    user: AiChatMessageData
    ai: AiChatMessageData
    template_updated?: boolean
}

const e = encodeURIComponent

interface SendMessageBody {
    content: string
    update_template: boolean
}

@Injectable({ providedIn: 'root' })
export class AiChatRepository {
    protected http = inject(HttpClient)

    getChatMessages(
        chatType: 'email-templates' | 'screens',
        chatId: number,
    ): Observable<LaravelApiResponse<AiChatMessageData[]>> {
        return this.http.get<LaravelApiResponse<AiChatMessageData[]>>(
            `/api/${e(chatType)}/${e(chatId)}/chats`,
        )
    }

    sendMessage(
        chatType: 'email-templates' | 'screens',
        chatId: number,
        { content, update_template = false }: SendMessageBody,
    ): Observable<LaravelApiResponse<SendMessageResponse>> {
        return this.http.post<LaravelApiResponse<SendMessageResponse>>(
            `/api/${e(chatType)}/${e(chatId)}/chats`,
            {
                content,
                update_template,
            },
        )
    }
}
