import { ChangeDetectionStrategy, Component, effect, signal } from '@angular/core'
import { SelectModule } from 'primeng/select'
import { InputTextModule } from 'primeng/inputtext'
import { ButtonModule } from 'primeng/button'
import { AvatarModule } from 'primeng/avatar'
import { CardModule } from 'primeng/card'
import { FormsModule } from '@angular/forms'
import { DrawerModule } from 'primeng/drawer'
import { ScreenDetails } from '../../components/screen-details/screen-details'

interface SelectOption {
    name: string
    value: string
}

@Component({
    selector: 'app-screens',
    imports: [
        InputTextModule,
        SelectModule,
        FormsModule,
        DrawerModule,
        ScreenDetails,
        ButtonModule,
        AvatarModule,
        CardModule,
    ],
    templateUrl: './screens.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    host: {
        class: 'block',
    },
})
export class Screens {
    languages = [
        { name: 'English', value: 'en' },
        { name: 'Spanish', value: 'es' },
    ] as const satisfies SelectOption[]
    releases = [
        { name: '1.0.2', value: '1.0.2' },
        { name: '1.0.1', value: '1.0.1' },
        { name: '1.0.0', value: '1.0.0' },
    ] as const satisfies SelectOption[]
    devices = [
        { name: 'iPhone 15', value: 'iphone_15' },
        { name: 'iPhone 15 Pro', value: 'iphone_15_pro' },
    ] as const satisfies SelectOption[]
    selectedDevice: (typeof this.devices)[number]['value'] = 'iphone_15'
    selectedRelease: (typeof this.releases)[number]['value'] = '1.0.2'
    selectedLanguage: (typeof this.languages)[number]['value'] = 'en'

    shownScreenId = signal<number | null>(null)
    drawerVisible = false
    activeTab = signal<'comments' | 'ai-chat'>('comments')

    // Mock comments data
    comments = [
        {
            id: 1,
            user: {
                name: 'John Doe',
                avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-15T10:30:00Z',
            content: 'This screen looks great! The layout is clean and intuitive.',
        },
        {
            id: 2,
            user: {
                name: 'Sarah Wilson',
                avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=64&h=64&fit=crop&crop=face',
            },
            date: '2024-01-15T14:22:00Z',
            content: 'I think we should consider adding a loading state for this screen.',
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
    ]

    // Mock AI chat data
    aiChatMessages = signal([
        {
            id: 1,
            type: 'bot' as const,
            content:
                'Hello! I can help you analyze this screen design. What would you like to know?',
            timestamp: '2024-01-16T10:00:00Z',
        },
        {
            id: 2,
            type: 'user' as const,
            content: 'What are the accessibility considerations for this screen?',
            timestamp: '2024-01-16T10:01:00Z',
        },
        {
            id: 3,
            type: 'bot' as const,
            content:
                'Great question! For this screen, you should consider: 1) Color contrast ratios, 2) Touch target sizes (minimum 44px), 3) Screen reader compatibility, and 4) Keyboard navigation support.',
            timestamp: '2024-01-16T10:01:30Z',
        },
    ])

    newMessage = signal('')

    constructor() {
        effect(() => {
            console.log('showing screen:', this.shownScreenId())
        })
    }

    showScreenDetails(index: number) {
        this.shownScreenId.set(index)
        this.drawerVisible = true
    }

    closeExpandedScreen() {
        this.shownScreenId.set(null)
        this.drawerVisible = false
    }

    setActiveTab(tab: 'comments' | 'ai-chat') {
        this.activeTab.set(tab)
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

            // Simulate bot response
            setTimeout(() => {
                const botResponse = {
                    id: this.aiChatMessages().length + 1,
                    type: 'bot' as const,
                    content:
                        "Thanks for your message! I'm here to help with any questions about this screen design.",
                    timestamp: new Date().toISOString(),
                }
                this.aiChatMessages.update(messages => [...messages, botResponse])
            }, 1000)
        }
    }

    formatDate(dateString: string): string {
        const date = new Date(dateString)
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        })
    }
}
