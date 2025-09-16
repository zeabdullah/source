import { ChangeDetectionStrategy, Component, inject, signal } from '@angular/core'
import { ActivatedRoute, NavigationEnd, Router, RouterLink, RouterOutlet } from '@angular/router'
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
export class Dashboard {
    protected router = inject(Router)
    protected activatedRoute = inject(ActivatedRoute)
    protected authService = inject(AuthService)
    protected isInsideProject = signal(false)

    constructor() {
        this.router.events.subscribe(event => {
            if (event instanceof NavigationEnd) {
                this.isInsideProject.set(
                    Boolean(
                        this.activatedRoute.firstChild?.firstChild?.snapshot.params['projectId'],
                    ),
                )
            }
        })
    }
}
