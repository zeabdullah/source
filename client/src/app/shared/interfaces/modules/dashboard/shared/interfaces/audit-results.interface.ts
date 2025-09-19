import { AuditIssue } from './audit-issue.interface'

export interface AuditResults {
    auditId: string
    flowName: string
    overallConsistencyScore: number
    issues: AuditIssue[]
    positiveFindings: string[]
}