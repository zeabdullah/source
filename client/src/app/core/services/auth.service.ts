import { HttpClient, HttpErrorResponse } from '@angular/common/http'
import { inject, Injectable, signal } from '@angular/core'
import { Observable, of } from 'rxjs'
import { catchError, map } from 'rxjs/operators'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { SessionUser } from '~/shared/interfaces/session-user.interface'

type MeResponse = LaravelApiResponse<SessionUser>
type LoginResponse = LaravelApiResponse<{
    token: string
    user: SessionUser
}>
type RegisterResponse = LoginResponse

@Injectable({ providedIn: 'root' })
export class AuthService {
    protected http = inject(HttpClient)

    public user = signal<SessionUser | null>(null)
    public isAuthenticated = signal<boolean>(false)

    checkIfAuthenticated(): Observable<boolean> {
        return this.http.get<MeResponse>('/api/me').pipe(
            map(res => {
                this.user.set(res.payload)
                this.isAuthenticated.set(true)
                return true
            }),
            catchError(() => of(false)),
        )
    }

    register(formData: FormData): Observable<{ success: boolean; errorMessage: string | null }> {
        return this.http
            .post<RegisterResponse>('/api/register', formData, { observe: 'response' })
            .pipe(
                map(res => {
                    if (res.ok && res.body?.payload?.user) {
                        this.user.set(res.body.payload.user)
                        this.isAuthenticated.set(true)
                        return { success: true, errorMessage: null }
                    }
                    return { success: false, errorMessage: res.body?.message || 'Login failed.' }
                }),
                catchError((errResp: HttpErrorResponse) => {
                    const errorMessage =
                        (errResp.error?.message as string) ||
                        (typeof errResp.error === 'string' ? errResp.error : null) ||
                        'Login failed. Please try again.'

                    return of({ success: false, errorMessage })
                }),
            )
    }

    login(formData: FormData): Observable<{ success: boolean; errorMessage: string | null }> {
        return this.http.post<LoginResponse>('/api/login', formData, { observe: 'response' }).pipe(
            map(res => {
                if (res.ok && res.body?.payload?.user) {
                    this.user.set(res.body.payload.user)
                    this.isAuthenticated.set(true)
                    return { success: true, errorMessage: null }
                }
                return { success: false, errorMessage: res.body?.message || 'Login failed.' }
            }),
            catchError((errResp: HttpErrorResponse) => {
                const errorMessage =
                    (errResp.error?.message as string) ||
                    (typeof errResp.error === 'string' ? errResp.error : null) ||
                    'Login failed. Please try again.'

                return of({ success: false, errorMessage })
            }),
        )
    }

    getUser() {
        return this.user.asReadonly()
    }

    logout(): Observable<boolean> {
        return this.http.post('/api/logout', null, { observe: 'response' }).pipe(
            map(res => {
                if (res.ok) {
                    this.user.set(null)
                    this.isAuthenticated.set(false)
                    return true
                }
                return false
            }),
            catchError(() => of(false)),
        )
    }
}
