import { ChangeDetectionStrategy, Component, input } from '@angular/core'
import { AiChatMessageData } from '../../shared/interfaces/ai-chat-message-data.interface'

@Component({
    selector: 'app-ai-chat-message',
    imports: [],
    templateUrl: './ai-chat-message.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'block' },
})
export class AiChatMessage {
    message = input.required<AiChatMessageData>()
}
