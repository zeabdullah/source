import {
    ChangeDetectionStrategy,
    Component,
    computed,
    inject,
    input,
    output,
    signal,
} from '@angular/core'
import { DatePipe } from '@angular/common'
import { FormsModule } from '@angular/forms'
import { catchError, of } from 'rxjs'
import { Button } from 'primeng/button'
import { Card } from 'primeng/card'
import { Checkbox } from 'primeng/checkbox'
import { Dialog } from 'primeng/dialog'
import { InputText } from 'primeng/inputtext'
import { MessageService } from 'primeng/api'
import { ProgressSpinner } from 'primeng/progressspinner'
import { Toast } from 'primeng/toast'
import { BrevoService } from '~/core/services/brevo.service'
import {
    BrevoTemplate,
    GetBrevoTemplatesResponse,
} from '../../shared/interfaces/brevo-template.interface'
import { EmailTemplate } from '../../shared/interfaces/email.interface'
import { HttpErrorResponse } from '@angular/common/http'

@Component({
    selector: 'app-brevo-template-selector',
    imports: [
        DatePipe,
        FormsModule,
        Button,
        Card,
        Checkbox,
        Dialog,
        InputText,
        ProgressSpinner,
        Toast,
    ],
    providers: [MessageService],
    templateUrl: './brevo-template-selector.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class BrevoTemplateSelector {
    private brevoService = inject(BrevoService)
    private messageService = inject(MessageService)

    // Inputs
    projectId = input.required<string>()
    visible = input<boolean>(false)
    existingTemplates = input<EmailTemplate[]>([])

    // Outputs
    visibleChange = output<boolean>()
    templatesImported = output<EmailTemplate[]>()

    // State
    brevoTemplates = signal<BrevoTemplate[]>([])
    selectedTemplateIds = signal<string[]>([])
    searchQuery = signal<string>('')
    isLoading = signal<boolean>(false)
    isImporting = signal<boolean>(false)

    // Computed
    filteredTemplates = computed(() => {
        const templates = this.brevoTemplates()
        const query = this.searchQuery().toLowerCase()

        if (!query) return templates

        return templates.filter(
            template =>
                template.name.toLowerCase().includes(query) ||
                template.subject.toLowerCase().includes(query),
        )
    })

    alreadyImportedIds = computed(() => {
        return this.existingTemplates()
            .filter(template => template.brevo_template_id)
            .map(template => template.brevo_template_id!)
    })

    availableTemplates = computed(() => {
        return this.filteredTemplates().filter(
            template => !this.alreadyImportedIds().includes(template.id),
        )
    })

    canImport = computed(() => {
        return this.selectedTemplateIds().length > 0 && !this.isImporting()
    })

    // Methods
    onVisibleChange(visible: boolean) {
        this.visibleChange.emit(visible)
        if (visible) {
            this.loadBrevoTemplates()
        } else {
            this.resetState()
        }
    }

    loadBrevoTemplates() {
        this.isLoading.set(true)
        this.brevoService
            .getTemplates()
            .pipe(
                catchError((err: HttpErrorResponse) => {
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail:
                            'Failed to load Brevo templates: ' + err.error
                                ? err.error.message
                                : err.message,
                        life: 4000,
                    })
                    return of({ message: '', payload: [] })
                }),
            )
            .subscribe(response => {
                this.brevoTemplates.set(
                    (response as GetBrevoTemplatesResponse).payload?.templates || [],
                )
                this.isLoading.set(false)
            })
    }

    toggleTemplateSelection(templateId: string) {
        const current = this.selectedTemplateIds()
        if (current.includes(templateId)) {
            this.selectedTemplateIds.set(current.filter(id => id !== templateId))
        } else {
            this.selectedTemplateIds.set([...current, templateId])
        }
    }

    selectAllTemplates() {
        const availableIds = this.availableTemplates().map(template => template.id)
        this.selectedTemplateIds.set(availableIds)
    }

    clearSelection() {
        this.selectedTemplateIds.set([])
    }

    importSelectedTemplates() {
        const selectedIds = this.selectedTemplateIds()
        if (selectedIds.length === 0) return

        this.isImporting.set(true)

        if (selectedIds.length === 1) {
            // Single template import
            this.brevoService.importTemplate(this.projectId(), selectedIds[0]).subscribe({
                next: response => {
                    this.messageService.add({
                        severity: 'success',
                        summary: 'Success',
                        detail: 'Template imported successfully!',
                        life: 4000,
                    })
                    this.templatesImported.emit([response.payload!])
                    this.onVisibleChange(false)
                },
                error: (err: HttpErrorResponse) => {
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail:
                            'Failed to import template: ' + err.error
                                ? err.error.message
                                : err.message,
                        life: 4000,
                    })
                    this.isImporting.set(false)
                },
            })
        } else {
            // Multiple templates import
            this.brevoService.importMultipleTemplates(this.projectId(), selectedIds).subscribe({
                next: response => {
                    this.messageService.add({
                        severity: 'success',
                        summary: 'Success',
                        detail: `Successfully imported ${response.payload!.length} templates!`,
                        life: 4000,
                    })
                    this.templatesImported.emit(response.payload!)
                    this.onVisibleChange(false)
                },
                error: (err: HttpErrorResponse) => {
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail:
                            'Failed to import templates: ' + err.error
                                ? err.error.message
                                : err.message,
                        life: 4000,
                    })
                    this.isImporting.set(false)
                },
            })
        }
    }

    private resetState() {
        this.selectedTemplateIds.set([])
        this.searchQuery.set('')
        this.isImporting.set(false)
    }

    getTemplatePreview(template: BrevoTemplate): string {
        // Create a simple preview by extracting text content from HTML
        const div = document.createElement('div')
        div.innerHTML = template.htmlContent
        const textContent = div.textContent || div.innerText || ''
        return textContent.substring(0, 100) + (textContent.length > 100 ? '...' : '')
    }
}
