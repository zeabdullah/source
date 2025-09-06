import { ChangeDetectionStrategy, Component, inject, signal } from '@angular/core'
import {
    ReactiveFormsModule,
    NonNullableFormBuilder,
    Validators,
    AbstractControl,
    ValidationErrors,
} from '@angular/forms'
import { Router, RouterLink } from '@angular/router'
import { HttpErrorResponse } from '@angular/common/http'
import { AuthService } from '~/shared/services/auth.service'

@Component({
    selector: 'app-register-form',
    imports: [ReactiveFormsModule, RouterLink],
    templateUrl: './register-form.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class RegisterForm {
    protected fb = inject(NonNullableFormBuilder)
    protected authService = inject(AuthService)
    protected router = inject(Router)

    protected isLoading = signal(false)
    protected errorMessage = signal<string | null>(null)

    protected registerFb = this.fb.group({
        name: ['', Validators.required],
        email: ['', [Validators.required, Validators.email]],
        password: ['', [Validators.required, Validators.minLength(8)]],
        password_confirmation: ['', [Validators.required, this.passwordMatchValidator.bind(this)]],
    })

    private passwordMatchValidator(control: AbstractControl): ValidationErrors | null {
        const password = this.registerFb?.get('password')?.value
        const PasswordConfirmation = control.value

        if (password && PasswordConfirmation && password !== PasswordConfirmation) {
            return { passwordMismatch: true }
        }

        return null
    }

    register(): void {
        if (this.registerFb.invalid) {
            return
        }

        this.isLoading.set(true)
        this.errorMessage.set(null)

        const formData = new FormData()
        formData.append('name', this.registerFb.get('name')?.value || '')
        formData.append('email', this.registerFb.get('email')?.value || '')
        formData.append('password', this.registerFb.get('password')?.value || '')
        formData.append(
            'password_confirmation',
            this.registerFb.get('password_confirmation')?.value || '',
        )

        this.authService.register(formData).subscribe({
            next: ({ success, errorMessage }) => {
                this.isLoading.set(false)
                if (success) {
                    this.router.navigate(['/dashboard'])
                } else {
                    this.errorMessage.set(errorMessage || 'Registration failed. Please try again.')
                }
            },
            error: (err: HttpErrorResponse) => {
                this.isLoading.set(false)
                const errMsg =
                    (err.error?.message as string) ||
                    (typeof err.error === 'string' ? err.error : null) ||
                    'Registration failed. Please try again.'
                this.errorMessage.set(errMsg)
            },
        })
    }
}
