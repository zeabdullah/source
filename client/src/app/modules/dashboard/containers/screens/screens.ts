import { ChangeDetectionStrategy, Component } from '@angular/core'
import { RouterLink } from '@angular/router'
import { SelectModule } from 'primeng/select'
import { InputTextModule } from 'primeng/inputtext'
import { FormsModule } from '@angular/forms'

interface SelectOption {
    name: string
    value: string
}

@Component({
    selector: 'app-screens',
    imports: [InputTextModule, RouterLink, SelectModule, FormsModule],
    templateUrl: './screens.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Screens {
    languages = [
        { name: 'English', value: 'en' },
        { name: 'Spanish', value: 'es' },
    ] as const satisfies SelectOption[]
    releases = [
        { name: '1.0.2', value: '1.0.2' },
        { name: '1.0.1', value: '1.0.1' },
        { name: '1.0.0', value: '1.0.0' },
    ] as const satisfies SelectOption[]
    devices = [
        { name: 'iPhone 15', value: 'iphone_15' },
        { name: 'iPhone 15 Pro', value: 'iphone_15_pro' },
    ] as const satisfies SelectOption[]

    selectedDevice: (typeof this.devices)[number]['value'] = 'iphone_15'
    selectedRelease: (typeof this.releases)[number]['value'] = '1.0.2'
    selectedLanguage: (typeof this.languages)[number]['value'] = 'en'
}
