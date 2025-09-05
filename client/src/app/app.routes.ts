import { Routes } from '@angular/router'
import { authRoutes } from './modules/auth/auth.routes'
import { landingRoutes } from './modules/landing/landing.routes'
import { dashboardRoutes } from './modules/dashboard/dashboard.routes'

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
        children: dashboardRoutes,
    },
]
