import { ChangeDetectionStrategy, Component, DestroyRef, inject, OnInit } from '@angular/core'
import { Router, RouterLink } from '@angular/router'
import { AuthService } from '~/core/services/auth.service'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'

@Component({
    selector: 'app-navbar',
    imports: [RouterLink],
    templateUrl: './navbar.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Navbar implements OnInit {
    protected authService = inject(AuthService)
    protected router = inject(Router)
    protected destroyRef = inject(DestroyRef)

    protected isAuthenticated = this.authService.isAuthenticated.asReadonly()

    ngOnInit(): void {
        this.authService
            .checkIfAuthenticated()
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe()
    }

    logout() {
        this.authService
            .logout()
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe(() => {
                this.router.navigate(['/'])
            })
    }
}
