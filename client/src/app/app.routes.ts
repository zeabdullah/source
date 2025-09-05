import { Routes } from '@angular/router'
import { authRoutes } from './modules/auth/auth.routes'

export const routes: Routes = [
    {
        path: '',
        loadComponent: () => import('./pages/home-page/home-page').then(m => m.HomePage),
    },
    {
        path: 'auth',
        children: authRoutes,
    },
    {
        path: 'dashboard',
        loadComponent: () =>
            import('./pages/dashboard-page/dashboard-page').then(m => m.DashboardPage),
    },
]
