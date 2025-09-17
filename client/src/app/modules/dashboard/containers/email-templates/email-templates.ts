import { ChangeDetectionStrategy, Component, computed, signal, inject } from '@angular/core'
import { FormsModule } from '@angular/forms'
import { HttpClient } from '@angular/common/http'
import { ActivatedRoute } from '@angular/router'
import { catchError, of } from 'rxjs'
import { Toast } from 'primeng/toast'
import { Button } from 'primeng/button'
import { Drawer } from 'primeng/drawer'
import { Select } from 'primeng/select'
import { TabsModule } from 'primeng/tabs'
import { MessageService } from 'primeng/api'
import { ProgressSpinner } from 'primeng/progressspinner'
import { SelectOption } from '~/modules/dashboard/shared/interfaces/select-option.interface'
import { EmailTemplate } from '../../shared/interfaces/email.interface'
import { ExpandedImage } from '../../components/expanded-image/expanded-image'
import { CommentsPanel } from '../../components/comments-panel/comments-panel'
import { AiChatPanel } from '../../components/ai-chat-panel/ai-chat-panel'
import { EmptyState } from '~/shared/components/empty-state/empty-state'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'

@Component({
    selector: 'app-email-templates',
    imports: [
        FormsModule,
        Button,
        Drawer,
        Select,
        TabsModule,
        Toast,
        ProgressSpinner,
        ExpandedImage,
        EmptyState,
        CommentsPanel,
        AiChatPanel,
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
    messageService = inject(MessageService)

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
                catchError(err => {
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to load email templates. ' + err.message,
                        life: 4000,
                    })
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

    connectToMailChimp() {
        // TODO: Implement MailChimp connection logic
        console.log('Connect to MailChimp clicked')
    }
}
