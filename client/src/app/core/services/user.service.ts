import { HttpClient } from '@angular/common/http'
import { inject, Injectable } from '@angular/core'
import { Observable } from 'rxjs'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'

type StoreFigmaTokenResponse = LaravelApiResponse<null>

@Injectable({ providedIn: 'root' })
export class UserService {
    protected http = inject(HttpClient)

    storeFigmaToken(token: string): Observable<StoreFigmaTokenResponse> {
        return this.http.post<StoreFigmaTokenResponse>('/api/profile/figma-token', {
            figma_access_token: token,
        })
    }
}
