import { ChangeDetectionStrategy, Component } from '@angular/core'

@Component({
    selector: 'app-home-page',
    imports: [],
    templateUrl: './home.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class HomePage {}
