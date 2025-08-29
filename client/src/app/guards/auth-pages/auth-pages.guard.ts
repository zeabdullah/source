import { inject } from '@angular/core'
import { CanActivateFn, Router } from '@angular/router'
import { map } from 'rxjs/operators'
import { AuthService } from '../../services/auth.service'

export const authPagesGuard: CanActivateFn = (_route, _state) => {
    const auth = inject(AuthService)
    const router = inject(Router)

    return auth
        .isAuthenticated()
        .pipe(
            map(isAuthenticated => (isAuthenticated ? router.createUrlTree(['/dashboard']) : true)),
        )
}
