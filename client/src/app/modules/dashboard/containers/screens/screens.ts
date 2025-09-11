import { ChangeDetectionStrategy, Component, effect, signal } from '@angular/core'
import { SelectModule } from 'primeng/select'
import { InputTextModule } from 'primeng/inputtext'
import { FormsModule } from '@angular/forms'
import { DrawerModule } from 'primeng/drawer'
import { ScreenDetails } from '../../components/screen-details/screen-details'

interface SelectOption {
    name: string
    value: string
}

@Component({
    selector: 'app-screens',
    imports: [InputTextModule, SelectModule, FormsModule, DrawerModule, ScreenDetails],
    templateUrl: './screens.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: {
        class: 'block',
    },
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

    shownScreenId = signal<number | null>(null)
    drawerVisible = false

    constructor() {
        effect(() => {
            console.log('showing screen:', this.shownScreenId())
        })
    }

    showScreenDetails(index: number) {
        this.shownScreenId.set(index)
        this.drawerVisible = true
    }

    closeExpandedScreen() {
        this.shownScreenId.set(null)
        this.drawerVisible = false
    }
}
