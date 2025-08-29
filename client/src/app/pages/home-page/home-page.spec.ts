import { ComponentFixture, TestBed } from '@angular/core/testing'
import { HomePage } from './home-page'
import { provideZonelessChangeDetection } from '@angular/core'

describe('HomePage', () => {
    let component: HomePage
    let fixture: ComponentFixture<HomePage>

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            imports: [HomePage],
            providers: [provideZonelessChangeDetection()],
        }).compileComponents()

        fixture = TestBed.createComponent(HomePage)
        component = fixture.componentInstance
        fixture.detectChanges()
    })

    it('should create', () => {
        expect(component).toBeTruthy()
    })
})
