import { HttpClient, HttpResponse } from '@angular/common/http'
import { inject, Injectable } from '@angular/core'
import { Observable } from 'rxjs'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { SessionUser } from '~/shared/interfaces/session-user.interface'

type MeResponse = LaravelApiResponse<SessionUser>
type LoginResponse = LaravelApiResponse<{
    token: string
    user: SessionUser
}>
type RegisterResponse = LoginResponse

@Injectable({ providedIn: 'root' })
export class AuthRepository {
    protected http = inject(HttpClient)

    me(): Observable<MeResponse> {
        return this.http.get<MeResponse>('/api/me')
    }

    register(formValue: {
        name: string
        email: string
        password: string
        password_confirmation: string
        avatar_url?: string
    }): Observable<HttpResponse<RegisterResponse>> {
        return this.http.post<RegisterResponse>('/api/register', formValue, { observe: 'response' })
    }

    login(formValue: { email: string; password: string }): Observable<HttpResponse<LoginResponse>> {
        return this.http.post<LoginResponse>('/api/login', formValue, { observe: 'response' })
    }

    logout(): Observable<HttpResponse<unknown>> {
        return this.http.post('/api/logout', null, { observe: 'response' })
    }
}
