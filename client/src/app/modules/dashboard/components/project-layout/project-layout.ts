import { ChangeDetectionStrategy, Component } from '@angular/core'
import { RouterLink, RouterLinkActive, RouterOutlet } from '@angular/router'

@Component({
    selector: 'app-project-layout',
    imports: [RouterOutlet, RouterLink, RouterLinkActive],
    templateUrl: './project-layout.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: {
        class: 'overflow-hidden flex grow',
    },
})
export class ProjectLayout {
    sidebarLinks = [
        { label: 'Screens', path: 'screens' },
        { label: 'Email Templates', path: 'email-templates' },
        { label: 'Project Settings', path: 'settings' },
    ]
}
