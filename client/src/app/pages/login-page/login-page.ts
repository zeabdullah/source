import { ChangeDetectionStrategy, Component, signal, inject, OnInit } from '@angular/core'
import { Router, RouterLink } from '@angular/router'
import { Validators, ReactiveFormsModule, NonNullableFormBuilder } from '@angular/forms'
import { HttpErrorResponse } from '@angular/common/http'
import { BASE_URL } from '../../constants/http.constants'
import { AuthService } from '../../services/auth.service'

@Component({
    selector: 'app-login-page',
    imports: [RouterLink, ReactiveFormsModule],
    templateUrl: './login-page.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class LoginPage implements OnInit {
    protected fb = inject(NonNullableFormBuilder)
    protected authService = inject(AuthService)
    protected router = inject(Router)

    protected isLoading = signal(false)
    protected errorMessage = signal<string | null>(null)

    protected loginForm = this.fb.group({
        email: ['', [Validators.required, Validators.email]],
        password: ['', Validators.required],
    })

    ngOnInit(): void {
        fetch(`${BASE_URL}/sanctum/csrf-cookie`, { credentials: 'include' })
    }

    login(): void {
        if (this.loginForm.invalid) {
            return
        }

        this.isLoading.set(true)
        this.errorMessage.set(null)

        const formData = new FormData()
        formData.append('email', this.loginForm.get('email')?.value || '')
        formData.append('password', this.loginForm.get('password')?.value || '')

        this.authService.login(formData).subscribe({
            next: ({ success, errorMessage }) => {
                this.isLoading.set(false)
                if (success) {
                    this.router.navigate(['/dashboard'])
                } else {
                    this.errorMessage.set(errorMessage || 'Login failed. Please try again.')
                }
            },
            error: (err: HttpErrorResponse) => {
                this.isLoading.set(false)
                const errMsg =
                    (err.error?.message as string) ||
                    (typeof err.error === 'string' ? err.error : null) ||
                    'Login failed. Please try again.'
                this.errorMessage.set(errMsg)
            },
        })
    }
}
