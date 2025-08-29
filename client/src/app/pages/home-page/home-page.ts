import { ChangeDetectionStrategy, Component } from '@angular/core'
import { Navbar } from '../../components/navbar/navbar'

@Component({
    selector: 'app-home-page',
    imports: [Navbar],
    templateUrl: './home-page.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class HomePage {}
