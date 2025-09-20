import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { EmailTemplate } from './email.interface'

export interface BrevoTemplate {
    id: string
    name: string
    subject: string
    htmlContent: string
    createdAt: string
    modifiedAt: string
    isActive: boolean
}

export type GetBrevoTemplatesResponse = LaravelApiResponse<{
    count: number
    templates: BrevoTemplate[]
}>
export type ImportBrevoTemplateResponse = LaravelApiResponse<EmailTemplate>
export type ImportMultipleBrevoTemplatesResponse = LaravelApiResponse<EmailTemplate[]>
export type SyncBrevoTemplateResponse = LaravelApiResponse<EmailTemplate>
