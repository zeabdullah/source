import { AuditResults } from './audit-results.interface'

export interface AuditData {
    id: number
    project_id: number
    name: string
    description: string | null
    status: 'pending' | 'processing' | 'completed' | 'failed'
    results: AuditResults | null
    overall_score: number | null
    created_at: string
    updated_at: string
    screens: {
        id: number
        section_name: string | null
        figma_svg_url: string | null
        description: string | null
        pivot: {
            sequence_order: number
        }
    }[]
}

export interface CreateAuditRequest {
    name: string
    description?: string
    screen_ids: number[]
}

export interface UpdateAuditRequest {
    name?: string
    description?: string
}
