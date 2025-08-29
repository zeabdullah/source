import { ChangeDetectionStrategy, Component, signal, inject, OnInit } from '@angular/core'
import { RouterLink } from '@angular/router'
import { Validators, ReactiveFormsModule, NonNullableFormBuilder } from '@angular/forms'
import { HttpClient, HttpErrorResponse } from '@angular/common/http'
import { BASE_URL } from '../../constants/http.constants'

@Component({
    selector: 'app-login-page',
    imports: [RouterLink, ReactiveFormsModule],
    templateUrl: './login-page.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class LoginPage implements OnInit {
    protected fb = inject(NonNullableFormBuilder)
    protected http = inject(HttpClient)

    protected isLoading = signal(false)
    protected errorMessage = signal<string | null>(null)
    protected successMessage = signal<string | null>(null)

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
        this.successMessage.set(null)

        const formData = new FormData()
        formData.append('email', this.loginForm.get('email')?.value || '')
        formData.append('password', this.loginForm.get('password')?.value || '')

        interface LoginResponse {
            message: string
            payload: {
                token: string
                user: {
                    id: number
                    name: string
                    email: string
                    avatar_url: string
                }
            } | null
        }

        this.http.post<LoginResponse>('/api/login', formData, { observe: 'response' }).subscribe({
            next: res => {
                this.isLoading.set(false)
                if (res.status === 200) {
                    this.successMessage.set('Login successful!')
                    this.loginForm.reset()
                } else {
                    this.errorMessage.set(res.body?.message || 'Login failed. Please try again.')
                }
            },
            error: (error: HttpErrorResponse) => {
                this.isLoading.set(false)
                const errMsg =
                    (error.error?.message as string) ||
                    (typeof error.error === 'string' ? error.error : null) ||
                    'Login failed. Please try again.'
                this.errorMessage.set(errMsg)
            },
        })
    }
}
