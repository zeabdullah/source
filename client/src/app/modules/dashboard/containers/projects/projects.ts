import { ChangeDetectionStrategy, Component } from '@angular/core'
import { Button } from 'primeng/button'
import { ProjectCard } from '../../components/project-card/project-card'

@Component({
    selector: 'app-projects',
    imports: [Button, ProjectCard],
    templateUrl: './projects.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: {
        class: 'grow',
    },
})
export class Projects {}
