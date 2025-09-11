import { ChangeDetectionStrategy, Component, input } from '@angular/core'
import { DialogModule } from 'primeng/dialog'
import { CardModule } from 'primeng/card'

interface Screen {
    id: number
    name: string
    section_name: string
    image: string
    device: string
    release: string
}

@Component({
    selector: 'app-screen-details',
    imports: [DialogModule, CardModule],
    templateUrl: './screen-details.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: {
        class: 'block',
    },
})
export class ScreenDetails {
    screen = input<Screen>()
}
