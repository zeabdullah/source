import { ChangeDetectionStrategy, Component } from '@angular/core'

@Component({
    selector: 'app-footer',
    templateUrl: './footer.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    imports: [],
})
export class Footer {}
