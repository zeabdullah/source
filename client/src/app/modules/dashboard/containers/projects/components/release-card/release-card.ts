import { ChangeDetectionStrategy, Component, input } from '@angular/core'
import { Chip } from 'primeng/chip'
import { Release } from '../../shared/interfaces/release.interface'
import { DatePipe } from '@angular/common'

@Component({
    selector: 'app-release-card',
    imports: [Chip, DatePipe],
    templateUrl: './release-card.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ReleaseCard {
    release = input.required<Release>()
}
