import { HttpClient } from '@angular/common/http'
import { inject, Injectable } from '@angular/core'
import { Observable } from 'rxjs'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { ReleaseData } from '../interfaces/release.interface'

export interface CreateReleaseRequest {
    version: string
    description?: string
    tags?: string
    screens?: number[]
    emails?: number[]
}

@Injectable({ providedIn: 'root' })
export class ReleaseRepository {
    protected http = inject(HttpClient)

    getProjectReleases(projectId: string): Observable<LaravelApiResponse<ReleaseData[]>> {
        return this.http.get<LaravelApiResponse<ReleaseData[]>>(
            `/api/projects/${projectId}/releases`,
        )
    }

    createRelease(
        projectId: string,
        releaseData: CreateReleaseRequest,
    ): Observable<LaravelApiResponse<ReleaseData>> {
        return this.http.post<LaravelApiResponse<ReleaseData>>(
            `/api/projects/${projectId}/releases`,
            releaseData,
        )
    }

    getReleaseById(releaseId: string): Observable<LaravelApiResponse<ReleaseData>> {
        return this.http.get<LaravelApiResponse<ReleaseData>>(`/api/releases/${releaseId}`)
    }

    updateRelease(
        releaseId: string,
        releaseData: Partial<CreateReleaseRequest>,
    ): Observable<LaravelApiResponse<ReleaseData>> {
        return this.http.put<LaravelApiResponse<ReleaseData>>(
            `/api/releases/${releaseId}`,
            releaseData,
        )
    }

    deleteRelease(releaseId: string): Observable<LaravelApiResponse<null>> {
        return this.http.delete<LaravelApiResponse<null>>(`/api/releases/${releaseId}`)
    }
}
