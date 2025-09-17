import { ChangeDetectionStrategy, Component, input, output } from '@angular/core'

@Component({
    selector: 'app-faq-accordion',
    templateUrl: './faq-accordion.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class FaqAccordion {
    question = input.required<string>()
    answer = input.required<string>()
    isOpen = input<boolean>(false)

    toggled = output()

    protected toggleOpen(): void {
        this.toggled.emit()
    }
}
