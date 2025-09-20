import {
    ChangeDetectionStrategy,
    Component,
    inject,
    signal,
    OnInit,
    DestroyRef,
} from '@angular/core'
import { NonNullableFormBuilder, ReactiveFormsModule, Validators } from '@angular/forms'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { Button } from 'primeng/button'
import { Dialog } from 'primeng/dialog'
import { InputText } from 'primeng/inputtext'
import { Textarea } from 'primeng/textarea'
import { ProjectCard } from '../../components/project-card/project-card'
import { Message } from 'primeng/message'
import { Toast } from 'primeng/toast'
import { ProgressSpinner } from 'primeng/progressspinner'
import { HttpClient } from '@angular/common/http'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { catchError, of } from 'rxjs'
import { EmptyState } from '~/shared/components/empty-state/empty-state'
import { AuthService } from '~/core/services/auth.service'
import { MessageService } from '~/core/services/message.service'

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
        ProgressSpinner,
        EmptyState,
    ],
    templateUrl: './projects.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'grow' },
})
export class Projects implements OnInit {
    authService = inject(AuthService)
    fb = inject(NonNullableFormBuilder)
    http = inject(HttpClient)
    message = inject(MessageService)
    destroyRef = inject(DestroyRef)

    user = this.authService.getUser()
    showDialog = false

    projectForm = this.fb.group({
        name: ['', Validators.required],
        description: [''],
    })

    projects = signal<Project[]>([])
    isLoading = signal<boolean>(true)

    ngOnInit() {
        this.loadProjects()
    }

    resetForm() {
        this.projectForm.reset()
    }

    loadProjects() {
        this.isLoading.set(true)
        this.http
            .get<LaravelApiResponse<Project[]>>('/api/projects')
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    this.message.error(
                        'Error',
                        `Failed to load projects. ${err.error?.message || err.message}`,
                    )
                    return of<LaravelApiResponse<Project[]>>({ message: '', payload: [] })
                }),
            )
            .subscribe(response => {
                this.projects.set(response.payload || [])
                this.isLoading.set(false)
            })
    }

    createProject() {
        if (!this.projectForm.valid) {
            return
        }

        const formData = new FormData()
        formData.append('name', this.projectForm.value.name as string)
        formData.append('description', this.projectForm.value.description || '')

        this.http
            .post<LaravelApiResponse<Project>>('/api/projects', {
                name: this.projectForm.value.name as string,
            })
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    this.message.error(
                        'Error',
                        `Failed to create project. ${err.error?.message || err.message}`,
                    )
                    return of<LaravelApiResponse<Project>>({ message: '', payload: null })
                }),
            )
            .subscribe(response => {
                if (response.payload) {
                    this.projects.update(projects => [...projects, response.payload!])
                    this.projectForm.reset()
                    this.showDialog = false

                    this.message.success('Success', 'Project created successfully')
                }
            })
    }
}
