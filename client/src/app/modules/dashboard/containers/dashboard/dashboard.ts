import { ChangeDetectionStrategy, Component } from '@angular/core'
import { ButtonModule } from 'primeng/button'
import { InputIcon } from 'primeng/inputicon'
import { IconField } from 'primeng/iconfield'
import { Logo } from '~/shared/components/logo/logo'
import { InputTextModule } from 'primeng/inputtext'
import { Avatar } from 'primeng/avatar'
import { RouterLink, RouterLinkActive, RouterOutlet } from '@angular/router'

@Component({
    selector: 'app-dashboard',
    imports: [
        ButtonModule,
        Logo,
        InputIcon,
        IconField,
        InputTextModule,
        Avatar,
        RouterOutlet,
        RouterLink,
        RouterLinkActive,
    ],
    templateUrl: './dashboard.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Dashboard {}
