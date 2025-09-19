import { ChangeDetectionStrategy, Component, computed, inject, OnInit, signal } from '@angular/core'
import { ActivatedRoute } from '@angular/router'
import { Button } from 'primeng/button'
import { ProgressSpinner } from 'primeng/progressspinner'
import { Toast } from 'primeng/toast'
import { MessageService } from 'primeng/api'
import { catchError, finalize, of } from 'rxjs'
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
export class Audits implements OnInit {
    private auditService = inject(AuditService)
    private route = inject(ActivatedRoute)
    private messageService = inject(MessageService)

    private audits = signal<Audit[]>([])
    private isLoading = signal(false)
    showCreateDialog = signal(false)

    projectId = computed(() =>
        Number(this.route.snapshot.parent?.parent?.paramMap.get('projectId')),
    )
    auditsList = this.audits.asReadonly()
    loading = this.isLoading.asReadonly()

    ngOnInit() {
        this.loadAudits()
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
        this.messageService.add({
            severity: 'info',
            summary: 'Processing',
            detail: 'Audit execution started',
            life: 3000,
        })
    }
}
