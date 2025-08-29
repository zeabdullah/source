import { ChangeDetectionStrategy, Component } from '@angular/core'
import { Logo } from '../../components/logo/logo'
import { RegisterForm } from '../../components/forms/register-form/register-form'

@Component({
    selector: 'app-register-page',
    imports: [Logo, RegisterForm],
    templateUrl: './register-page.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class RegisterPage {}
