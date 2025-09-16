import { ChangeDetectionStrategy, Component, inject, signal } from '@angular/core'
import { Button } from 'primeng/button'
import { ReleaseCard } from '../../components/release-card/release-card'
import { NewReleaseDialog } from '../../components/new-release-dialog/new-release-dialog'
import { Release } from '../../shared/interfaces/release.interface'
import { MessageService } from 'primeng/api'
import { Toast } from 'primeng/toast'
import { EmptyState } from '~/shared/components/empty-state/empty-state'

@Component({
    selector: 'app-releases',
    imports: [Button, ReleaseCard, NewReleaseDialog, Toast, EmptyState],
    providers: [MessageService],
    templateUrl: './releases.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Releases {
    messageService = inject(MessageService)
    showNewReleaseDialog = signal(false)

    releases: Release[] = [
        // {
        //     version: '2.2.0',
        //     description: 'Complete dashboard redesign with modern analytics and dark mode support.',
        //     tags: ['Bugfix', 'Authentication'],
        //     screensCount: 5,
        //     emailsCount: 2,
        //     created_at: '2025-01-18T02:01:00Z',
        // },
        // {
        //     version: '2.1.0',
        //     description: 'Added new email templates and improved user experience.',
        //     tags: ['Feature', 'UI/UX'],
        //     screensCount: 3,
        //     emailsCount: 4,
        //     created_at: '2025-01-15T10:30:00Z',
        // },
        // {
        //     version: '2.0.0',
        //     description:
        //         'Major release with new project management features and enhanced collaboration tools.',
        //     tags: ['Major', 'Collaboration'],
        //     screensCount: 8,
        //     emailsCount: 6,
        //     created_at: '2025-01-10T15:45:00Z',
        // },
    ]

    addNewRelease(release: Release) {
        this.releases.unshift(release)
        this.messageService.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Release created successfully',
            life: 4000,
        })
    }

    openNewReleaseDialog() {
        this.showNewReleaseDialog.set(true)
    }

    onDialogVisibleChange(visible: boolean) {
        this.showNewReleaseDialog.set(visible)
    }
}
