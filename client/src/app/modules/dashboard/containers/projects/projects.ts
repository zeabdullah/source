import { ChangeDetectionStrategy, Component } from '@angular/core'
import { ButtonGroup } from 'primeng/buttongroup'
import { Button } from 'primeng/button'
import { ProjectCard } from '../../components/project-card/project-card'

@Component({
    selector: 'app-projects',
    imports: [ButtonGroup, Button, ProjectCard],
    templateUrl: './projects.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Projects {}
