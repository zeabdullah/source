import { ChangeDetectionStrategy, Component, input } from '@angular/core'
import { DatePipe } from '@angular/common'
import { Avatar } from 'primeng/avatar'

interface CommentType {
    user: {
        name: string
        avatar: string
    }
    date: string
    content: string
}

@Component({
    selector: 'app-comment',
    imports: [DatePipe, Avatar],
    templateUrl: './comment.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'block' },
})
export class Comment {
    comment = input.required<CommentType>()
}
