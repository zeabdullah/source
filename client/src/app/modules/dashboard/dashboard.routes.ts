import { Routes } from '@angular/router'
import { Dashboard } from './containers/dashboard/dashboard'
import { Projects } from './containers/projects/projects'

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
                path: 'projects',
                children: [
                    {
                        path: '',
                        component: Projects,
                    },
                    {
                        path: ':projectId',
                        children: [
                            {
                                path: '',
                                redirectTo: 'screens',
                                pathMatch: 'full',
                            },
                            {
                                path: 'screens',
                                loadComponent: () =>
                                    import('./containers/screens/screens').then(m => m.Screens),
                            },
                            {
                                path: 'email-templates',
                                loadComponent: () =>
                                    import('./containers/email-templates/email-templates').then(
                                        m => m.EmailTemplates,
                                    ),
                            },
                            {
                                path: 'settings',
                                loadComponent: () =>
                                    import('./containers/settings/settings').then(m => m.Settings),
                            },
                        ],
                    },
                ],
            },
            {
                path: 'account',
                loadComponent: () => import('./containers/account/account').then(m => m.Account),
            },
        ],
    },
]
