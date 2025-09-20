import { Injectable, inject } from '@angular/core'
import { MessageService as PrimeNGMessageService } from 'primeng/api'

export interface MessageOptions {
    severity: 'success' | 'info' | 'warn' | 'error' | 'secondary' | 'contrast'
    summary: string
    detail: string
    life?: number
}

export const defaultLife = {
    success: 3000,
    info: 5000,
    warn: 5000,
    error: 10_000,
    secondary: 3000,
    contrast: 3000,
} as const satisfies Record<MessageOptions['severity'], number>

@Injectable({ providedIn: 'root' })
export class MessageService {
    private messageService = inject(PrimeNGMessageService)

    private readonly defaultLife = defaultLife.success

    add(message: MessageOptions) {
        this.messageService.add({
            ...message,
            life: message.life ?? this.defaultLife,
        })
    }

    success(summary: string, detail: string, life?: number) {
        this.messageService.add({
            severity: 'success',
            summary,
            detail,
            life: life ?? defaultLife.success,
        })
    }

    info(summary: string, detail: string, life?: number) {
        this.messageService.add({
            severity: 'info',
            summary,
            detail,
            life: life ?? defaultLife.info,
        })
    }

    warn(summary: string, detail: string, life?: number) {
        this.messageService.add({
            severity: 'warn',
            summary,
            detail,
            life: life ?? defaultLife.warn,
        })
    }

    error(summary: string, detail: string, life?: number) {
        this.messageService.add({
            severity: 'error',
            summary,
            detail,
            life: life ?? defaultLife.error,
        })
    }

    secondary(summary: string, detail: string, life?: number) {
        this.messageService.add({
            severity: 'secondary',
            summary,
            detail,
            life: life ?? defaultLife.secondary,
        })
    }

    contrast(summary: string, detail: string, life?: number) {
        this.messageService.add({
            severity: 'contrast',
            summary,
            detail,
            life: life ?? defaultLife.contrast,
        })
    }
}
