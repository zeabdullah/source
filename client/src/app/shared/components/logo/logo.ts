import { ChangeDetectionStrategy, Component } from '@angular/core'
import { RouterLink } from '@angular/router'

@Component({
    selector: 'app-logo',
    imports: [RouterLink],
    template: `
        <a routerLink="/">
            <img src="/source-logo-brand.svg" alt="Source Logo" class="h-20" alt="Source" />
        </a>
    `,
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Logo {}
