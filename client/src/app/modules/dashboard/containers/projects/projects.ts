import { ChangeDetectionStrategy, Component } from '@angular/core'
import { Button } from 'primeng/button'
import { ProjectCard } from '../../components/project-card/project-card'

interface Project {
    id: number
    name: string
    description: string | null
    created_at: string
    updated_at: string
}

@Component({
    selector: 'app-projects',
    imports: [Button, ProjectCard],
    templateUrl: './projects.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: {
        class: 'grow',
    },
})
export class Projects {
    projects: Project[] = [
        {
            id: 1,
            name: 'Project 1',
            description: 'Project 1 description',
            created_at: '2025-01-01T00:00:00.000Z',
            updated_at: '2025-01-01T00:00:00.000Z',
        },
        {
            id: 2,
            name: 'Project 2',
            description: 'Project 2 description',
            created_at: '2025-01-01T00:00:00.000Z',
            updated_at: '2025-01-01T00:00:00.000Z',
        },
    ]
}
