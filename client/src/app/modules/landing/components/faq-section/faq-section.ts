import { ChangeDetectionStrategy, Component, signal } from '@angular/core'
import { FaqAccordion } from '../faq-accordion/faq-accordion'

@Component({
    selector: 'app-faq-section',
    imports: [FaqAccordion],
    templateUrl: './faq-section.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class FaqSection {
    protected openFaqIndex = signal<number | null>(1) // Start with second FAQ open

    protected faqs = [
        {
            question: 'What tools can I connect?',
            answer: "Source integrates with popular design and marketing tools including Figma and Brevo, with support coming to many more. We're constantly working on adding new integrations.",
        },
        {
            question: 'How does the AI actually help?',
            answer: 'Our AI reviews your flows for quality, accessibility, and compliance, then suggests or applies edits directly.',
        },
        {
            question: 'Can I use Source without design tools?',
            answer: 'Yes! While Source works great with design tools, you can also use it as a standalone platform for managing user flows, creating documentation, and collaborating with your team.',
        },
        {
            question: 'Does it work for marketing teams too?',
            answer: 'Definitely! We unify marketing assets like emails, ads, alongside product UI flows.',
        },
    ]

    protected toggleFaq(index: number): void {
        if (this.openFaqIndex() === index) {
            this.openFaqIndex.set(null)
        } else {
            this.openFaqIndex.set(index)
        }
    }
}
