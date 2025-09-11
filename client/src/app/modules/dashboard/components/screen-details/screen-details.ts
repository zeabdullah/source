import { ChangeDetectionStrategy, Component, input } from '@angular/core'
import { DialogModule } from 'primeng/dialog'
import { CardModule } from 'primeng/card'

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
    id = input<number>()
    imgSrc = input<string>()
}
