import { ChangeDetectionStrategy, Component } from '@angular/core'
import { Navbar } from '../../../../shared/components/navbar/navbar'

@Component({
    selector: 'app-dashboard',
    imports: [Navbar],
    templateUrl: './dashboard.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Dashboard {}
