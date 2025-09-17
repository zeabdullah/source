import { ChangeDetectionStrategy, Component, input } from '@angular/core'
import { MarkdownComponent } from 'ngx-markdown'
import { AiChatMessageData } from '../../shared/interfaces/ai-chat-message-data.interface'

@Component({
    selector: 'app-ai-chat-message',
    imports: [MarkdownComponent],
    templateUrl: './ai-chat-message.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'block' },
})
export class AiChatMessage {
    message = input.required<AiChatMessageData>()
}
