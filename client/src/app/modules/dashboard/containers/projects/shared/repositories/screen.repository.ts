import { HttpClient } from '@angular/common/http'
import { inject, Injectable } from '@angular/core'
import { Observable } from 'rxjs'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { ScreenData } from '../interfaces/screen.interface'

@Injectable({ providedIn: 'root' })
export class ScreenRepository {
    protected http = inject(HttpClient)

    getProjectScreens(projectId: string): Observable<LaravelApiResponse<ScreenData[]>> {
        return this.http.get<LaravelApiResponse<ScreenData[]>>(`/api/projects/${projectId}/screens`)
    }
}
