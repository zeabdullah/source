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
import { catchError } from 'rxjs/operators'
import { of } from 'rxjs'
import { Message } from 'primeng/message'
import { Button } from 'primeng/button'
import { InputText } from 'primeng/inputtext'
import { AuthService } from '~/core/services/auth.service'
import { AuthRepository } from '~/core/repositories/auth.repository'

@Component({
    selector: 'app-register-form',
    imports: [ReactiveFormsModule, RouterLink, Message, InputText, Button],
    templateUrl: './register-form.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class RegisterForm {
    protected fb = inject(NonNullableFormBuilder)
    protected authService = inject(AuthService)
    protected authRepository = inject(AuthRepository)
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

    isInvalid(controlName: string): boolean | undefined {
        const control = this.registerFb.get(controlName)
        return control?.invalid && control?.touched
    }

    register(): void {
        if (this.registerFb.invalid) {
            return
        }

        this.isLoading.set(true)
        this.errorMessage.set(null)

        const formValue = this.registerFb.getRawValue()

        this.authRepository
            .register(formValue)
            .pipe(
                catchError((err: HttpErrorResponse) => {
                    const errMsg =
                        (err.error?.message as string) ||
                        (typeof err.error === 'string' ? err.error : null) ||
                        'Registration failed. Please try again.'

                    this.errorMessage.set(errMsg)
                    this.isLoading.set(false)
                    return of(null)
                }),
            )
            .subscribe(async resp => {
                this.isLoading.set(false)

                if (!resp) {
                    return
                }

                if (!(resp.ok && resp.body?.payload?.user)) {
                    const message = resp.body?.message || 'Registration failed. Please try again.'
                    this.errorMessage.set(message)
                    return
                }

                this.authService.user.set(resp.body.payload.user)
                this.authService.isAuthenticated.set(true)
                await this.router.navigate(['/dashboard'])
            })
    }
}
