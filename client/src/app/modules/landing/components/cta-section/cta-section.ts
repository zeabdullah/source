import { ChangeDetectionStrategy, Component } from '@angular/core'
import { RouterLink } from '@angular/router'

@Component({
    selector: 'app-cta-section',
    templateUrl: './cta-section.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    imports: [RouterLink],
})
export class CtaSection {}
