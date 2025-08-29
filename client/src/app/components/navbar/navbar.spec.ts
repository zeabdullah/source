import { ComponentFixture, TestBed } from '@angular/core/testing'
import { provideRouter } from '@angular/router'
import { provideZonelessChangeDetection } from '@angular/core'
import { Navbar } from './navbar'
import { AuthService } from '../../services/auth.service'
import { routes } from '../../app.routes'

describe('Navbar', () => {
    let component: Navbar
    let fixture: ComponentFixture<Navbar>

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            imports: [Navbar],
            providers: [provideZonelessChangeDetection(), AuthService, provideRouter(routes)],
        }).compileComponents()

        fixture = TestBed.createComponent(Navbar)
        component = fixture.componentInstance
        await fixture.whenStable()
    })

    it('should create', () => {
        expect(component).toBeTruthy()
    })

    it('should show a login button, when not authenticated', async () => {
        const componentEl: HTMLElement = fixture.nativeElement
        const loginButton = componentEl.querySelector('.btn-primary')
        const dashboardButton = componentEl.querySelector('.btn-secondary')
        const logoutButton = componentEl.querySelector('.btn-danger')

        expect(loginButton).toBeTruthy()
        expect(loginButton?.textContent).toContain('Login')
        expect(dashboardButton).toBeNull()
        expect(logoutButton).toBeNull()
    })

    it('should show a logout and go to dashboard buttons, when authenticated', async () => {
        const componentEl: HTMLElement = fixture.nativeElement
        const loginButton = componentEl.querySelector('.btn-primary')
        const dashboardButton = componentEl.querySelector('.btn-secondary')
        const logoutButton = componentEl.querySelector('.btn-danger')

        expect(loginButton).toBeNull()
        expect(dashboardButton).toBeTruthy()
        expect(dashboardButton?.textContent).toContain('Dashboard')
        expect(logoutButton).toBeTruthy()
        expect(logoutButton?.textContent).toContain('Logout')
    })
})
