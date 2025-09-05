import { ChangeDetectionStrategy, Component } from '@angular/core'
import { RegisterForm } from '../../components/register-form/register-form'
import { Logo } from '../../../../shared/components/logo/logo'

@Component({
    selector: 'app-register',
    imports: [Logo, RegisterForm],
    templateUrl: './register.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Register {}
