import { ChangeDetectionStrategy, Component, inject, signal } from '@angular/core'
import { NonNullableFormBuilder, ReactiveFormsModule, Validators } from '@angular/forms'
import { ButtonModule } from 'primeng/button'
import { CardModule } from 'primeng/card'
import { InputTextModule } from 'primeng/inputtext'
import { MessageModule } from 'primeng/message'
import { UserService } from '~/core/services/user.service'

@Component({
    selector: 'app-account',
    imports: [ReactiveFormsModule, CardModule, InputTextModule, ButtonModule, MessageModule],
    templateUrl: './account.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: {
        class: 'grow',
    },
})
export class Account {
    private fb = inject(NonNullableFormBuilder)
    private userService = inject(UserService)

    public isSubmitting = signal(false)
    public successMessage = signal<string | null>(null)
    public errorMessage = signal<string | null>(null)

    public form = this.fb.group({
        figma_access_token: ['', Validators.required],
    })

    submitToken() {
        if (!(this.form.valid && this.form.value.figma_access_token)) {
            return
        }

        this.isSubmitting.set(true)
        this.successMessage.set(null)
        this.errorMessage.set(null)

        const token = this.form.value.figma_access_token
        this.userService.storeFigmaToken(token).subscribe({
            next: () => {
                this.successMessage.set('Figma access token saved successfully!')
                this.isSubmitting.set(false)
                this.form.reset()
            },
            error: error => {
                this.errorMessage.set(error.error?.message || 'Failed to save token')
                this.isSubmitting.set(false)
            },
        })
    }
}
