import {
    ChangeDetectionStrategy,
    Component,
    computed,
    DestroyRef,
    inject,
    OnDestroy,
    OnInit,
    signal,
} from '@angular/core'
import { ActivatedRoute } from '@angular/router'
import { Button } from 'primeng/button'
import { ProgressSpinner } from 'primeng/progressspinner'
import { Toast } from 'primeng/toast'
import { catchError, finalize, interval, of, Subscription, switchMap, takeWhile } from 'rxjs'
import { AuditRepository } from '~/modules/dashboard/containers/projects/containers/audits/shared/repositories/audit.repository'
import { EmptyState } from '~/shared/components/empty-state/empty-state'
import { NgClass } from '@angular/common'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { MessageService } from '~/core/services/message.service'
import { AuditCard } from '../../components/audit-card/audit-card'
import { CreateAuditDialog } from '../../components/create-audit-dialog/create-audit-dialog'
import { AuditData } from '../../shared/interfaces/audit.interface'
import { ConfirmDialog } from 'primeng/confirmdialog'

@Component({
    selector: 'app-audits',
    imports: [
        Button,
        ProgressSpinner,
        Toast,
        AuditCard,
        CreateAuditDialog,
        EmptyState,
        NgClass,
        ConfirmDialog,
    ],
    templateUrl: './audits.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'flex flex-1 flex-col' },
})
export class Audits implements OnInit, OnDestroy {
    private auditRepository = inject(AuditRepository)
    private route = inject(ActivatedRoute)
    private message = inject(MessageService)
    destroyRef = inject(DestroyRef)

    private audits = signal<AuditData[]>([])
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
        this.auditRepository
            .getAudits(this.projectId())
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(error => {
                    console.error('Failed to load audits:', error)
                    this.message.error('Error', 'Failed to load audits. Please try again.')
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
        this.message.success('Success', 'Audit created successfully')
    }

    onAuditDeleted() {
        this.loadAudits()
        this.message.success('Success', 'Audit deleted successfully')
    }

    onAuditExecuted() {
        this.loadAudits()
        this.startPolling() // Restart polling when audit is executed
        this.message.info('Processing', 'Audit execution started')
    }

    private startPolling() {
        this.stopPolling()

        this.pollingSubscription = interval(5000) // Poll in milliseconds
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                takeWhile(() => this.hasProcessingAudits()),
                switchMap(() => this.auditRepository.getAudits(this.projectId())),
                catchError(error => {
                    console.error('Polling error:', error)
                    this.message.error('Error', 'Failed to load audits. Please try again.')
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
                    this.message.success('Update', 'Audit status updated')
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
