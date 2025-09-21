import { HttpClient } from '@angular/common/http'
import { inject, Injectable } from '@angular/core'
import { Observable } from 'rxjs'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { EmailTemplate } from '../interfaces/email.interface'

@Injectable({ providedIn: 'root' })
export class EmailTemplateRepository {
    protected http = inject(HttpClient)

    getProjectEmailTemplates(projectId: string): Observable<LaravelApiResponse<EmailTemplate[]>> {
        return this.http.get<LaravelApiResponse<EmailTemplate[]>>(
            `/api/projects/${projectId}/email-templates`,
        )
    }
}
