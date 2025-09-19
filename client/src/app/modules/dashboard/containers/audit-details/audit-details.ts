import { ChangeDetectionStrategy, Component, computed, inject, OnInit, signal } from '@angular/core'
import { ActivatedRoute } from '@angular/router'
import { Button } from 'primeng/button'
import { ProgressSpinner } from 'primeng/progressspinner'
import { Tag } from 'primeng/tag'
import { Card } from 'primeng/card'
import { Toast } from 'primeng/toast'
import { MessageService } from 'primeng/api'
import { catchError, finalize, of } from 'rxjs'
import { AuditService } from '~/core/services/audit.service'
import { Audit } from '~/shared/interfaces/modules/dashboard/shared/interfaces/audit.interface'
import { AuditIssue } from '~/shared/interfaces/modules/dashboard/shared/interfaces/audit-issue.interface'

@Component({
    selector: 'app-audit-details',
    imports: [Button, ProgressSpinner, Tag, Card, Toast],
    providers: [MessageService],
    templateUrl: './audit-details.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'flex flex-1 flex-col' },
})
export class AuditDetails implements OnInit {
    private auditService = inject(AuditService)
    private route = inject(ActivatedRoute)
    private messageService = inject(MessageService)

    private audit = signal<Audit | null>(null)
    private isLoading = signal(false)

    projectId = computed(() =>
        Number(this.route.snapshot.parent?.parent?.paramMap.get('projectId')),
    )
    auditId = computed(() => Number(this.route.snapshot.parent?.paramMap.get('auditId')))
    auditData = this.audit.asReadonly()
    loading = this.isLoading.asReadonly()

    statusSeverity = computed(() => {
        const audit = this.audit()
        if (!audit) return 'info'

        switch (audit.status) {
            case 'completed':
                return 'success'
            case 'processing':
                return 'warning'
            case 'failed':
                return 'danger'
            default:
                return 'info'
        }
    })

    statusLabel = computed(() => {
        const audit = this.audit()
        if (!audit) return 'Unknown'

        switch (audit.status) {
            case 'completed':
                return 'Completed'
            case 'processing':
                return 'Processing'
            case 'failed':
                return 'Failed'
            default:
                return 'Pending'
        }
    })

    issuesBySeverity = computed(() => {
        const audit = this.audit()
        if (!audit?.results?.issues) return { high: [], medium: [], low: [] }

        return audit.results.issues.reduce(
            (acc, issue) => {
                acc[issue.severity].push(issue)
                return acc
            },
            { high: [] as AuditIssue[], medium: [] as AuditIssue[], low: [] as AuditIssue[] },
        )
    })

    ngOnInit() {
        this.loadAudit()
    }

    loadAudit() {
        this.isLoading.set(true)
        this.auditService
            .getAudit(this.projectId(), this.auditId())
            .pipe(
                catchError(error => {
                    console.error('Failed to load audit:', error)
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to load audit details. Please try again.',
                        life: 5000,
                    })
                    return of({ message: '', payload: null })
                }),
                finalize(() => this.isLoading.set(false)),
            )
            .subscribe(response => {
                this.audit.set(response.payload)
            })
    }
}
