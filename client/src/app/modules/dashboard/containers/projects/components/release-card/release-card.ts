import { ChangeDetectionStrategy, Component, input, computed } from '@angular/core'
import { Chip } from 'primeng/chip'
import { ReleaseData } from '../../shared/interfaces/release.interface'
import { DatePipe, TitleCasePipe } from '@angular/common'

@Component({
    selector: 'app-release-card',
    imports: [Chip, DatePipe, TitleCasePipe],
    templateUrl: './release-card.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ReleaseCard {
    release = input.required<ReleaseData>()

    tags = computed(() => {
        const tagsString = this.release().tags
        return tagsString
            ? tagsString
                  .split(',')
                  .map(tag => tag.trim())
                  .filter(tag => tag)
            : []
    })

    screensCount = computed(() => this.release().screens?.length)
    emailsCount = computed(() => this.release().email_templates?.length)
}
