import {
    ChangeDetectionStrategy,
    Component,
    computed,
    inject,
    input,
    OnInit,
    output,
    signal,
    DestroyRef,
} from '@angular/core'
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms'
import { Dialog } from 'primeng/dialog'
import { Button } from 'primeng/button'
import { InputText } from 'primeng/inputtext'
import { Textarea } from 'primeng/textarea'
import { MultiSelect } from 'primeng/multiselect'
import { Message } from 'primeng/message'
import { MessageService } from '~/core/services/message.service'
import { HttpClient } from '@angular/common/http'
import { catchError, finalize, of } from 'rxjs'
import { AuditRepository } from '~/modules/dashboard/containers/projects/containers/audits/shared/repositories/audit.repository'
import { ScreenData } from '~/modules/dashboard/containers/projects/shared/interfaces/screen.interface'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { CreateAuditRequest } from '../../shared/interfaces/audit.interface'

@Component({
    selector: 'app-create-audit-dialog',
    imports: [Dialog, Button, InputText, Textarea, MultiSelect, Message, ReactiveFormsModule],
    templateUrl: './create-audit-dialog.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: { class: 'block' },
})
export class CreateAuditDialog implements OnInit {
    private auditRepository = inject(AuditRepository)
    private fb = inject(FormBuilder)
    private message = inject(MessageService)
    private http = inject(HttpClient)
    destroyRef = inject(DestroyRef)

    visible = input<boolean>(false)
    visibleChange = output<boolean>()
    projectId = input.required<number>()

    auditCreated = output<void>()

    private screens = signal<ScreenData[]>([])
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
            .get<LaravelApiResponse<ScreenData[]>>(`/api/projects/${this.projectId()}/screens`)
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    this.message.error(
                        'Error',
                        `Failed to load screens. ${err.error?.message || err.message}`,
                    )
                    return of<LaravelApiResponse<ScreenData[]>>({ message: '', payload: [] })
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
        if (!this.auditForm.valid) {
            this.auditForm.markAllAsTouched()
            return
        }

        this.isSubmitting.set(true)
        const formData = this.auditForm.value as CreateAuditRequest

        this.auditRepository
            .createAudit(this.projectId(), formData)
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    this.message.error(
                        'Error',
                        `Failed to create audit. ${err.error?.message || err.message}`,
                    )
                    return of({ message: '', payload: null })
                }),
                finalize(() => this.isSubmitting.set(false)),
            )
            .subscribe(response => {
                if (response.payload) {
                    this.auditCreated.emit()
                    this.onVisibleChange(false)
                    this.message.success('Success', 'Audit created successfully')
                }
            })
    }
}
