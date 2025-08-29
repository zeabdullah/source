import { ChangeDetectionStrategy, Component } from '@angular/core'
import { Navbar } from '../../components/navbar/navbar'

@Component({
    selector: 'app-dashboard-page',
    imports: [Navbar],
    templateUrl: './dashboard-page.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class DashboardPage {}
