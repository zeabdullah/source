import { inject } from '@angular/core'
import { CanActivateFn, Router } from '@angular/router'
import { map } from 'rxjs/operators'
import { AuthService } from '~/core/services/auth.service'

export const redirectToLoginIfNotLoggedInGuard: CanActivateFn = (_route, _state) => {
    const auth = inject(AuthService)
    const router = inject(Router)

    return auth
        .checkIfAuthenticated()
        .pipe(
            map(isAuthenticated =>
                isAuthenticated ? true : router.createUrlTree(['/auth/login']),
            ),
        )
}
