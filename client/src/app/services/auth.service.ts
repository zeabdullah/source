import { HttpClient, HttpErrorResponse } from '@angular/common/http'
import { inject, Injectable, signal } from '@angular/core'
import { Observable, of } from 'rxjs'
import { catchError, map } from 'rxjs/operators'
import { SessionUser } from '../interfaces/session-user.interface'
import { LaravelApiResponse } from '../interfaces/laravel-api-response.interface'
import { Router } from '@angular/router'

type MeResponse = LaravelApiResponse<SessionUser>
type LoginResponse = LaravelApiResponse<{
    token: string
    user: SessionUser
}>

@Injectable({ providedIn: 'root' })
export class AuthService {
    protected http = inject(HttpClient)
    protected router = inject(Router)
    protected user = signal<SessionUser | null>(null)

    isAuthenticated(): Observable<boolean> {
        return this.http.get<MeResponse>('/api/me').pipe(
            map(res => {
                this.user.set(res.payload)
                return Boolean(res.payload)
            }),
            catchError(() => of(false)),
        )
    }

    login(formData: FormData): Observable<{ success: boolean; errorMessage: string | null }> {
        return this.http.post<LoginResponse>('/api/login', formData, { observe: 'response' }).pipe(
            map(res => {
                if (res.status === 200 && res.body?.payload?.user) {
                    this.user.set(res.body.payload.user)
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
                if (res.status === 200) {
                    this.user.set(null)
                    this.router.navigate(['/'])
                    return true
                }
                return false
            }),
            catchError(() => of(false)),
        )
    }
}
