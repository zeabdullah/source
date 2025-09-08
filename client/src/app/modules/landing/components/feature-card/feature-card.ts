import { ChangeDetectionStrategy, Component, input } from '@angular/core'
import { NgOptimizedImage } from '@angular/common'

@Component({
    selector: 'app-feature-card',
    templateUrl: './feature-card.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    imports: [NgOptimizedImage],
})
export class FeatureCard {
    title = input.required<string>()
    description = input.required<string>()
    illustrationSrc = input.required<string>()
    big = input<boolean | undefined>(false)
}
