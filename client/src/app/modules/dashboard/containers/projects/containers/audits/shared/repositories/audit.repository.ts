import { HttpClient } from '@angular/common/http'
import { inject, Injectable } from '@angular/core'
import { Observable } from 'rxjs'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { AuditData, CreateAuditRequest, UpdateAuditRequest } from '../interfaces/audit.interface'

type GetAuditsResponse = LaravelApiResponse<AuditData[]>
type GetAuditResponse = LaravelApiResponse<AuditData>
type CreateAuditResponse = LaravelApiResponse<AuditData>
type UpdateAuditResponse = LaravelApiResponse<AuditData>
type DeleteAuditResponse = LaravelApiResponse<null>
type ExecuteAuditResponse = LaravelApiResponse<AuditData>
type AuditStatusResponse = LaravelApiResponse<{
    id: number
    status: string
    overall_score: number | null
    created_at: string
    updated_at: string
}>

@Injectable({ providedIn: 'root' })
export class AuditRepository {
    protected http = inject(HttpClient)

    getAudits(projectId: number): Observable<GetAuditsResponse> {
        return this.http.get<GetAuditsResponse>(`/api/projects/${projectId}/audits`)
    }

    getAudit(projectId: number, auditId: number): Observable<GetAuditResponse> {
        return this.http.get<GetAuditResponse>(`/api/projects/${projectId}/audits/${auditId}`)
    }

    createAudit(projectId: number, data: CreateAuditRequest): Observable<CreateAuditResponse> {
        return this.http.post<CreateAuditResponse>(`/api/projects/${projectId}/audits`, data)
    }

    updateAudit(
        projectId: number,
        auditId: number,
        data: UpdateAuditRequest,
    ): Observable<UpdateAuditResponse> {
        return this.http.put<UpdateAuditResponse>(
            `/api/projects/${projectId}/audits/${auditId}`,
            data,
        )
    }

    deleteAudit(projectId: number, auditId: number): Observable<DeleteAuditResponse> {
        return this.http.delete<DeleteAuditResponse>(`/api/projects/${projectId}/audits/${auditId}`)
    }

    executeAudit(projectId: number, auditId: number): Observable<ExecuteAuditResponse> {
        return this.http.post<ExecuteAuditResponse>(
            `/api/projects/${projectId}/audits/${auditId}/execute`,
            {},
        )
    }

    getAuditStatus(projectId: number, auditId: number): Observable<AuditStatusResponse> {
        return this.http.get<AuditStatusResponse>(
            `/api/projects/${projectId}/audits/${auditId}/status`,
        )
    }
}
