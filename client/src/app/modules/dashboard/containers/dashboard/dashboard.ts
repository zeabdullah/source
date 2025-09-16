import { ChangeDetectionStrategy, Component, inject } from '@angular/core'
import { RouterLink, RouterOutlet } from '@angular/router'
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
    protected authService = inject(AuthService)
}
