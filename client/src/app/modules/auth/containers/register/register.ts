import { ChangeDetectionStrategy, Component } from '@angular/core'
import { Logo } from '../../components/logo/logo'
import { RegisterForm } from '../../components/forms/register-form/register-form'

@Component({
    selector: 'app-register',
    imports: [Logo, RegisterForm],
    templateUrl: './register.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Register {}
