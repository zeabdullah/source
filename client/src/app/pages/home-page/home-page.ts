import { ChangeDetectionStrategy, Component } from '@angular/core'
import { CtaSection } from '../../shared/components/cta-section/cta-section'
import { FaqSection } from '../../shared/components/faq-section/faq-section'
import { FeaturesSection } from '../../shared/components/features-section/features-section'
import { HeroSection } from '../../shared/components/hero-section/hero-section'
import { Navbar } from '../../shared/components/navbar/navbar'
import { TrustingClients } from '../../shared/components/trusting-clients/trusting-clients'
import { Footer } from '../../shared/components/footer/footer'
import { Button } from 'primeng/button'

@Component({
    selector: 'app-home-page',
    imports: [
        Button,
        Navbar,
        HeroSection,
        FeaturesSection,
        TrustingClients,
        FaqSection,
        CtaSection,
        Footer,
    ],
    templateUrl: './home-page.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class HomePage {}
