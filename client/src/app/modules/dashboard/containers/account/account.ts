import { ChangeDetectionStrategy, Component, inject, signal } from '@angular/core'
import { NonNullableFormBuilder, ReactiveFormsModule, Validators } from '@angular/forms'
import { Button } from 'primeng/button'
import { Card } from 'primeng/card'
import { ConfirmPopup } from 'primeng/confirmpopup'
import { ConfirmationService, MessageService } from 'primeng/api'
import { InputText } from 'primeng/inputtext'
import { Message } from 'primeng/message'
import { Toast } from 'primeng/toast'
import { UserService } from '~/core/services/user.service'

@Component({
    selector: 'app-account',
    imports: [ReactiveFormsModule, Card, InputText, Button, Message, Toast, ConfirmPopup],
    providers: [ConfirmationService, MessageService],
    templateUrl: './account.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: {
        class: 'grow',
    },
})
export class Account {
    private fb = inject(NonNullableFormBuilder)
    private userService = inject(UserService)
    private confirmationService = inject(ConfirmationService)
    private messageService = inject(MessageService)

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
        this.userService.storeFigmaToken(token).subscribe({
            next: () => {
                this.messageService.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Figma access token saved successfully!',
                    life: 4000,
                })
                this.isSubmitting.set(false)
                this.figmaForm.reset()
            },
            error: error => {
                this.messageService.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: error.error?.message || 'Failed to save token',
                    life: 4000,
                })
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
        this.userService.storeBrevoToken(token).subscribe({
            next: () => {
                this.messageService.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: 'Brevo API token saved successfully!',
                    life: 4000,
                })
                this.isSubmitting.set(false)
                this.brevoForm.reset()
            },
            error: error => {
                this.messageService.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: error.error?.message || 'Failed to save token',
                    life: 4000,
                })
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

        this.userService.removeFigmaToken().subscribe({
            next: () => {
                this.messageService.add({
                    severity: 'info',
                    summary: 'Token Deleted',
                    detail: 'Figma access token deleted successfully!',
                    life: 4000,
                })
                this.isSubmitting.set(false)
            },
            error: error => {
                this.messageService.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: error.error?.message || 'Failed to delete token',
                    life: 4000,
                })
                this.isSubmitting.set(false)
            },
        })
    }

    private deleteBrevoToken() {
        this.isSubmitting.set(true)

        this.userService.removeBrevoToken().subscribe({
            next: () => {
                this.messageService.add({
                    severity: 'info',
                    summary: 'Token Deleted',
                    detail: 'Brevo API token deleted successfully!',
                    life: 4000,
                })
                this.isSubmitting.set(false)
            },
            error: error => {
                this.messageService.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: error.error?.message || 'Failed to delete token',
                    life: 4000,
                })
                this.isSubmitting.set(false)
            },
        })
    }
}
