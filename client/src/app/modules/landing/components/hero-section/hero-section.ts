import { ChangeDetectionStrategy, Component, signal, inject } from '@angular/core'
import { NonNullableFormBuilder, ReactiveFormsModule, Validators } from '@angular/forms'

@Component({
    selector: 'app-hero-section',
    imports: [ReactiveFormsModule],
    templateUrl: './hero-section.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class HeroSection {
    private fb = inject(NonNullableFormBuilder)

    protected isLoading = signal(false)

    protected signupFb = this.fb.group({
        name: ['', Validators.required],
        email: ['', [Validators.required, Validators.email]],
    })

    protected onSubmit(): void {
        if (this.signupFb.valid) {
            this.isLoading.set(true)
            setTimeout(() => {
                this.isLoading.set(false)
                // this.router.navigate(['/register'])
            }, 1000)
        }
    }
}
