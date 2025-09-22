import {
    ChangeDetectionStrategy,
    Component,
    inject,
    signal,
    OnInit,
    DestroyRef,
} from '@angular/core'
import { ActivatedRoute, NavigationEnd, Router, RouterLink, RouterOutlet } from '@angular/router'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { InputIcon } from 'primeng/inputicon'
import { IconField } from 'primeng/iconfield'
import { InputText } from 'primeng/inputtext'
import { Avatar } from 'primeng/avatar'
import { Toolbar } from 'primeng/toolbar'
import { Popover } from 'primeng/popover'
import { AuthService } from '~/core/services/auth.service'

@Component({
    selector: 'app-dashboard',
    imports: [InputIcon, IconField, InputText, Toolbar, Avatar, Popover, RouterOutlet, RouterLink],
    templateUrl: './dashboard.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Dashboard implements OnInit {
    destroyRef = inject(DestroyRef)
    protected router = inject(Router)
    protected activatedRoute = inject(ActivatedRoute)
    protected authService = inject(AuthService)
    protected isInsideProject = signal(false)

    protected user = this.authService.getUser()

    ngOnInit() {
        this.router.events.pipe(takeUntilDestroyed(this.destroyRef)).subscribe(event => {
            if (event instanceof NavigationEnd) {
                this.isInsideProject.set(
                    Boolean(
                        this.activatedRoute.firstChild?.firstChild?.snapshot.params['projectId'],
                    ),
                )
            }
        })

        this.authService
            .checkIfAuthenticated()
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe()
    }

    logoutAndGoHome() {
        this.authService
            .logout()
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe({
                next: async () => {
                    await this.router.navigate(['/'])
                },
                error: err => {
                    alert('something wrong happened')
                    console.log(err)
                },
            })
    }
}
