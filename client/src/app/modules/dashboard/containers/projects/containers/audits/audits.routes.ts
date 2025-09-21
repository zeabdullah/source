import { Routes } from '@angular/router'
import { Audits } from './containers/audits/audits'

export const auditRoutes: Routes = [
    {
        path: '',
        component: Audits,
    },
    {
        path: ':auditId',
        loadComponent: () =>
            import('./containers/audit-details/audit-details').then(m => m.AuditDetails),
    },
]
