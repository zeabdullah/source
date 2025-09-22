import { ChangeDetectionStrategy, Component, DestroyRef, inject, signal } from '@angular/core'
import { NonNullableFormBuilder, ReactiveFormsModule, Validators } from '@angular/forms'
import { Button } from 'primeng/button'
import { Card } from 'primeng/card'
import { ConfirmPopup } from 'primeng/confirmpopup'
import { ConfirmationService } from 'primeng/api'
import { InputText } from 'primeng/inputtext'
import { Message } from 'primeng/message'
import { Toast } from 'primeng/toast'
import { UserRepository } from '~/core/repositories/user.repository'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { MessageService } from '~/core/services/message.service'

@Component({
    selector: 'app-account',
    imports: [ReactiveFormsModule, Card, InputText, Button, Message, Toast, ConfirmPopup],
    templateUrl: './account.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'grow' },
})
export class Account {
    private fb = inject(NonNullableFormBuilder)
    private userRepository = inject(UserRepository)
    private confirmationService = inject(ConfirmationService)
    private message = inject(MessageService)
    destroyRef = inject(DestroyRef)

    public isSubmitting = signal(false)

    public figmaForm = this.fb.group({
        figma_access_token: ['', Validators.required],
    })

    public brevoForm = this.fb.group({
        brevo_api_token: ['', Validators.required],
    })

    submitToken() {
        if (!(this.figmaForm.valid && this.figmaForm.value.figma_access_token)) {
            return
        }

        this.isSubmitting.set(true)

        const token = this.figmaForm.value.figma_access_token
        this.userRepository
            .storeFigmaToken(token)
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe({
                next: () => {
                    this.message.success('Success', 'Figma access token saved successfully!')
                    this.isSubmitting.set(false)
                    this.figmaForm.reset()
                },
                error: err => {
                    this.message.error(
                        'Error',
                        `Failed to save token: ${err.error?.message || err.message}`,
                    )
                    this.isSubmitting.set(false)
                },
            })
    }

    submitBrevoToken() {
        if (!(this.brevoForm.valid && this.brevoForm.value.brevo_api_token)) {
            return
        }

        this.isSubmitting.set(true)

        const token = this.brevoForm.value.brevo_api_token
        this.userRepository
            .storeBrevoToken(token)
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe({
                next: () => {
                    this.message.success('Success', 'Brevo API token saved successfully!')
                    this.isSubmitting.set(false)
                    this.brevoForm.reset()
                },
                error: err => {
                    this.message.error(
                        'Error',
                        `Failed to save token: ${err.error?.message || err.message}`,
                    )
                    this.isSubmitting.set(false)
                },
            })
    }

    confirmDeleteFigmaToken(event: Event) {
        this.confirmationService.confirm({
            target: event.currentTarget as EventTarget,
            message: 'Are you sure you want to delete your Figma access token?',
            icon: 'pi pi-exclamation-triangle',
            rejectButtonProps: {
                severity: 'secondary',
                outlined: true,
            },
            acceptButtonProps: {
                severity: 'danger',
            },
            accept: () => {
                this.deleteFigmaToken()
            },
        })
    }

    confirmDeleteBrevoToken(event: Event) {
        this.confirmationService.confirm({
            target: event.currentTarget as EventTarget,
            message: 'Are you sure you want to delete your Brevo API token?',
            icon: 'pi pi-exclamation-triangle',
            rejectButtonProps: {
                severity: 'secondary',
                outlined: true,
            },
            acceptButtonProps: {
                severity: 'danger',
            },
            accept: () => {
                this.deleteBrevoToken()
            },
        })
    }

    private deleteFigmaToken() {
        this.isSubmitting.set(true)

        this.userRepository
            .removeFigmaToken()
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe({
                next: () => {
                    this.message.info('Token Deleted', 'Figma access token deleted successfully!')
                    this.isSubmitting.set(false)
                },
                error: err => {
                    this.message.error(
                        'Error',
                        `Failed to delete token: ${err.error?.message || err.message}`,
                    )
                    this.isSubmitting.set(false)
                },
            })
    }

    private deleteBrevoToken() {
        this.isSubmitting.set(true)

        this.userRepository
            .removeBrevoToken()
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe({
                next: () => {
                    this.message.info('Token Deleted', 'Brevo API token deleted successfully!')
                    this.isSubmitting.set(false)
                },
                error: err => {
                    this.message.error(
                        'Error',
                        `Failed to delete token: ${err.error?.message || err.message}`,
                    )
                    this.isSubmitting.set(false)
                },
            })
    }
}
