import { Routes } from '@angular/router'
import { auditRoutes } from './containers/audits/audits.routes'
import { Projects } from './containers/projects/projects'

export const projectRoutes: Routes = [
    {
        path: '',
        component: Projects,
    },
    {
        path: ':projectId',
        loadComponent: () =>
            import('./containers/project-layout/project-layout').then(m => m.ProjectLayout),

        children: [
            {
                path: '',
                redirectTo: 'screens',
                pathMatch: 'full',
            },
            {
                path: 'settings',
                loadComponent: () => import('./containers/settings/settings').then(m => m.Settings),
            },
            {
                path: 'screens',
                loadComponent: () => import('./containers/screens/screens').then(m => m.Screens),
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
                loadComponent: () => import('./containers/releases/releases').then(m => m.Releases),
            },
            {
                path: 'audits',
                children: auditRoutes,
            },
        ],
    },
]
