import { inject, Injectable, signal } from '@angular/core'
import { Observable, of } from 'rxjs'
import { catchError, map } from 'rxjs/operators'
import { SessionUser } from '~/shared/interfaces/session-user.interface'
import { AuthRepository } from '~/core/repositories/auth.repository'

@Injectable({ providedIn: 'root' })
export class AuthService {
    protected authRepository = inject(AuthRepository)

    public user = signal<SessionUser | null>(null)
    public isAuthenticated = signal<boolean>(false)

    checkIfAuthenticated(): Observable<boolean> {
        return this.authRepository.me().pipe(
            map(res => {
                this.user.set(res.payload)
                this.isAuthenticated.set(true)
                return true
            }),
            catchError(() => of(false)),
        )
    }

    getUser() {
        return this.user.asReadonly()
    }

    logout(): Observable<boolean> {
        return this.authRepository.logout().pipe(
            map(res => {
                if (res.ok) {
                    this.user.set(null)
                    this.isAuthenticated.set(false)
                    return true
                }
                return false
            }),
            catchError(() => of(false)),
        )
    }
}
