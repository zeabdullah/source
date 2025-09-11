import { ChangeDetectionStrategy, Component } from '@angular/core'
import { RouterLink } from '@angular/router'
import { InputTextModule } from 'primeng/inputtext'

@Component({
    selector: 'app-screens',
    imports: [InputTextModule, RouterLink],
    templateUrl: './screens.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Screens {}
