import { ScreenData } from './screen.interface'
import { EmailTemplate } from './email.interface'

export interface ReleaseData {
    id: number
    project_id: number
    version: string
    description: string | null
    tags: string | null
    screens: ScreenData[]
    email_templates: EmailTemplate[]
    created_at: string
    updated_at: string
}
