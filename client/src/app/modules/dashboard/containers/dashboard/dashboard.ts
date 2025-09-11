import { ChangeDetectionStrategy, Component, computed, signal } from '@angular/core'
import { RouterLink, RouterLinkActive, RouterOutlet } from '@angular/router'
import { ButtonModule } from 'primeng/button'
import { InputIcon } from 'primeng/inputicon'
import { IconField } from 'primeng/iconfield'
import { Logo } from '~/shared/components/logo/logo'
import { InputTextModule } from 'primeng/inputtext'
import { Avatar } from 'primeng/avatar'

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
export class Dashboard {
    isProjectContext = signal(true)

    sidebarLinks = computed<{ label: string; icon?: string; path: string }[]>(() =>
        this.isProjectContext()
            ? [
                  { label: 'Screens', path: 'screens' },
                  { label: 'Email Templates', path: 'email-templates' },
                  { label: 'Settings', path: 'settings' },
              ]
            : [
                  { label: 'My Projects', icon: 'pi pi-th-large', path: 'projects' },
                  { label: 'Account', icon: 'pi pi-user', path: 'account' },
              ],
    )
}
