import { ChangeDetectionStrategy, Component } from '@angular/core'

@Component({
    selector: 'app-trusting-clients',
    templateUrl: './trusting-clients.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class TrustingClients {
    protected clients = [
        { name: 'Company 1', logo: this.getCompany1Logo() },
        { name: 'Company 2', logo: this.getCompany2Logo() },
        { name: 'Company 3', logo: this.getCompany3Logo() },
        { name: 'Company 4', logo: this.getCompany4Logo() },
        { name: 'Company 5', logo: this.getCompany5Logo() },
        { name: 'Company 6', logo: this.getCompany6Logo() }
    ]

    private getCompany1Logo(): string {
        return `<svg width="105" height="40" viewBox="0 0 105 40" fill="none">
            <rect width="105" height="40" fill="#000000"/>
            <text x="52.5" y="25" text-anchor="middle" fill="white" font-family="Arial" font-size="12">COMPANY 1</text>
        </svg>`
    }

    private getCompany2Logo(): string {
        return `<svg width="220" height="40" viewBox="0 0 220 40" fill="none">
            <rect width="220" height="40" fill="#0E1534"/>
            <text x="110" y="25" text-anchor="middle" fill="white" font-family="Arial" font-size="12">COMPANY 2</text>
        </svg>`
    }

    private getCompany3Logo(): string {
        return `<svg width="176" height="40" viewBox="0 0 176 40" fill="none">
            <rect width="176" height="40" fill="#283841"/>
            <text x="88" y="25" text-anchor="middle" fill="white" font-family="Arial" font-size="12">COMPANY 3</text>
        </svg>`
    }

    private getCompany4Logo(): string {
        return `<svg width="169" height="40" viewBox="0 0 169 40" fill="none">
            <rect width="169" height="40" fill="#283841"/>
            <text x="84.5" y="25" text-anchor="middle" fill="white" font-family="Arial" font-size="12">COMPANY 4</text>
        </svg>`
    }

    private getCompany5Logo(): string {
        return `<svg width="220" height="40" viewBox="0 0 220 40" fill="none">
            <rect width="220" height="40" fill="#0E1534"/>
            <text x="110" y="25" text-anchor="middle" fill="white" font-family="Arial" font-size="12">COMPANY 5</text>
        </svg>`
    }

    private getCompany6Logo(): string {
        return `<svg width="176" height="40" viewBox="0 0 176 40" fill="none">
            <rect width="176" height="40" fill="#283841"/>
            <text x="88" y="25" text-anchor="middle" fill="white" font-family="Arial" font-size="12">COMPANY 6</text>
        </svg>`
    }
}
