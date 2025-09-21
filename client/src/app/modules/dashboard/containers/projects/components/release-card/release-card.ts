import {
    ChangeDetectionStrategy,
    Component,
    input,
    output,
    computed,
    inject,
    DestroyRef,
} from '@angular/core'
import { Chip } from 'primeng/chip'
import { Button } from 'primeng/button'
import { Menu } from 'primeng/menu'
import { MenuItem } from 'primeng/api'
import { ConfirmDialog } from 'primeng/confirmdialog'
import { ConfirmationService } from 'primeng/api'
import { ReleaseData } from '../../shared/interfaces/release.interface'
import { DatePipe, TitleCasePipe } from '@angular/common'
import { ReleaseRepository } from '../../shared/repositories/release.repository'
import { MessageService } from '~/core/services/message.service'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { catchError, of } from 'rxjs'

@Component({
    selector: 'app-release-card',
    imports: [Chip, DatePipe, TitleCasePipe, Button, Menu, ConfirmDialog],
    templateUrl: './release-card.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'block' },
})
export class ReleaseCard {
    private releaseRepository = inject(ReleaseRepository)
    private confirmationService = inject(ConfirmationService)
    private message = inject(MessageService)
    destroyRef = inject(DestroyRef)

    release = input.required<ReleaseData>()
    projectId = input.required<string>()

    releaseDeleted = output<void>()

    private menuItems = computed<MenuItem[]>(() => [
        {
            label: 'View Details',
            icon: 'pi pi-eye',
            command: () => this.viewDetails(),
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

    tags = computed(() => {
        const tagsString = this.release().tags
        return tagsString
            ? tagsString
                  .split(',')
                  .map(tag => tag.trim())
                  .filter(tag => tag)
            : []
    })

    screensCount = computed(() => this.release().screens.length || 0)
    emailsCount = computed(() => this.release().email_templates.length || 0)

    get menuItemsList() {
        return this.menuItems()
    }

    private viewDetails() {
        // TODO: Implement view details navigation
        console.log('View details for release:', this.release().id)
    }

    private confirmDelete() {
        this.confirmationService.confirm({
            message: 'Are you sure you want to delete this release? This action cannot be undone.',
            header: 'Confirm Delete',
            icon: 'pi pi-exclamation-triangle',
            accept: () => this.deleteRelease(),
        })
    }

    private deleteRelease() {
        this.releaseRepository
            .deleteRelease(this.release().id.toString())
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    console.error('Failed to delete release:', err)
                    this.message.error(
                        'Error',
                        `Failed to delete release: ${err.error?.message || err.message}`,
                    )
                    return of({ message: '', payload: null })
                }),
            )
            .subscribe(() => {
                this.releaseDeleted.emit()
                this.message.success('Success', 'Release deleted successfully')
            })
    }
}
