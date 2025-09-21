import { Injectable, inject } from '@angular/core'
import { MessageService as PrimeNGMessageService } from 'primeng/api'

export interface MessageOptions {
    severity: 'success' | 'info' | 'warn' | 'error' | 'secondary' | 'contrast'
    summary: string
    detail: string
    life: number
}

export const defaultLife = {
    success: 3000,
    info: 5000,
    warn: 5000,
    error: 10_000,
    secondary: 3000,
    contrast: 3000,
} as const satisfies Record<MessageOptions['severity'], number>

/**
 * A wrapper around the PrimeNG MessageService for more concise usage
 */
@Injectable({ providedIn: 'root' })
export class MessageService {
    private messageService = inject(PrimeNGMessageService)

    add(message: MessageOptions) {
        this.messageService.add({
            ...message,
            life: message.life || defaultLife[message.severity],
        })
    }

    success(summary: string, detail: string, life: number = defaultLife.success) {
        this.messageService.add({
            severity: 'success',
            summary,
            detail,
            life,
        })
    }

    info(summary: string, detail: string, life: number = defaultLife.info) {
        this.messageService.add({
            severity: 'info',
            summary,
            detail,
            life,
        })
    }

    warn(summary: string, detail: string, life: number = defaultLife.warn) {
        this.messageService.add({
            severity: 'warn',
            summary,
            detail,
            life,
        })
    }

    error(summary: string, detail: string, life: number = defaultLife.error) {
        this.messageService.add({
            severity: 'error',
            summary,
            detail,
            life,
        })
    }

    secondary(summary: string, detail: string, life: number = defaultLife.secondary) {
        this.messageService.add({
            severity: 'secondary',
            summary,
            detail,
            life,
        })
    }

    contrast(summary: string, detail: string, life: number = defaultLife.contrast) {
        this.messageService.add({
            severity: 'contrast',
            summary,
            detail,
            life,
        })
    }
}
