import { Routes } from '@angular/router'
import { Dashboard } from './containers/dashboard/dashboard'
import { projectRoutes } from './containers/projects/projects.routes'

export const dashboardRoutes: Routes = [
    {
        path: '',
        component: Dashboard,
        children: [
            {
                path: '',
                redirectTo: 'projects',
                pathMatch: 'full',
            },
            {
                path: 'account',
                loadComponent: () => import('./containers/account/account').then(m => m.Account),
            },
            {
                path: 'projects',
                children: projectRoutes,
            },
        ],
    },
]
