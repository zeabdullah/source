import { ChangeDetectionStrategy, Component, signal } from '@angular/core'
import { Button } from 'primeng/button'
import { ReleaseCard } from '../../components/release-card/release-card'
import { NewReleaseDialog } from '../../components/new-release-dialog/new-release-dialog'
import { Release } from '../../shared/interfaces/release.interface'

@Component({
    selector: 'app-releases',
    imports: [Button, ReleaseCard, NewReleaseDialog],
    templateUrl: './releases.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Releases {
    showNewReleaseDialog = signal(false)
    releases: Release[] = [
        {
            version: '2.2.0',
            description: 'Complete dashboard redesign with modern analytics and dark mode support.',
            tags: ['Bugfix', 'Authentication'],
            publisher: 'John Doe',
            screensCount: 5,
            emailsCount: 2,
            createdAt: '2025-01-18T02:01:00Z',
        },
        {
            version: '2.1.0',
            description: 'Added new email templates and improved user experience.',
            tags: ['Feature', 'UI/UX'],
            publisher: 'Jane Smith',
            screensCount: 3,
            emailsCount: 4,
            createdAt: '2025-01-15T10:30:00Z',
        },
        {
            version: '2.0.0',
            description:
                'Major release with new project management features and enhanced collaboration tools.',
            tags: ['Major', 'Collaboration'],
            publisher: 'Mike Johnson',
            screensCount: 8,
            emailsCount: 6,
            createdAt: '2025-01-10T15:45:00Z',
        },
    ]

    openNewReleaseDialog() {
        this.showNewReleaseDialog.set(true)
    }

    onDialogVisibleChange(visible: boolean) {
        this.showNewReleaseDialog.set(visible)
    }
}
