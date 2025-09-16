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
        version: ['1.2.1', [Validators.required]],
        description: [''],
        compare: ['1.2.0'],
    })

    onHide() {
        this.visibleChange.emit(false)
    }

    createRelease() {
        if (this.dialogForm.valid) {
            console.log('Creating release:', this.dialogForm.value)
            this.onHide()
        }
    }
}
