import {
    ChangeDetectionStrategy,
    Component,
    inject,
    OnDestroy,
    OnInit,
    signal,
} from '@angular/core'
import { Router, RouterLink } from '@angular/router'
import { Subscription } from 'rxjs'
import { AuthService } from '~/core/services/auth.service'

@Component({
    selector: 'app-navbar',
    imports: [RouterLink],
    templateUrl: './navbar.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Navbar implements OnInit, OnDestroy {
    protected authService = inject(AuthService)
    protected router = inject(Router)

    protected authCheckSubscription = signal<Subscription | null>(null)
    protected isAuthenticated = this.authService.isAuthenticated.asReadonly()

    ngOnInit(): void {
        this.authCheckSubscription.set(this.authService.checkIfAuthenticated().subscribe())
    }

    ngOnDestroy(): void {
        this.authCheckSubscription()?.unsubscribe()
        this.authCheckSubscription.set(null)
    }

    logout() {
        this.authService.logout().subscribe(() => {
            this.router.navigate(['/'])
        })
    }
}
