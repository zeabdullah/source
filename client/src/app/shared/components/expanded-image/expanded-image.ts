import { ChangeDetectionStrategy, Component, input } from '@angular/core'

interface Item {
    name: string
    image: string
}

@Component({
    selector: 'app-expanded-image',
    imports: [],
    templateUrl: './expanded-image.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ExpandedImage {
    item = input.required<Item>()
}
