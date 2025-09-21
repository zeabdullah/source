import {
    ChangeDetectionStrategy,
    Component,
    input,
    output,
    inject,
    signal,
    DestroyRef,
} from '@angular/core'
import { NonNullableFormBuilder, ReactiveFormsModule, Validators } from '@angular/forms'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { catchError, of } from 'rxjs'
import { CommonModule } from '@angular/common'
import { Button } from 'primeng/button'
import { Dialog } from 'primeng/dialog'
import { Select } from 'primeng/select'
import { InputText } from 'primeng/inputtext'
import { Textarea } from 'primeng/textarea'
import { Message } from 'primeng/message'
import { MultiSelect } from 'primeng/multiselect'
import { ReleaseData } from '../../shared/interfaces/release.interface'
import { AutoComplete } from 'primeng/autocomplete'
import { ReleaseRepository } from '../../shared/repositories/release.repository'
import { MessageService } from '~/core/services/message.service'

@Component({
    selector: 'app-new-release-dialog',
    imports: [
        CommonModule,
        Dialog,
        Button,
        InputText,
        Textarea,
        Select,
        MultiSelect,
        AutoComplete,
        ReactiveFormsModule,
        Message,
    ],
    templateUrl: './new-release-dialog.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class NewReleaseDialog {
    fb = inject(NonNullableFormBuilder)
    releaseRepository = inject(ReleaseRepository)
    messageService = inject(MessageService)
    destroyRef = inject(DestroyRef)

    visible = input<boolean>(false)
    projectId = input<string>('')
    visibleChange = output<boolean>()
    onNewRelease = output<ReleaseData>()

    isCreating = signal(false)

    compareOptions = [
        { label: '1.2.0', value: '1.2.0' },
        { label: '1.1.0', value: '1.1.0' },
        { label: '1.0.0', value: '1.0.0' },
    ]

    screenOptions = [
        { label: 'Welcome Screen', value: 1 },
        { label: 'Login', value: 2 },
    ]
    emailOptions = [
        { label: 'Welcome Message', value: 1 },
        { label: 'Forgot Password', value: 2 },
    ]

    dialogForm = this.fb.group({
        version: this.fb.control('', Validators.required),
        description: this.fb.control(''),
        tags: this.fb.control<string[]>([]),
        screens: this.fb.control<number[]>([]),
        emails: this.fb.control<number[]>([]),
        compare: this.fb.control('1.2.0'),
    })

    onHide() {
        this.visibleChange.emit(false)
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
        }

        this.releaseRepository
            .createRelease(this.projectId(), releaseData)
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    this.messageService.error(
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
