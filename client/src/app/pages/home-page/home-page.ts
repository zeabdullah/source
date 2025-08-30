import { ChangeDetectionStrategy, Component } from '@angular/core'
import { Navbar } from '../../components/navbar/navbar'
import { HeroSection } from '../../components/hero-section/hero-section'
import { FeaturesSection } from '../../components/features-section/features-section'
import { TrustingClients } from '../../components/trusting-clients/trusting-clients'
import { FaqSection } from '../../components/faq-section/faq-section'
import { CtaSection } from '../../components/cta-section/cta-section'
import { Footer } from '../../components/footer/footer'

@Component({
    selector: 'app-home-page',
    imports: [
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
