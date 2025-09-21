import {
    ChangeDetectionStrategy,
    Component,
    inject,
    signal,
    OnInit,
    DestroyRef,
} from '@angular/core'
import { ActivatedRoute } from '@angular/router'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { catchError, of } from 'rxjs'
import { ProgressSpinner } from 'primeng/progressspinner'
import { Button } from 'primeng/button'
import { Toast } from 'primeng/toast'
import { ReleaseCard } from '../../components/release-card/release-card'
import { NewReleaseDialog } from '../../components/new-release-dialog/new-release-dialog'
import { ReleaseData } from '../../shared/interfaces/release.interface'
import { ReleaseRepository } from '../../shared/repositories/release.repository'
import { MessageService } from '~/core/services/message.service'
import { EmptyState } from '~/shared/components/empty-state/empty-state'

@Component({
    selector: 'app-releases',
    imports: [Button, ReleaseCard, NewReleaseDialog, Toast, EmptyState, ProgressSpinner],
    templateUrl: './releases.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Releases implements OnInit {
    route = inject(ActivatedRoute)
    message = inject(MessageService)
    releaseRepository = inject(ReleaseRepository)
    destroyRef = inject(DestroyRef)

    showNewReleaseDialog = signal(false)
    releases = signal<ReleaseData[]>([])
    isLoading = signal(true)
    projectId = signal<string>('')

    ngOnInit() {
        this.route.parent?.paramMap.pipe(takeUntilDestroyed(this.destroyRef)).subscribe(params => {
            const projectId = params.get('projectId')
            if (projectId) {
                this.projectId.set(projectId)
                this.loadReleases()
            }
        })
    }

    loadReleases() {
        const projectId = this.projectId()
        if (!projectId) return

        this.isLoading.set(true)
        this.releaseRepository
            .getProjectReleases(projectId)
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    this.message.error(
                        'Error',
                        `Failed to load releases. ${err.error?.message || err.message}`,
                    )
                    return of({ message: '', payload: [] })
                }),
            )
            .subscribe(response => {
                this.releases.set(response.payload || [])
                this.isLoading.set(false)
            })
    }

    addNewRelease(newRelease: ReleaseData) {
        this.releases.update(releases => [newRelease, ...releases])
        this.message.success('Success', 'Release created successfully')
    }

    openNewReleaseDialog() {
        this.showNewReleaseDialog.set(true)
    }

    onDialogVisibleChange(visible: boolean) {
        this.showNewReleaseDialog.set(visible)
    }
}
