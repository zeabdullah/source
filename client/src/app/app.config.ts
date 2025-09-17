import { provideHttpClient, withFetch, withInterceptors } from '@angular/common/http'
import {
    ApplicationConfig,
    provideBrowserGlobalErrorListeners,
    provideZonelessChangeDetection,
} from '@angular/core'
import { provideClientHydration, withEventReplay } from '@angular/platform-browser'
import { provideRouter } from '@angular/router'
import { routes } from './app.routes'
import { baseInterceptor } from './shared/interceptors/base.interceptor'
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async'
import { providePrimeNG } from 'primeng/config'
import { primengPreset } from './primeng.preset'

export const appConfig: ApplicationConfig = {
    providers: [
        provideBrowserGlobalErrorListeners(),
        provideZonelessChangeDetection(),
        provideRouter(routes),
        provideClientHydration(withEventReplay()),
        provideHttpClient(withInterceptors([baseInterceptor]), withFetch()),
        provideAnimationsAsync(),
        providePrimeNG({
            theme: {
                preset: primengPreset,
                options: {
                    darkModeSelector: 'light',
                    cssLayer: {
                        name: 'primeng',
                        order: 'theme, base, primeng',
                    },
                },
            },
        }),
    ],
}
