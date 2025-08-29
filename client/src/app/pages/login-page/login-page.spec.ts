import { ComponentFixture, TestBed } from '@angular/core/testing'
import { provideHttpClientTesting } from '@angular/common/http/testing'
import { LoginPage } from './login-page'
import { provideZonelessChangeDetection } from '@angular/core'
import { provideRouter } from '@angular/router'
import { routes } from '../../app.routes'

describe('LoginPage', () => {
    let component: LoginPage
    let fixture: ComponentFixture<LoginPage>

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            imports: [LoginPage],
            providers: [
                provideZonelessChangeDetection(),
                provideRouter(routes),
                provideHttpClientTesting(),
            ],
        }).compileComponents()

        fixture = TestBed.createComponent(LoginPage)
        component = fixture.componentInstance
        fixture.detectChanges()
    })

    it('should create', () => {
        expect(component).toBeTruthy()
    })
})
