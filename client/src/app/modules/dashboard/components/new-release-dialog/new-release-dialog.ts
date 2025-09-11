import { ChangeDetectionStrategy, Component, input, output, inject } from '@angular/core'
import {
    FormArray,
    FormControl,
    NonNullableFormBuilder,
    ReactiveFormsModule,
    Validators,
} from '@angular/forms'
import { ButtonModule } from 'primeng/button'
import { DialogModule } from 'primeng/dialog'
import { SelectModule } from 'primeng/select'
import { InputText } from 'primeng/inputtext'
import { Textarea } from 'primeng/textarea'
import { CommonModule } from '@angular/common'
import { CheckboxModule } from 'primeng/checkbox'

interface TrackableItem {
    id: number
    name: string
    type: 'screen' | 'email'
    modifiedAt: string
}

@Component({
    selector: 'app-new-release-dialog',
    imports: [
        CommonModule,
        DialogModule,
        ButtonModule,
        InputText,
        Textarea,
        SelectModule,
        ReactiveFormsModule,
        CheckboxModule,
    ],
    templateUrl: './new-release-dialog.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class NewReleaseDialog {
    fb = inject(NonNullableFormBuilder)
    visible = input<boolean>(false)
    visibleChange = output<boolean>()

    trackableItems: TrackableItem[] = [
        { id: 1, name: 'Welcome Screen', type: 'screen', modifiedAt: '2h ago' },
        { id: 2, name: 'Login', type: 'screen', modifiedAt: '1d ago' },
        { id: 3, name: 'Welcome Screen', type: 'screen', modifiedAt: '2h ago' },
        { id: 4, name: 'Welcome Message', type: 'email', modifiedAt: '2h ago' },
        { id: 5, name: 'Forgot Password', type: 'email', modifiedAt: '1d ago' },
    ]
    compareOptions = [
        { label: '1.2.0', value: '1.2.0' },
        { label: '1.1.0', value: '1.1.0' },
        { label: '1.0.0', value: '1.0.0' },
    ]

    dialogForm = this.fb.group({
        version: ['1.2.1', [Validators.required]],
        description: [''],
        compare: ['1.2.0'],
    })

    constructor() {
        this.dialogForm.valueChanges.subscribe(value => {
            console.log(value)
        })
    }

    onHide() {
        this.visibleChange.emit(false)
    }

    createRelease() {
        if (this.dialogForm.valid) {
            console.log('Creating release:', this.dialogForm.value)
            this.onHide()
        }
    }

    // toggleAllScreens() {
    //     if (this.allScreensSelected()) {
    //         this.dialogForm
    //             .get('trackableItems')
    //             ?.setValue(Array(this.trackableItems.length).fill(false))
    //     } else {
    //         this.dialogForm
    //             .get('trackableItems')
    //             ?.setValue(Array(this.trackableItems.length).fill(true))
    //     }
    // }

    get screens() {
        return this.trackableItems.filter(item => item.type === 'screen')
    }

    get trackables() {
        return this.dialogForm.get('trackableItems')?.value as
            | FormArray<FormControl<boolean>>
            | undefined
    }

    allScreensSelected() {
        return this.trackables?.value?.every(item => item === true)
    }
    someScreensSelected() {
        return this.trackables?.value?.some(item => item === true)
    }
}
