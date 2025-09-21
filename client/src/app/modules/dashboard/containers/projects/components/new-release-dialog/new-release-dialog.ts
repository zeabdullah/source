import {
    ChangeDetectionStrategy,
    Component,
    input,
    output,
    inject,
    signal,
    DestroyRef,
    computed,
    effect,
} from '@angular/core'
import { NonNullableFormBuilder, ReactiveFormsModule, Validators } from '@angular/forms'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { catchError, of } from 'rxjs'
import { CommonModule } from '@angular/common'
import { Button } from 'primeng/button'
import { Dialog } from 'primeng/dialog'
import { InputText } from 'primeng/inputtext'
import { Textarea } from 'primeng/textarea'
import { MultiSelect } from 'primeng/multiselect'
import { ReleaseData } from '../../shared/interfaces/release.interface'
import { AutoComplete } from 'primeng/autocomplete'
import { ReleaseRepository } from '../../shared/repositories/release.repository'
import { ScreenRepository } from '../../shared/repositories/screen.repository'
import { EmailTemplateRepository } from '../../shared/repositories/email-template.repository'
import { MessageService } from '~/core/services/message.service'
import { ScreenData } from '../../shared/interfaces/screen.interface'
import { EmailTemplate } from '../../shared/interfaces/email.interface'

@Component({
    selector: 'app-new-release-dialog',
    imports: [
        CommonModule,
        Dialog,
        Button,
        InputText,
        Textarea,
        MultiSelect,
        AutoComplete,
        ReactiveFormsModule,
    ],
    templateUrl: './new-release-dialog.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class NewReleaseDialog {
    fb = inject(NonNullableFormBuilder)
    releaseRepository = inject(ReleaseRepository)
    screenRepository = inject(ScreenRepository)
    emailTemplateRepository = inject(EmailTemplateRepository)
    message = inject(MessageService)
    destroyRef = inject(DestroyRef)

    visible = input<boolean>(false)
    projectId = input<string>('')
    visibleChange = output<boolean>()
    onNewRelease = output<ReleaseData>()

    isCreating = signal(false)
    screens = signal<ScreenData[]>([])
    emailTemplates = signal<EmailTemplate[]>([])
    isLoadingScreens = signal(false)
    isLoadingEmailTemplates = signal(false)

    screenOptions = computed(() => {
        return this.screens().map(screen => ({
            label: screen.figma_node_name || `Screen ${screen.id}`,
            value: screen.id,
        }))
    })

    emailOptions = computed(() => {
        return this.emailTemplates().map(template => ({
            label: template.section_name,
            value: template.id,
        }))
    })

    dialogForm = this.fb.group({
        version: this.fb.control('', Validators.required),
        description: this.fb.control(''),
        tags: this.fb.control<string[]>([]),
        screens: this.fb.control<number[]>([]),
        emails: this.fb.control<number[]>([]),
    })

    constructor() {
        // Watch for projectId changes to load project data
        effect(() => {
            const projectId = this.projectId()
            if (projectId) {
                this.loadProjectData(projectId)
            }
        })
    }

    onHide() {
        this.visibleChange.emit(false)
    }

    loadProjectData(projectId: string) {
        this.loadScreens(projectId)
        this.loadEmailTemplates(projectId)
    }

    loadScreens(projectId: string) {
        this.isLoadingScreens.set(true)
        this.screenRepository
            .getProjectScreens(projectId)
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    this.message.error(
                        'Error',
                        `Failed to load screens. ${err.error?.message || err.message}`,
                    )
                    return of({ message: '', payload: [] })
                }),
            )
            .subscribe(response => {
                this.screens.set(response.payload || [])
                this.isLoadingScreens.set(false)
            })
    }

    loadEmailTemplates(projectId: string) {
        this.isLoadingEmailTemplates.set(true)
        this.emailTemplateRepository
            .getProjectEmailTemplates(projectId)
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    this.message.error(
                        'Error',
                        `Failed to load email templates. ${err.error?.message || err.message}`,
                    )
                    return of({ message: '', payload: [] })
                }),
            )
            .subscribe(response => {
                this.emailTemplates.set(response.payload || [])
                this.isLoadingEmailTemplates.set(false)
            })
    }

    createRelease() {
        if (!this.dialogForm.valid || !this.projectId()) {
            return
        }

        this.isCreating.set(true)

        const releaseData = {
            version: this.dialogForm.value.version!,
            description: this.dialogForm.value.description || undefined,
            tags: this.dialogForm.value.tags?.join(', ') || undefined,
            screen_ids: this.dialogForm.value.screens || [],
            email_template_ids: this.dialogForm.value.emails || [],
        }

        this.releaseRepository
            .createRelease(this.projectId(), releaseData)
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    this.message.error(
                        'Error',
                        `Failed to create release. ${err.error?.message || err.message}`,
                    )
                    this.isCreating.set(false)
                    return of(null)
                }),
            )
            .subscribe(response => {
                this.isCreating.set(false)
                if (response?.payload) {
                    this.onNewRelease.emit(response.payload)
                    this.dialogForm.reset()
                    this.onHide()
                }
            })
    }
}
