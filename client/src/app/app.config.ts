import { provideHttpClient, withInterceptors } from '@angular/common/http'
import {
    ApplicationConfig,
    provideBrowserGlobalErrorListeners,
    provideZonelessChangeDetection,
} from '@angular/core'
import { provideClientHydration, withEventReplay } from '@angular/platform-browser'
import { provideRouter } from '@angular/router'
import { routes } from './app.routes'
import { baseInterceptor } from './interceptors/base.interceptor'

export const appConfig: ApplicationConfig = {
    providers: [
        provideBrowserGlobalErrorListeners(),
        provideZonelessChangeDetection(),
        provideRouter(routes),
        provideClientHydration(withEventReplay()),
        provideHttpClient(withInterceptors([baseInterceptor])),
    ],
}
