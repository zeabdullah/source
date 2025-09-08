import { ChangeDetectionStrategy, Component } from '@angular/core'
import { FeaturesSection } from '../../components/features-section/features-section'
import { HeroSection } from '../../components/hero-section/hero-section'
import { TrustingClients } from '../../components/trusting-clients/trusting-clients'
import { CtaSection } from '../../components/cta-section/cta-section'
import { FaqSection } from '../../components/faq-section/faq-section'
import { Navbar } from '~/shared/components/navbar/navbar'
import { Footer } from '~/shared/components/footer/footer'

@Component({
    selector: 'app-home',
    imports: [
        Navbar,
        Footer,
        HeroSection,
        FeaturesSection,
        TrustingClients,
        FaqSection,
        CtaSection,
    ],
    templateUrl: './home.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Home {}
