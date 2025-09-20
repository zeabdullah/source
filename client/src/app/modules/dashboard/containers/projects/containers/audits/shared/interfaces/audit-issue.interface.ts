export interface AuditIssue {
    type: 'terminology' | 'color_usage' | 'spacing' | 'typography' | 'interaction' | 'layout' | 'accessibility' | 'navigation' | 'analysis_error'
    severity: 'low' | 'medium' | 'high'
    description: string
    screens: string[]
    suggestion: string
}