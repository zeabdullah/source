import { ChangeDetectionStrategy, Component, input, output, inject } from '@angular/core'
import { NonNullableFormBuilder, ReactiveFormsModule, Validators } from '@angular/forms'
import { CommonModule } from '@angular/common'
import { Button } from 'primeng/button'
import { Dialog } from 'primeng/dialog'
import { Select } from 'primeng/select'
import { InputText } from 'primeng/inputtext'
import { Textarea } from 'primeng/textarea'
import { Message } from 'primeng/message'
import { MultiSelect } from 'primeng/multiselect'
import { Release } from '../../shared/interfaces/release.interface'
import { AutoComplete } from 'primeng/autocomplete'

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

    visible = input<boolean>(false)
    visibleChange = output<boolean>()
    onNewRelease = output<Release>()

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
        if (!this.dialogForm.valid) {
            return
        }

        const newRelease: Release = {
            version: this.dialogForm.value.version!,
            description: this.dialogForm.value.description!,
            tags: this.dialogForm.value.tags ?? [],
            screensCount: this.dialogForm.value.screens?.length ?? 0,
            emailsCount: this.dialogForm.value.emails?.length ?? 0,
            created_at: new Date().toISOString(),
        }

        this.onNewRelease.emit(newRelease)
        this.onHide()
    }
}
