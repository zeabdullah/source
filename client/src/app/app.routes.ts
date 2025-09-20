import { Routes } from '@angular/router'
import { authRoutes } from './modules/auth/auth.routes'
import { landingRoutes } from './modules/landing/landing.routes'

export const routes: Routes = [
    {
        path: '',
        children: landingRoutes,
    },
    {
        path: 'auth',
        children: authRoutes,
    },
    {
        path: 'dashboard',
        loadChildren: () =>
            import('./modules/dashboard/dashboard.routes').then(m => m.dashboardRoutes),
    },
]
