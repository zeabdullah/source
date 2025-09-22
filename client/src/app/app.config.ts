import { provideHttpClient, withFetch, withInterceptors } from '@angular/common/http'
import {
    ApplicationConfig,
    makeEnvironmentProviders,
    provideBrowserGlobalErrorListeners,
    provideZonelessChangeDetection,
} from '@angular/core'
import { provideClientHydration, withEventReplay } from '@angular/platform-browser'
import { provideRouter } from '@angular/router'
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async'
import { provideMarkdown } from 'ngx-markdown'
import { ConfirmationService, MessageService as PrimeNGMessageService } from 'primeng/api'
import { providePrimeNG } from 'primeng/config'
import { baseInterceptor } from './shared/interceptors/base.interceptor'
import { routes } from './app.routes'
import { primengPreset } from './primeng.preset'

export const appConfig: ApplicationConfig = {
    providers: [
        provideBrowserGlobalErrorListeners(),
        provideZonelessChangeDetection(),
        provideRouter(routes),
        provideClientHydration(withEventReplay()),
        provideHttpClient(withInterceptors([baseInterceptor]), withFetch()),
        provideMarkdown(),
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
        makeEnvironmentProviders([PrimeNGMessageService, ConfirmationService]),
    ],
}
