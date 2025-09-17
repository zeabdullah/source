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
    sidebarLinks: ({ id: number; label: string; path: string } | { id: number })[] = [
        { id: 1, label: 'Screens', path: 'screens' },
        { id: 2, label: 'Email Templates', path: 'email-templates' },
        { id: 3, label: 'Releases', path: 'releases' },
        { id: 4 },
        { id: 5, label: 'Project Settings', path: 'settings' },
    ]
}
