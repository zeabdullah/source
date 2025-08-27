import {
    ApplicationConfig,
    provideBrowserGlobalErrorListeners,
    provideZonelessChangeDetection,
} from '@angular/core'
import { provideRouter } from '@angular/router'
import { provideHttpClient, withInterceptors, HttpInterceptorFn } from '@angular/common/http'

import { routes } from './app.routes'
import { provideClientHydration, withEventReplay } from '@angular/platform-browser'

const baseInterceptor: HttpInterceptorFn = (req, next) => {
    const modifiedReq = req.clone({
        url: 'http://localhost:8000/api' + req.urlWithParams,
        setHeaders: {
            Accept: 'application/json',
        },
    })
    return next(modifiedReq)
}

export const appConfig: ApplicationConfig = {
    providers: [
        provideBrowserGlobalErrorListeners(),
        provideZonelessChangeDetection(),
        provideRouter(routes),
        provideClientHydration(withEventReplay()),
        provideHttpClient(withInterceptors([baseInterceptor])),
    ],
}
