import { ChangeDetectionStrategy, Component } from '@angular/core'
import { FeatureCard } from '../feature-card/feature-card'

@Component({
    selector: 'app-features-section',
    imports: [FeatureCard],
    templateUrl: './features-section.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class FeaturesSection {
    protected features = [
        {
            title: 'AI Audits & Reviews',
            description:
                'Get instant quality checks, accessibility reports, and compliance reviews',
            illustrationSrc: '/ud-ai.svg',
        },
        {
            title: 'Effortless Edits',
            description:
                "Do changes directly to connected tools like Figma & Brevo, with AI's help",
            illustrationSrc: '/ud-dnd.svg',
        },
        {
            title: 'Unified Workspace',
            description: 'See emails, UI, notifications, and ads together. no more tool-switching.',
            illustrationSrc: '/ud-collab-writing.svg',
        },
        {
            big: true,
            title: 'Collaboration Made Simple',
            description:
                'Invite your team to comment, group screens, and co-edit flows in real time.',
            illustrationSrc: '/ud-collab.svg',
        },
        {
            title: 'Powerful Search',
            description: 'Jump to any flow, screen, or copy with text or natural language search.',
            illustrationSrc: '/ud-docs.svg',
        },
    ]
}
