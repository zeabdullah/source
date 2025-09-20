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
        const formData = new FormData()
        formData.append('figma_access_token', token)
        return this.http.post<StoreFigmaTokenResponse>('/api/profile/figma-token', formData)
    }

    storeBrevoToken(token: string): Observable<StoreBrevoTokenResponse> {
        const formData = new FormData()
        formData.append('brevo_api_token', token)
        return this.http.post<StoreBrevoTokenResponse>('/api/profile/brevo-token', formData)
    }

    removeFigmaToken(): Observable<DeleteTokenResponse> {
        return this.http.delete<DeleteTokenResponse>('/api/profile/figma-token')
    }

    removeBrevoToken(): Observable<DeleteTokenResponse> {
        return this.http.delete<DeleteTokenResponse>('/api/profile/brevo-token')
    }
}
