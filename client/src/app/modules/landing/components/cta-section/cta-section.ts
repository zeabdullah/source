import { ChangeDetectionStrategy, Component, inject } from '@angular/core'
import { Router } from '@angular/router'

@Component({
    selector: 'app-cta-section',
    templateUrl: './cta-section.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    imports: [],
})
export class CtaSection {
    private router = inject(Router)

    protected onGetStarted(): void {
        this.router.navigate(['/register'])
    }
}
