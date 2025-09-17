import { ChangeDetectionStrategy, Component, computed, signal, inject } from '@angular/core'
import { FormsModule } from '@angular/forms'
import { HttpClient } from '@angular/common/http'
import { ActivatedRoute } from '@angular/router'
import { catchError, of } from 'rxjs'
import { Toast } from 'primeng/toast'
import { Button } from 'primeng/button'
import { Drawer } from 'primeng/drawer'
import { Select } from 'primeng/select'
import { InputText } from 'primeng/inputtext'
import { TabsModule } from 'primeng/tabs'
import { MessageService } from 'primeng/api'
import { ProgressSpinner } from 'primeng/progressspinner'
import { SelectOption } from '~/modules/dashboard/shared/interfaces/select-option.interface'
import { EmailTemplate } from '../../shared/interfaces/email.interface'
import { ExpandedImage } from '../../components/expanded-image/expanded-image'
import { Comment } from '../../components/comment/comment'
import { AiChatPanel } from '../../components/ai-chat-panel/ai-chat-panel'
import { EmptyState } from '~/shared/components/empty-state/empty-state'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'

@Component({
    selector: 'app-email-templates',
    imports: [
        FormsModule,
        InputText,
        Button,
        Drawer,
        Select,
        TabsModule,
        Comment,
        Toast,
        ProgressSpinner,
        ExpandedImage,
        EmptyState,
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

    constructor() {
        const projectId = this.route.parent?.snapshot.paramMap.get('projectId')
        if (projectId) {
            this.loadEmailTemplates(projectId)
        }
    }

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

    comments = [
        {
            id: 1,
            user: {
                name: 'John Doe',
                avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-15T10:30:00Z',
            content: 'This email looks great! The layout is clean and intuitive.',
        },
        {
            id: 2,
            user: {
                name: 'Sarah Wilson',
                avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-15T14:22:00Z',
            content: 'I think we should consider adding a loading state for this email.',
        },
        {
            id: 3,
            user: {
                name: 'Mike Chen',
                avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-16T09:15:00Z',
            content: 'The color scheme works well with our brand guidelines.',
        },
        {
            id: 4,
            user: {
                name: 'Mike Chen',
                avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-16T09:15:00Z',
            content: 'The color scheme works well with our brand guidelines.',
        },
        {
            id: 5,
            user: {
                name: 'Mike Chen',
                avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-16T09:15:00Z',
            content: 'The color scheme works well with our brand guidelines.',
        },
        {
            id: 6,
            user: {
                name: 'Mike Chen',
                avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-16T09:15:00Z',
            content: 'The color scheme works well with our brand guidelines.',
        },
        {
            id: 7,
            user: {
                name: 'Mike Chen',
                avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-16T09:15:00Z',
            content: 'The color scheme works well with our brand guidelines.',
        },
        {
            id: 8,
            user: {
                name: 'Mike Chen',
                avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-16T09:15:00Z',
            content: 'The color scheme works well with our brand guidelines.',
        },
        {
            id: 9,
            user: {
                name: 'Mike Chen',
                avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-16T09:15:00Z',
            content: 'The color scheme works well with our brand guidelines.',
        },
    ]

    newComment = signal('')

    filteredEmailTemplates = computed(() => {
        // Since the backend doesn't have release filtering yet, we'll return all templates
        // This can be updated when release filtering is implemented
        return this.emailTemplates()
    })

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

    showEmailDetails(emailId: number) {
        this.shownEmailId.set(emailId)
        this.drawerVisible = true
    }

    closeExpandedEmail() {
        this.shownEmailId.set(null)
        this.drawerVisible = false
    }

    sendComment() {
        if (this.newComment().trim()) {
            const newComment = {
                id: this.comments.length + 1,
                user: {
                    name: 'Current User',
                    avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=64&h=64&fit=crop&crop=face',
                },
                date: new Date().toISOString(),
                content: this.newComment(),
            }
            this.comments.push(newComment)
            this.newComment.set('')
        }
    }

    getEmailTemplateById(id: number) {
        return this.emailTemplates().find(t => t.id === id)
    }

    connectToMailChimp() {
        // TODO: Implement MailChimp connection logic
        console.log('Connect to MailChimp clicked')
    }
}
