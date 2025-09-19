import {
    ChangeDetectionStrategy,
    Component,
    computed,
    inject,
    OnDestroy,
    OnInit,
    signal,
} from '@angular/core'
import { ActivatedRoute } from '@angular/router'
import { Button } from 'primeng/button'
import { ProgressSpinner } from 'primeng/progressspinner'
import { Toast } from 'primeng/toast'
import { MessageService } from 'primeng/api'
import { catchError, finalize, interval, of, Subscription, switchMap, takeWhile } from 'rxjs'
import { AuditService } from '~/core/services/audit.service'
import { Audit } from '~/shared/interfaces/modules/dashboard/shared/interfaces/audit.interface'
import { AuditCard } from '../../components/audit-card/audit-card'
import { CreateAuditDialog } from '../../components/create-audit-dialog/create-audit-dialog'
import { EmptyState } from '~/shared/components/empty-state/empty-state'

@Component({
    selector: 'app-audits',
    imports: [Button, ProgressSpinner, Toast, AuditCard, CreateAuditDialog, EmptyState],
    providers: [MessageService],
    templateUrl: './audits.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'flex flex-1 flex-col' },
})
export class Audits implements OnInit, OnDestroy {
    private auditService = inject(AuditService)
    private route = inject(ActivatedRoute)
    private messageService = inject(MessageService)

    private audits = signal<Audit[]>([])
    private isLoading = signal(false)
    showCreateDialog = signal(false)
    private pollingSubscription?: Subscription

    projectId = computed(() =>
        Number(this.route.snapshot.parent?.parent?.paramMap.get('projectId')),
    )
    auditsList = this.audits.asReadonly()
    loading = this.isLoading.asReadonly()

    hasProcessingAudits = computed(() => this.audits().some(audit => audit.status === 'processing'))

    ngOnInit() {
        this.loadAudits()
        this.startPolling()
    }

    ngOnDestroy() {
        this.stopPolling()
    }

    private loadAudits() {
        this.isLoading.set(true)
        this.auditService
            .getAudits(this.projectId())
            .pipe(
                catchError(error => {
                    console.error('Failed to load audits:', error)
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to load audits. Please try again.',
                        life: 5000,
                    })
                    return of({ message: '', payload: [] })
                }),
                finalize(() => this.isLoading.set(false)),
            )
            .subscribe(response => {
                this.audits.set(response.payload || [])
            })
    }

    onCreateAudit() {
        this.showCreateDialog.set(true)
    }

    onAuditCreated() {
        this.showCreateDialog.set(false)
        this.loadAudits()
        this.messageService.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Audit created successfully',
            life: 3000,
        })
    }

    onAuditDeleted() {
        this.loadAudits()
        this.messageService.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Audit deleted successfully',
            life: 3000,
        })
    }

    onAuditExecuted() {
        this.loadAudits()
        this.startPolling() // Restart polling when audit is executed
        this.messageService.add({
            severity: 'info',
            summary: 'Processing',
            detail: 'Audit execution started',
            life: 3000,
        })
    }

    private startPolling() {
        this.stopPolling() // Stop any existing polling first
        this.pollingSubscription = interval(5000) // Poll in milliseconds
            .pipe(
                takeWhile(() => this.hasProcessingAudits()),
                switchMap(() => this.auditService.getAudits(this.projectId())),
                catchError(error => {
                    console.error('Polling error:', error)
                    return of({ message: '', payload: [] })
                }),
            )
            .subscribe(response => {
                const currentAudits = this.audits()
                const updatedAudits = response.payload || []

                // Check if any audit status changed from processing to completed/failed
                const statusChanged = currentAudits.some(currentAudit => {
                    const updatedAudit = updatedAudits.find(ua => ua.id === currentAudit.id)
                    return (
                        currentAudit.status === 'processing' &&
                        updatedAudit &&
                        (updatedAudit.status === 'completed' || updatedAudit.status === 'failed')
                    )
                })

                this.audits.set(updatedAudits)

                if (statusChanged) {
                    this.messageService.add({
                        severity: 'success',
                        summary: 'Update',
                        detail: 'Audit status updated',
                        life: 3000,
                    })
                }
            })
    }

    private stopPolling() {
        if (this.pollingSubscription) {
            this.pollingSubscription.unsubscribe()
            this.pollingSubscription = undefined
        }
    }
}
