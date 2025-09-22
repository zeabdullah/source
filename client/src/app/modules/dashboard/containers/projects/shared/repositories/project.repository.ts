import { HttpClient } from '@angular/common/http'
import { inject, Injectable } from '@angular/core'
import { Observable } from 'rxjs'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { ProjectData } from '../interfaces/project-data.interface'

const e = encodeURIComponent

@Injectable({ providedIn: 'root' })
export class ProjectRepository {
    protected http = inject(HttpClient)

    getProjects(): Observable<LaravelApiResponse<ProjectData[]>> {
        return this.http.get<LaravelApiResponse<ProjectData[]>>('/api/projects')
    }

    getProject(projectId: string): Observable<LaravelApiResponse<ProjectData>> {
        return this.http.get<LaravelApiResponse<ProjectData>>(`/api/projects/${e(projectId)}`)
    }

    createProject(formValue: {
        name: string
        description?: string
    }): Observable<LaravelApiResponse<ProjectData>> {
        return this.http.post<LaravelApiResponse<ProjectData>>('/api/projects', formValue)
    }

    updateProject(
        projectId: string,
        formValue: { name: string; description: string },
    ): Observable<LaravelApiResponse<ProjectData>> {
        return this.http.put<LaravelApiResponse<ProjectData>>(
            `/api/projects/${e(projectId)}`,
            formValue,
        )
    }
}
