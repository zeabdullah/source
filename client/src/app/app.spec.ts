import { provideZonelessChangeDetection } from '@angular/core'
import { ComponentFixture, TestBed } from '@angular/core/testing'
import { App } from './app'

describe('App', () => {
    let fixture: ComponentFixture<App>
    beforeEach(async () => {
        await TestBed.configureTestingModule({
            imports: [App],
            providers: [provideZonelessChangeDetection()],
        }).compileComponents()

        fixture = TestBed.createComponent(App)
        fixture.detectChanges()
    })

    it('should create the app', () => {
        const app = fixture.componentInstance
        expect(app).toBeTruthy()
    })

    it('should render a router-outlet', () => {
        const compiled = fixture.nativeElement as HTMLElement
        expect(compiled.querySelector('router-outlet')).not.toBeNull()
    })
})
