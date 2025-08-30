import { ChangeDetectionStrategy, Component, inject } from '@angular/core'
import { Router } from '@angular/router'

@Component({
    selector: 'app-cta-section',
    templateUrl: './cta-section.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class CtaSection {
    private router = inject(Router);

    /** Inserted by Angular inject() migration for backwards compatibility */
    constructor(...args: unknown[]);

    constructor() {}

    protected onGetStarted(): void {
        this.router.navigate(['/register'])
    }
}
