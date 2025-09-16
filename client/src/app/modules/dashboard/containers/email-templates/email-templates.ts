import { ChangeDetectionStrategy, Component, computed, signal } from '@angular/core'
import { FormsModule } from '@angular/forms'
import { SelectOption } from '~/modules/dashboard/shared/interfaces/select-option.interface'
import { Email } from '../../shared/interfaces/email.interface'
import { Button } from 'primeng/button'
import { Drawer } from 'primeng/drawer'
import { InputTextModule } from 'primeng/inputtext'
import { SelectModule } from 'primeng/select'
import { ExpandedImage } from '../../components/expanded-image/expanded-image'
import { TabsModule } from 'primeng/tabs'
import { Comment } from '../../components/comment/comment'
import { AiChatMessage } from '../../components/ai-chat-message/ai-chat-message'

@Component({
    selector: 'app-email-templates',
    imports: [
        FormsModule,
        InputTextModule,
        SelectModule,
        Drawer,
        Button,
        ExpandedImage,
        TabsModule,
        Comment,
        AiChatMessage,
    ],
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
    releases = [
        { name: 'All', value: 'all' },
        { name: '1.0.2', value: '1.0.2' },
        { name: '1.0.1', value: '1.0.1' },
        { name: '1.0.0', value: '1.0.0' },
    ] as const satisfies SelectOption[]
    selectedRelease = signal<(typeof this.releases)[number]['value']>('all')

    shownEmailId = signal<number | null>(null)
    drawerVisible = false
    activeTab: 'comments' | 'ai-chat' = 'comments'

    emails: Email[] = [
        {
            id: 1,
            name: 'Welcome Email',
            section_name: 'Signup',
            image: 'https://placehold.co/300x400.svg',
            release: '1.0.2',
        },
        {
            id: 2,
            name: 'Password Reset',
            section_name: 'Authentication',
            image: 'https://placehold.co/300x400.svg',
            release: '1.0.2',
        },
        {
            id: 3,
            name: 'New Feature Announcement',
            section_name: 'General',
            image: 'https://placehold.co/300x400.svg',
            release: '1.0.1',
        },
        {
            id: 4,
            name: 'Account Verification',
            section_name: 'Signup',
            image: 'https://placehold.co/300x400.svg',
            release: '1.0.0',
        },
    ]
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

    aiChatMessages = signal([
        {
            id: 1,
            type: 'bot' as const,
            content:
                'Hello! I can help you analyze this email design. What would you like to know?',
            timestamp: '2024-01-16T10:00:00Z',
        },
        {
            id: 2,
            type: 'user' as const,
            content: 'What are the accessibility considerations for this email?',
            timestamp: '2024-01-16T10:01:00Z',
        },
    ])
    newMessage = signal('')
    newComment = signal('')

    filteredEmails = computed(() =>
        this.emails.filter(
            email => this.selectedRelease() === 'all' || email.release === this.selectedRelease(),
        ),
    )

    emailsBySection = computed(() => {
        const grouped = this.filteredEmails().reduce(
            (acc, email) => {
                if (!acc[email.section_name]) {
                    acc[email.section_name] = []
                }
                acc[email.section_name].push(email)
                return acc
            },
            {} as Record<string, Email[]>,
        )

        return Object.entries(grouped).map(([sectionName, emails]) => ({
            sectionName,
            emails,
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

    sendMessage() {
        if (this.newMessage().trim()) {
            const newMsg = {
                id: this.aiChatMessages().length + 1,
                type: 'user' as const,
                content: this.newMessage(),
                timestamp: new Date().toISOString(),
            }
            this.aiChatMessages.update(messages => [...messages, newMsg])
            this.newMessage.set('')

            setTimeout(() => {
                const botResponse = {
                    id: this.aiChatMessages().length + 1,
                    type: 'bot' as const,
                    content:
                        "Thanks for your message! I'm here to help with any questions about this email design.",
                    timestamp: new Date().toISOString(),
                }
                this.aiChatMessages.update(messages => [...messages, botResponse])
            }, 1000)
        }
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

    getEmailById(id: number) {
        return this.emails.find(e => e.id === id)
    }

    connectToMailChimp() {
        // TODO: Implement MailChimp connection logic
        console.log('Connect to MailChimp clicked')
    }
}
