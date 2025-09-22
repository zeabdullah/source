import {
    ChangeDetectionStrategy,
    Component,
    computed,
    signal,
    inject,
    DestroyRef,
} from '@angular/core'
import { FormsModule } from '@angular/forms'
import { HttpClient } from '@angular/common/http'
import { ActivatedRoute } from '@angular/router'
import { catchError, of } from 'rxjs'
import { Button } from 'primeng/button'
import { Drawer } from 'primeng/drawer'
import { Select } from 'primeng/select'
import { TabsModule } from 'primeng/tabs'
import { MessageService } from '~/core/services/message.service'
import { ProgressSpinner } from 'primeng/progressspinner'
import { SelectOption } from '~/shared/interfaces/select-option.interface'
import { EmailTemplate } from '../../shared/interfaces/email.interface'
import { CommentsPanel } from '../../components/comments-panel/comments-panel'
import { AiChatPanel } from '../../components/ai-chat-panel/ai-chat-panel'
import { BrevoTemplateSelector } from '../../components/brevo-template-selector/brevo-template-selector'
import { EmptyState } from '~/shared/components/empty-state/empty-state'
import { ExpandedImage } from '~/shared/components/expanded-image/expanded-image'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'
import { takeUntilDestroyed } from '@angular/core/rxjs-interop'

@Component({
    selector: 'app-email-templates',
    imports: [
        FormsModule,
        Button,
        Drawer,
        Select,
        TabsModule,
        ProgressSpinner,
        ExpandedImage,
        EmptyState,
        CommentsPanel,
        AiChatPanel,
        BrevoTemplateSelector,
    ],
    providers: [MessageService],
    templateUrl: './email-templates.html',
    styles: `
        ::ng-deep .p-drawer-content {
            overflow: hidden;
            padding-inline-end: 0;
            padding-bottom: 0;
        }
    `,
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class EmailTemplates {
    http = inject(HttpClient)
    route = inject(ActivatedRoute)
    message = inject(MessageService)
    destroyRef = inject(DestroyRef)

    releases = [
        { name: 'All', value: 'all' },
        { name: '1.0.2', value: '1.0.2' },
        { name: '1.0.1', value: '1.0.1' },
        { name: '1.0.0', value: '1.0.0' },
    ] as const satisfies SelectOption[]

    selectedRelease = signal<(typeof this.releases)[number]['value']>('all')
    shownEmailId = signal<number | null>(null)
    emailTemplates = signal<EmailTemplate[]>([])
    isLoading = signal<boolean>(true)

    drawerVisible = false
    activeTab: 'comments' | 'ai-chat' = 'comments'
    brevoSelectorVisible = signal<boolean>(false) // TODO: this needs to be a banana in a box and used with an ngModel

    filteredEmailTemplates = computed(() => {
        // Since the backend doesn't have release filtering yet, we'll return all templates
        // This can be updated when release filtering is implemented
        return this.emailTemplates()
    })

    constructor() {
        const projectId = this.route.parent?.snapshot.paramMap.get('projectId')
        if (projectId) {
            this.loadEmailTemplates(projectId)
        }
    }

    emailTemplatesBySection = computed(() => {
        const grouped = this.filteredEmailTemplates().reduce(
            (acc, template) => {
                const sectionName = template.section_name || 'Uncategorized'
                if (!acc[sectionName]) {
                    acc[sectionName] = []
                }
                acc[sectionName].push(template)
                return acc
            },
            {} as Record<string, EmailTemplate[]>,
        )

        return Object.entries(grouped).map(([sectionName, templates]) => ({
            sectionName,
            templates,
        }))
    })

    loadEmailTemplates(projectId: string) {
        this.isLoading.set(true)
        this.http
            .get<LaravelApiResponse<EmailTemplate[]>>(`/api/projects/${projectId}/email-templates`)
            .pipe(
                takeUntilDestroyed(this.destroyRef),
                catchError(err => {
                    this.message.error(
                        'Error',
                        `Failed to load email templates. ${err.error?.message || err.message}`,
                    )
                    return of<LaravelApiResponse<EmailTemplate[]>>({ message: '', payload: [] })
                }),
            )
            .subscribe(response => {
                this.emailTemplates.set(response.payload || [])
                this.isLoading.set(false)
            })
    }

    showEmailDetails(emailId: number) {
        this.shownEmailId.set(emailId)
        this.drawerVisible = true
    }

    closeExpandedEmail() {
        this.shownEmailId.set(null)
        this.drawerVisible = false
    }

    getEmailTemplateById(id: number) {
        return this.emailTemplates().find(t => t.id === id)
    }

    openBrevoTemplateSelector() {
        this.brevoSelectorVisible.set(true)
    }

    onBrevoSelectorVisibleChange(visible: boolean) {
        this.brevoSelectorVisible.set(visible)
    }

    onTemplatesImported(importedTemplates: EmailTemplate[]) {
        // Add the newly imported templates to the existing list
        const currentTemplates = this.emailTemplates()
        this.emailTemplates.set([...currentTemplates, ...importedTemplates])

        this.message.success(
            'Templates Imported',
            `Successfully imported ${importedTemplates.length} template(s) from Brevo!`,
        )
    }

    getProjectId(): string {
        return this.route.parent?.snapshot.paramMap.get('projectId') || ''
    }
}
