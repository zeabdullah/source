import { ChangeDetectionStrategy, Component } from '@angular/core'
import { ButtonModule } from 'primeng/button'
import { ButtonGroup } from 'primeng/buttongroup'
import { InputIcon } from 'primeng/inputicon'
import { IconField } from 'primeng/iconfield'
import { Logo } from '~/shared/components/logo/logo'
import { InputTextModule } from 'primeng/inputtext'
import { Avatar } from 'primeng/avatar'
import { ProjectCard } from '../../components/project-card/project-card'

@Component({
    selector: 'app-dashboard',
    imports: [
        ButtonModule,
        ButtonGroup,
        ProjectCard,
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
