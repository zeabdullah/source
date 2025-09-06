import { Routes } from '@angular/router'

export const landingRoutes: Routes = [
    {
        path: '',
        loadComponent: () => import('./containers/home/home').then(m => m.Home),
    },
]
