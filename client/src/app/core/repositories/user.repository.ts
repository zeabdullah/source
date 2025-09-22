import { HttpClient } from '@angular/common/http'
import { inject, Injectable } from '@angular/core'
import { Observable } from 'rxjs'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'

type StoreFigmaTokenResponse = LaravelApiResponse<null>
type StoreBrevoTokenResponse = LaravelApiResponse<null>
type DeleteTokenResponse = LaravelApiResponse<null>

@Injectable({ providedIn: 'root' })
export class UserRepository {
    protected http = inject(HttpClient)

    storeFigmaToken(token: string): Observable<StoreFigmaTokenResponse> {
        return this.http.post<StoreFigmaTokenResponse>('/api/profile/figma-token', {
            figma_access_token: token,
        })
    }

    storeBrevoToken(token: string): Observable<StoreBrevoTokenResponse> {
        return this.http.post<StoreBrevoTokenResponse>('/api/profile/brevo-token', {
            brevo_api_token: token,
        })
    }

    removeFigmaToken(): Observable<DeleteTokenResponse> {
        return this.http.delete<DeleteTokenResponse>('/api/profile/figma-token')
    }

    removeBrevoToken(): Observable<DeleteTokenResponse> {
        return this.http.delete<DeleteTokenResponse>('/api/profile/brevo-token')
    }
}
