import { ChangeDetectionStrategy, Component } from '@angular/core'
import { RouterLink } from '@angular/router'

@Component({
    selector: 'app-footer',
    templateUrl: './footer.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    imports: [RouterLink],
})
export class Footer {}
