import { ChangeDetectionStrategy, Component, DestroyRef, inject, OnInit } from '@angular/core'
import { Router, RouterLink } from '@angular/router'
import { AuthService } from '~/core/services/auth.service'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'
import { MessageService } from '~/core/services/message.service'

@Component({
    selector: 'app-navbar',
    imports: [RouterLink],
    templateUrl: './navbar.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Navbar implements OnInit {
    protected authService = inject(AuthService)
    protected router = inject(Router)
    protected message = inject(MessageService)
    protected destroyRef = inject(DestroyRef)

    protected isAuthenticated = this.authService.isAuthenticated.asReadonly()

    ngOnInit(): void {
        this.authService
            .checkIfAuthenticated()
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe()
    }

    logoutAndGoHome() {
        this.authService
            .logout()
            .pipe(takeUntilDestroyed(this.destroyRef))
            .subscribe({
                next: async () => {
                    await this.router.navigate(['/'])
                },
                error: err => {
                    console.error('error logging out:', err)
                    this.message.error('Oops', 'Failed to log out.')
                },
            })
    }
}
