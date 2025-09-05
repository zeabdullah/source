import { Routes } from '@angular/router'

export const dashboardRoutes: Routes = [
    {
        path: '',
        loadComponent: () => import('./containers/dashboard/dashboard').then(m => m.Dashboard),
    },
]
