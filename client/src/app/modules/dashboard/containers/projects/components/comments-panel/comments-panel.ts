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
import { HttpClient } from '@angular/common/http'
import { catchError, of } from 'rxjs'
import { Button } from 'primeng/button'
import { InputText } from 'primeng/inputtext'
import { MessageService } from '~/core/services/message.service'
import { ProgressSpinner } from 'primeng/progressspinner'
import { Comment } from '../comment/comment'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { CommentData } from '../../shared/interfaces/comment.interface'
import { EmptyState } from '~/shared/components/empty-state/empty-state'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'

@Component({
    selector: 'app-comments-panel',
    imports: [FormsModule, InputText, Button, ProgressSpinner, Comment, EmptyState],
    templateUrl: './comments-panel.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'h-full' },
})
export class CommentsPanel implements OnInit {
    http = inject(HttpClient)
    message = inject(MessageService)
    destroyRef = inject(DestroyRef)

    screenId = input<number | undefined>()
    emailTemplateId = input<number | undefined>()

    comments = signal<CommentData[]>([])
    newComment = signal('')
    isLoading = signal(false)
    isSubmitting = signal(false)

    ngOnInit() {
        this.loadComments()
    }

    get commentId(): number {
        return this.screenId() ?? this.emailTemplateId() ?? 0
    }

    get commentType(): string {
        return this.emailTemplateId() ? 'email-templates' : 'screens'
    }

    loadComments() {
        if (!this.commentId) return

        this.isLoading.set(true)
        this.http
            .get<LaravelApiResponse<CommentData[]>>(
                `/api/${this.commentType}/${this.commentId}/comments`,
            )
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    console.warn('Failed to load comments:', err)
                    this.message.error(
                        'Error',
                        `Failed to load comments. ${err.error?.message || err.message}`,
                    )
                    return of<LaravelApiResponse<CommentData[]>>({ message: '', payload: [] })
                }),
            )
            .subscribe(response => {
                this.comments.set(response.payload || [])
                this.isLoading.set(false)
            })
    }

    sendComment() {
        const commentContent = this.newComment().trim()
        if (!commentContent || !this.commentId) {
            return
        }

        this.isSubmitting.set(true)
        const formData = new FormData()
        formData.set('content', commentContent)

        this.http
            .post<LaravelApiResponse<CommentData>>(
                `/api/${this.commentType}/${this.commentId}/comments`,
                formData,
            )
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    console.warn('Failed to send comment:', err)
                    this.message.error(
                        'Error',
                        `Failed to send comment. ${err.error?.message || err.message}`,
                    )
                    this.isSubmitting.set(false)
                    return of<LaravelApiResponse<CommentData>>({ message: '', payload: null })
                }),
            )
            .subscribe(response => {
                this.isSubmitting.set(false)
                if (response.payload) {
                    this.comments.update(comments => [...comments, response.payload!])
                    this.newComment.set('')
                }
            })
    }
}
