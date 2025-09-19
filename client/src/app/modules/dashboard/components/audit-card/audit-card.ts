import { ChangeDetectionStrategy, Component, computed, inject, input, output } from '@angular/core'
import { Router } from '@angular/router'
import { Button } from 'primeng/button'
import { Tag } from 'primeng/tag'
import { Menu } from 'primeng/menu'
import { MenuItem } from 'primeng/api'
import { ConfirmDialog } from 'primeng/confirmdialog'
import { ProgressSpinner } from 'primeng/progressspinner'
import { DatePipe } from '@angular/common'
import { ConfirmationService, MessageService } from 'primeng/api'
import { catchError, of } from 'rxjs'
import { AuditService } from '~/core/services/audit.service'
import { Audit } from '~/shared/interfaces/modules/dashboard/shared/interfaces/audit.interface'

@Component({
    selector: 'app-audit-card',
    imports: [Button, Tag, Menu, ConfirmDialog, ProgressSpinner, DatePipe],
    providers: [ConfirmationService, MessageService],
    templateUrl: './audit-card.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'block' },
})
export class AuditCard {
    private auditService = inject(AuditService)
    private confirmationService = inject(ConfirmationService)
    private messageService = inject(MessageService)
    private router = inject(Router)

    audit = input.required<Audit>()
    projectId = input.required<number>()

    auditDeleted = output<void>()
    auditExecuted = output<void>()

    private menuItems = computed<MenuItem[]>(() => [
        {
            label: 'View Details',
            icon: 'pi pi-eye',
            command: () => this.viewDetails(),
        },
        {
            label: 'Execute',
            icon: 'pi pi-play',
            command: () => this.executeAudit(),
            disabled: this.audit().status === 'processing' || this.audit().status === 'completed',
        },
        {
            separator: true,
        },
        {
            label: 'Delete',
            icon: 'pi pi-trash',
            command: () => this.confirmDelete(),
            styleClass: 'text-red-500',
        },
    ])

    statusSeverity = computed(() => {
        switch (this.audit().status) {
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
        switch (this.audit().status) {
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

    canExecute = computed(() => {
        return this.audit().status === 'pending'
    })

    get menuItemsList() {
        return this.menuItems()
    }

    private viewDetails() {
        this.router.navigate(['/dashboard/projects', this.projectId(), 'audits', this.audit().id])
    }

    executeAudit() {
        this.auditService
            .executeAudit(this.projectId(), this.audit().id)
            .pipe(
                catchError(error => {
                    console.error('Failed to execute audit:', error)
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to execute audit. Please try again.',
                        life: 5000,
                    })
                    return of({ message: '', payload: null })
                }),
            )
            .subscribe(response => {
                if (response.payload) {
                    this.auditExecuted.emit()
                }
            })
    }

    private confirmDelete() {
        this.confirmationService.confirm({
            message: 'Are you sure you want to delete this audit? This action cannot be undone.',
            header: 'Confirm Delete',
            icon: 'pi pi-exclamation-triangle',
            accept: () => this.deleteAudit(),
        })
    }

    private deleteAudit() {
        this.auditService
            .deleteAudit(this.projectId(), this.audit().id)
            .pipe(
                catchError(error => {
                    console.error('Failed to delete audit:', error)
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to delete audit. Please try again.',
                        life: 5000,
                    })
                    return of({ message: '', payload: null })
                }),
            )
            .subscribe(() => {
                this.auditDeleted.emit()
            })
    }
}
