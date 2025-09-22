import { HttpClient } from '@angular/common/http'
import { inject, Injectable } from '@angular/core'
import { Observable } from 'rxjs'
import {
    GetBrevoTemplatesResponse,
    ImportBrevoTemplateResponse,
    ImportMultipleBrevoTemplatesResponse,
    SyncBrevoTemplateResponse,
} from '~/modules/dashboard/containers/projects/shared/interfaces/brevo-template.interface'

@Injectable({ providedIn: 'root' })
export class BrevoRepository {
    protected http = inject(HttpClient)

    /**
     * Get all Brevo templates for the authenticated user
     */
    getTemplates(): Observable<GetBrevoTemplatesResponse> {
        return this.http.get<GetBrevoTemplatesResponse>('/api/brevo-templates')
    }

    /**
     * Import a single Brevo template to a project
     */
    importTemplate(
        projectId: string,
        brevoTemplateId: string,
    ): Observable<ImportBrevoTemplateResponse> {
        return this.http.post<ImportBrevoTemplateResponse>(
            `/api/projects/${projectId}/email-templates/import-brevo`,
            { brevo_template_id: brevoTemplateId },
        )
    }

    /**
     * Import multiple Brevo templates to a project
     */
    importMultipleTemplates(
        projectId: string,
        brevoTemplateIds: string[],
    ): Observable<ImportMultipleBrevoTemplatesResponse> {
        // Since the backend doesn't have a batch import endpoint, we'll make multiple individual calls
        // This could be optimized with a backend batch endpoint in the future
        const importPromises = brevoTemplateIds.map(templateId =>
            this.importTemplate(projectId, templateId).toPromise(),
        )

        return new Observable(observer => {
            Promise.all(importPromises)
                .then(results => {
                    const successfulImports = results
                        .filter(result => result && result.payload)
                        .map(result => result!.payload!)
                    observer.next({
                        message: `Successfully imported ${successfulImports.length} templates`,
                        payload: successfulImports,
                    })
                    observer.complete()
                })
                .catch(error => {
                    observer.error(error)
                })
        })
    }

    /**
     * Sync a local email template with its Brevo counterpart
     */
    syncTemplate(
        projectId: string,
        emailTemplateId: string,
    ): Observable<SyncBrevoTemplateResponse> {
        return this.http.post<SyncBrevoTemplateResponse>(
            `/api/projects/${projectId}/email-templates/${emailTemplateId}/sync-brevo`,
            null,
        )
    }

    /**
     * Update a template in Brevo
     */
    updateTemplateInBrevo(
        projectId: string,
        emailTemplateId: string,
        templateData: { html_content?: string; template_name?: string },
    ): Observable<SyncBrevoTemplateResponse> {
        return this.http.put<SyncBrevoTemplateResponse>(
            `/api/projects/${projectId}/email-templates/${emailTemplateId}/update-brevo`,
            templateData,
        )
    }
}
