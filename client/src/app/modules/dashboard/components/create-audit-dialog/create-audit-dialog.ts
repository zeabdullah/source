import {
    ChangeDetectionStrategy,
    Component,
    computed,
    inject,
    input,
    OnInit,
    output,
    signal,
} from '@angular/core'
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms'
import { Dialog } from 'primeng/dialog'
import { Button } from 'primeng/button'
import { InputText } from 'primeng/inputtext'
import { Textarea } from 'primeng/textarea'
import { MultiSelect } from 'primeng/multiselect'
import { Message } from 'primeng/message'
import { MessageService } from 'primeng/api'
import { HttpClient } from '@angular/common/http'
import { catchError, finalize, of } from 'rxjs'
import { AuditService } from '~/core/services/audit.service'
import { CreateAuditRequest } from '~/shared/interfaces/modules/dashboard/shared/interfaces/audit.interface'
import { Screen } from '~/modules/dashboard/shared/interfaces/screen.interface'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'

@Component({
    selector: 'app-create-audit-dialog',
    imports: [Dialog, Button, InputText, Textarea, MultiSelect, Message, ReactiveFormsModule],
    providers: [MessageService],
    templateUrl: './create-audit-dialog.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'block' },
})
export class CreateAuditDialog implements OnInit {
    private auditService = inject(AuditService)
    private fb = inject(FormBuilder)
    private messageService = inject(MessageService)
    private http = inject(HttpClient)

    visible = input<boolean>(false)
    visibleChange = output<boolean>()
    projectId = input.required<number>()

    auditCreated = output<void>()

    private screens = signal<Screen[]>([])
    private isLoading = signal(false)
    private isSubmitting = signal(false)

    auditForm: FormGroup = this.fb.group({
        name: ['', [Validators.required, Validators.maxLength(255)]],
        description: [''],
        screen_ids: [[], [Validators.required, Validators.minLength(2), Validators.maxLength(7)]],
    })

    screensList = this.screens.asReadonly()
    loading = this.isLoading.asReadonly()
    submitting = this.isSubmitting.asReadonly()

    screenOptions = computed(() =>
        this.screensList().map(screen => ({
            label: screen.figma_node_name || `Screen ${screen.id}`,
            value: screen.id,
            image: screen.figma_svg_url,
        })),
    )

    ngOnInit() {
        this.loadScreens()
    }

    private loadScreens() {
        this.isLoading.set(true)
        this.http
            .get<LaravelApiResponse<Screen[]>>(`/api/projects/${this.projectId()}/screens`)
            .pipe(
                catchError(err => {
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to load screens. ' + err.message,
                        life: 10_000,
                    })
                    return of<LaravelApiResponse<Screen[]>>({ message: '', payload: [] })
                }),

                finalize(() => this.isLoading.set(false)),
            )
            .subscribe(response => {
                this.screens.set(response.payload || [])
            })
    }

    onVisibleChange(visible: boolean) {
        if (!visible) {
            this.resetForm()
            this.visibleChange.emit(false)
        }
    }

    private resetForm() {
        this.auditForm.reset()
        this.auditForm.patchValue({
            name: '',
            description: '',
            screen_ids: [],
        })
    }

    onSubmit() {
        if (this.auditForm.valid) {
            this.isSubmitting.set(true)
            const formData = this.auditForm.value as CreateAuditRequest

            this.auditService
                .createAudit(this.projectId(), formData)
                .pipe(
                    catchError(error => {
                        console.error('Failed to create audit:', error)
                        this.messageService.add({
                            severity: 'error',
                            summary: 'Error',
                            detail: 'Failed to create audit. Please try again.',
                            life: 5000,
                        })
                        return of({ message: '', payload: null })
                    }),
                    finalize(() => this.isSubmitting.set(false)),
                )
                .subscribe(response => {
                    if (response.payload) {
                        this.auditCreated.emit()
                        this.onVisibleChange(false)
                        this.messageService.add({
                            severity: 'success',
                            summary: 'Success',
                            detail: 'Audit created successfully',
                            life: 3000,
                        })
                    }
                })
        } else {
            this.auditForm.markAllAsTouched()
        }
    }
}
