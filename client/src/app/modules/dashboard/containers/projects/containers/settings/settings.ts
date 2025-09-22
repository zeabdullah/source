import {
    ChangeDetectionStrategy,
    Component,
    DestroyRef,
    inject,
    OnInit,
    signal,
} from '@angular/core'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { HttpErrorResponse } from '@angular/common/http'
import { Button } from 'primeng/button'
import { InputText } from 'primeng/inputtext'
import { ReactiveFormsModule, Validators, NonNullableFormBuilder } from '@angular/forms'
import { ActivatedRoute } from '@angular/router'
import { ProjectRepository } from '../../shared/repositories/project.repository'
import { ProjectData } from '../../shared/interfaces/project-data.interface'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { MessageService } from '~/core/services/message.service'
import { Textarea } from 'primeng/textarea'

@Component({
    selector: 'app-settings',
    imports: [ReactiveFormsModule, InputText, Button, Textarea],
    templateUrl: './settings.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Settings implements OnInit {
    private fb = inject(NonNullableFormBuilder)
    private route = inject(ActivatedRoute)
    private projectRepository = inject(ProjectRepository)
    private message = inject(MessageService)
    destroyRef = inject(DestroyRef)

    isSubmitting = signal(false)

    projectId = this.route.parent!.snapshot.paramMap.get('projectId')!

    basicInfoForm = this.fb.group({
        name: ['', Validators.required],
        description: [''],
    })

    ngOnInit() {
        this.projectRepository
            .getProject(this.projectId)
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe({
                next: (response: LaravelApiResponse<ProjectData>) => {
                    this.basicInfoForm.patchValue({
                        name: response.payload!.name,
                        description: response.payload!.description ?? '',
                    })
                },
                error: (err: HttpErrorResponse) => {
                    this.message.error(
                        'Error',
                        `Failed to load project details. ${err.error?.message || err.message}`,
                    )
                },
            })
    }

    saveBasicInfo(): void {
        if (this.basicInfoForm.invalid) {
            return
        }

        this.isSubmitting.set(true)
        this.basicInfoForm.disable()

        const formValue = this.basicInfoForm.getRawValue()

        this.projectRepository
            .updateProject(this.projectId, formValue)
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe({
                next: () => {
                    this.message.success('Success', 'Project updated successfully!')

                    this.isSubmitting.set(false)
                    this.basicInfoForm.enable()
                },
                error: (err: HttpErrorResponse) => {
                    this.message.error(
                        'Error',
                        `Failed to update project. ${err.error?.message || err.message}`,
                    )
                    this.isSubmitting.set(false)
                    this.basicInfoForm.enable()
                },
            })
    }
}
