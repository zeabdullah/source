export interface EmailTemplate {
    id: number
    project_id: number
    section_name: string
    campaign_id: string
    thumbnail_url: string
    brevo_template_id?: string
    html_content?: string
    created_at: string
    updated_at: string
}
