import { Routes } from '@angular/router'
import { authPagesGuard } from './guards/auth-pages/auth-pages.guard'

export const routes: Routes = [
    {
        path: '',
        loadComponent: () => import('./pages/home-page/home-page').then(m => m.HomePage),
    },
    {
        path: 'login',
        loadComponent: () => import('./pages/login-page/login-page').then(m => m.LoginPage),
        canActivate: [authPagesGuard],
    },
    {
        path: 'dashboard',
        loadComponent: () =>
            import('./pages/dashboard-page/dashboard-page').then(m => m.DashboardPage),
    },
]
