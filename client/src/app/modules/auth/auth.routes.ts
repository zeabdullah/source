import { Routes } from '@angular/router'
import { redirectToDashboardIfLoggedInGuard } from './shared/guards/redirect-to-dashboard-if-logged-in.guard'

export const authRoutes: Routes = [
    {
        path: 'login',
        loadComponent: () => import('./containers/login/login').then(m => m.Login),
        canActivate: [redirectToDashboardIfLoggedInGuard],
    },
    {
        path: 'register',
        loadComponent: () => import('./containers/register/register').then(m => m.Register),
        canActivate: [redirectToDashboardIfLoggedInGuard],
    },
]
