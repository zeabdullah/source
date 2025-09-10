import { ChangeDetectionStrategy, Component } from '@angular/core'
import { RouterLink } from '@angular/router'
import { ButtonModule } from 'primeng/button'
import { ButtonGroup } from 'primeng/buttongroup'
import { InputIcon } from 'primeng/inputicon'
import { IconField } from 'primeng/iconfield'
import { Logo } from '~/shared/components/logo/logo'
import { InputTextModule } from 'primeng/inputtext'
import { Avatar } from 'primeng/avatar'

@Component({
    selector: 'app-dashboard',
    imports: [
        ButtonModule,
        ButtonGroup,
        RouterLink,
        Logo,
        InputIcon,
        IconField,
        InputTextModule,
        Avatar,
    ],
    templateUrl: './dashboard.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Dashboard {}
