import { DatePipe } from '@angular/common'
import { ChangeDetectionStrategy, Component, input } from '@angular/core'
import { RouterLink } from '@angular/router'

interface ProjectCardInput {
    id: number
    name: string
    updated_at: string
}

@Component({
    selector: 'app-project-card',
    imports: [RouterLink, DatePipe],
    templateUrl: './project-card.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ProjectCard {
    project = input.required<ProjectCardInput>()
}
