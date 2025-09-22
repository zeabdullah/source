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
import { Message } from 'primeng/message'
import { Toast } from 'primeng/toast'
import { ProgressSpinner } from 'primeng/progressspinner'
import { HttpErrorResponse } from '@angular/common/http'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { catchError, of } from 'rxjs'
import { EmptyState } from '~/shared/components/empty-state/empty-state'
import { AuthService } from '~/core/services/auth.service'
import { MessageService } from '~/core/services/message.service'
import { ProjectCard } from '../../components/project-card/project-card'
import { NgClass } from '@angular/common'
import { ProjectData } from '../../shared/interfaces/project-data.interface'
import { ProjectRepository } from '../../shared/repositories/project.repository'

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
        NgClass,
    ],
    templateUrl: './projects.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'grow' },
})
export class Projects implements OnInit {
    private authService = inject(AuthService)
    private message = inject(MessageService)
    private projectRepository = inject(ProjectRepository)
    private fb = inject(NonNullableFormBuilder)
    destroyRef = inject(DestroyRef)

    user = this.authService.getUser()
    showDialog = false

    projects = signal<ProjectData[]>([])
    isLoading = signal<boolean>(true)

    projectForm = this.fb.group({
        name: ['', Validators.required],
        description: [''],
    })

    ngOnInit() {
        this.loadProjects()
    }

    resetForm() {
        this.projectForm.reset()
    }

    loadProjects() {
        this.isLoading.set(true)

        this.projectRepository
            .getProjects()
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError((err: HttpErrorResponse) => {
                    this.message.error(
                        'Error',
                        `Failed to load projects. ${err.error?.message || err.message}`,
                    )
                    return of<LaravelApiResponse<ProjectData[]>>({ message: '', payload: [] })
                }),
            )
            .subscribe((response: LaravelApiResponse<ProjectData[]>) => {
                this.projects.set(response.payload || [])
                this.isLoading.set(false)
            })
    }

    createProject() {
        if (!this.projectForm.valid) {
            return
        }

        const formValue = this.projectForm.getRawValue()

        this.projectRepository
            .createProject(formValue)
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError((err: HttpErrorResponse) => {
                    this.message.error(
                        'Error',
                        `Failed to create project. ${err.error?.message || err.message}`,
                    )
                    return of<LaravelApiResponse<ProjectData>>({ message: '', payload: null })
                }),
            )
            .subscribe((response: LaravelApiResponse<ProjectData>) => {
                if (response.payload) {
                    this.projects.update(projects => [...projects, response.payload!])
                    this.projectForm.reset()
                    this.showDialog = false

                    this.message.success('Success', 'Project created successfully')
                }
            })
    }
}
