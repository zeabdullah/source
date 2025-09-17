import { ChangeDetectionStrategy, Component, input } from '@angular/core'

interface AiChatMessageType {
    type: 'user' | 'bot'
    content: string
    timestamp: string
}

@Component({
    selector: 'app-ai-chat-message',
    imports: [],
    templateUrl: './ai-chat-message.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'block' },
})
export class AiChatMessage {
    message = input.required<AiChatMessageType>()
}
