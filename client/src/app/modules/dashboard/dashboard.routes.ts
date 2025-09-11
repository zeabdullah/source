import { Routes } from '@angular/router'
import { Dashboard } from './containers/dashboard/dashboard'

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
                children: [
                    {
                        path: '',
                        loadComponent: () =>
                            import('./containers/projects/projects').then(m => m.Projects),
                    },
                    {
                        path: ':projectId',
                        loadComponent: () =>
                            import('./components/project-layout/project-layout').then(
                                m => m.ProjectLayout,
                            ),

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
                                path: 'releases',
                                loadComponent: () =>
                                    import('./containers/releases/releases').then(m => m.Releases),
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
        ],
    },
]
