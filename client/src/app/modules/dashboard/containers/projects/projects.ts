import { ChangeDetectionStrategy, Component, inject } from '@angular/core'
import { NonNullableFormBuilder, ReactiveFormsModule, Validators } from '@angular/forms'
import { Button } from 'primeng/button'
import { Dialog } from 'primeng/dialog'
import { InputText } from 'primeng/inputtext'
import { Textarea } from 'primeng/textarea'
import { ProjectCard } from '../../components/project-card/project-card'
import { Message } from 'primeng/message'
import { MessageService } from 'primeng/api'
import { Toast } from 'primeng/toast'

interface Project {
    id: number
    name: string
    description: string | null
    created_at: string
    updated_at: string
}

@Component({
    selector: 'app-projects',
    imports: [
        Button,
        ProjectCard,
        Dialog,
        ReactiveFormsModule,
        InputText,
        Textarea,
        Message,
        Toast,
    ],
    providers: [MessageService],
    templateUrl: './projects.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'grow' },
})
export class Projects {
    fb = inject(NonNullableFormBuilder)
    messageService = inject(MessageService)
    showDialog = false

    projectForm = this.fb.group({
        name: ['', Validators.required],
        description: [''],
    })

    resetForm() {
        this.projectForm.reset()
    }
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
        {
            id: 3,
            name: 'Project 3',
            description: 'Project 3 description',
            created_at: '2025-01-01T00:00:00.000Z',
            updated_at: '2025-01-01T00:00:00.000Z',
        },
        {
            id: 4,
            name: 'Project 4',
            description: 'Project 4 description',
            created_at: '2025-01-01T00:00:00.000Z',
            updated_at: '2025-01-01T00:00:00.000Z',
        },
        {
            id: 5,
            name: 'Project 5',
            description: 'Project 5 description',
            created_at: '2025-01-01T00:00:00.000Z',
            updated_at: '2025-01-01T00:00:00.000Z',
        },
        {
            id: 6,
            name: 'Project 6',
            description: 'Project 6 description',
            created_at: '2025-01-01T00:00:00.000Z',
            updated_at: '2025-01-01T00:00:00.000Z',
        },
        {
            id: 7,
            name: 'Project 7',
            description: 'Project 7 description',
            created_at: '2025-01-01T00:00:00.000Z',
            updated_at: '2025-01-01T00:00:00.000Z',
        },
    ]

    createProject() {
        if (!this.projectForm.valid) {
            return
        }

        this.projects.push({
            id: this.projects.length + 1,
            name: this.projectForm.value.name as string,
            description: this.projectForm.value.description ?? null,
            created_at: '2025-01-01T00:00:00.000Z',
            updated_at: '2025-01-01T00:00:00.000Z',
        })

        this.projectForm.reset()
        this.showDialog = false

        this.messageService.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Project created successfully',
            life: 4000,
        })
    }
}
