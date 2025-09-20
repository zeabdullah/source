import {
    ChangeDetectionStrategy,
    Component,
    computed,
    DestroyRef,
    inject,
    OnInit,
    signal,
} from '@angular/core'
import { ActivatedRoute } from '@angular/router'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { catchError, finalize, of } from 'rxjs'
import { Button } from 'primeng/button'
import { ProgressSpinner } from 'primeng/progressspinner'
import { Tag } from 'primeng/tag'
import { Card } from 'primeng/card'
import { Toast } from 'primeng/toast'
import { MessageService } from '~/core/services/message.service'
import { AuditRepository } from '~/modules/dashboard/containers/projects/containers/audits/shared/repositories/audit.repository'
import { AuditIssue } from '../../shared/interfaces/audit-issue.interface'
import { AuditData } from '../../shared/interfaces/audit.interface'

@Component({
    selector: 'app-audit-details',
    imports: [Button, ProgressSpinner, Tag, Card, Toast],
    templateUrl: './audit-details.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'flex flex-1 flex-col' },
})
export class AuditDetails implements OnInit {
    private auditRepository = inject(AuditRepository)
    private route = inject(ActivatedRoute)
    private message = inject(MessageService)
    destroyRef = inject(DestroyRef)

    private audit = signal<AuditData | null>(null)
    private isLoading = signal(false)

    projectId = computed(() =>
        Number(this.route.snapshot.parent?.parent?.paramMap.get('projectId')),
    )
    auditId = computed(() => Number(this.route.snapshot.paramMap.get('auditId')))
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
        this.auditRepository
            .getAudit(this.projectId(), this.auditId())
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(error => {
                    console.error('Failed to load audit:', error)
                    this.message.error('Error', 'Failed to load audit details. Please try again.')
                    return of({ message: '', payload: null })
                }),
                finalize(() => this.isLoading.set(false)),
            )
            .subscribe(response => {
                this.audit.set(response.payload)
            })
    }
}
