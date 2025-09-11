import { ChangeDetectionStrategy, Component, computed, effect, signal } from '@angular/core'
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

interface Screen {
    id: number
    name: string
    section_name: string
    image: string
    device: string
    release: string
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
    releases = [
        { name: 'All', value: 'all' },
        { name: '1.0.2', value: '1.0.2' },
        { name: '1.0.1', value: '1.0.1' },
        { name: '1.0.0', value: '1.0.0' },
    ] as const satisfies SelectOption[]
    devices = [
        { name: 'iPhone 15', value: 'iphone_15' },
        { name: 'iPhone 15 Pro', value: 'iphone_15_pro' },
        { name: 'Desktop', value: 'desktop' },
    ] as const satisfies SelectOption[]
    selectedDevice = signal<(typeof this.devices)[number]['value']>('iphone_15')
    selectedRelease = signal<(typeof this.releases)[number]['value']>('all')

    shownScreenId = signal<number | null>(null)
    drawerVisible = false
    activeTab = signal<'comments' | 'ai-chat'>('comments')

    // Screen data
    screens: Screen[] = [
        // Signup & Onboarding
        {
            id: 1,
            name: 'Welcome Screen',
            section_name: 'Signup & Onboarding',
            image: 'https://placehold.co/390x844.svg',
            device: 'iphone_15',
            release: '1.0.2',
        },
        {
            id: 2,
            name: 'Create Account',
            section_name: 'Signup & Onboarding',
            image: 'https://placehold.co/390x844.svg',
            device: 'iphone_15',
            release: '1.0.2',
        },
        {
            id: 3,
            name: 'Email Verification',
            section_name: 'Signup & Onboarding',
            image: 'https://placehold.co/393x852.svg',
            device: 'iphone_15_pro',
            release: '1.0.2',
        },
        {
            id: 4,
            name: 'Profile Setup',
            section_name: 'Signup & Onboarding',
            image: 'https://placehold.co/393x852.svg',
            device: 'iphone_15_pro',
            release: '1.0.1',
        },
        {
            id: 5,
            name: 'Onboarding Tutorial',
            section_name: 'Signup & Onboarding',
            image: 'https://placehold.co/390x844.svg',
            device: 'iphone_15',
            release: '1.0.1',
        },
        {
            id: 6,
            name: 'Permissions Request',
            section_name: 'Signup & Onboarding',
            image: 'https://placehold.co/393x852.svg',
            device: 'iphone_15_pro',
            release: '1.0.0',
        },
        {
            id: 7,
            name: 'Notification Setup',
            section_name: 'Signup & Onboarding',
            image: 'https://placehold.co/390x844.svg',
            device: 'iphone_15',
            release: '1.0.0',
        },
        {
            id: 8,
            name: 'Complete Setup',
            section_name: 'Signup & Onboarding',
            image: 'https://placehold.co/393x852.svg',
            device: 'iphone_15_pro',
            release: '1.0.2',
        },

        // Forgot Password
        {
            id: 9,
            name: 'Forgot Password',
            section_name: 'Forgot Password',
            image: 'https://placehold.co/390x844.svg',
            device: 'iphone_15',
            release: '1.0.2',
        },
        {
            id: 10,
            name: 'Reset Code',
            section_name: 'Forgot Password',
            image: 'https://placehold.co/393x852.svg',
            device: 'iphone_15_pro',
            release: '1.0.1',
        },
        {
            id: 11,
            name: 'New Password',
            section_name: 'Forgot Password',
            image: 'https://placehold.co/390x844.svg',
            device: 'iphone_15',
            release: '1.0.1',
        },
        {
            id: 12,
            name: 'Password Updated',
            section_name: 'Forgot Password',
            image: 'https://placehold.co/393x852.svg',
            device: 'iphone_15_pro',
            release: '1.0.0',
        },

        // Authentication
        {
            id: 13,
            name: 'Login Screen',
            section_name: 'Authentication',
            image: 'https://placehold.co/390x844.svg',
            device: 'iphone_15',
            release: '1.0.2',
        },
        {
            id: 14,
            name: 'Biometric Login',
            section_name: 'Authentication',
            image: 'https://placehold.co/393x852.svg',
            device: 'iphone_15_pro',
            release: '1.0.1',
        },
        {
            id: 15,
            name: 'Two-Factor Auth',
            section_name: 'Authentication',
            image: 'https://placehold.co/390x844.svg',
            device: 'iphone_15',
            release: '1.0.0',
        },

        // Main App
        {
            id: 16,
            name: 'Dashboard',
            section_name: 'Main App',
            image: 'https://placehold.co/393x852.svg',
            device: 'iphone_15_pro',
            release: '1.0.2',
        },
        {
            id: 17,
            name: 'Profile',
            section_name: 'Main App',
            image: 'https://placehold.co/390x844.svg',
            device: 'iphone_15',
            release: '1.0.1',
        },
        {
            id: 18,
            name: 'Settings',
            section_name: 'Main App',
            image: 'https://placehold.co/393x852.svg',
            device: 'iphone_15_pro',
            release: '1.0.0',
        },
        {
            id: 19,
            name: 'Notifications',
            section_name: 'Main App',
            image: 'https://placehold.co/390x844.svg',
            device: 'iphone_15',
            release: '1.0.2',
        },
        {
            id: 20,
            name: 'Help & Support',
            section_name: 'Main App',
            image: 'https://placehold.co/393x852.svg',
            device: 'iphone_15_pro',
            release: '1.0.1',
        },
        {
            id: 21,
            name: 'Desktop Dashboard',
            section_name: 'Main App',
            image: 'https://placehold.co/1280x832.svg',
            device: 'desktop',
            release: '1.0.1',
        },
        {
            id: 22,
            name: 'Desktop Settings',
            section_name: 'Main App',
            image: 'https://placehold.co/1280x832.svg',
            device: 'desktop',
            release: '1.0.1',
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

    // Filter screens based on selected device and release
    filteredScreens = computed(() => {
        return this.screens.filter(screen => {
            const deviceMatch = screen.device === this.selectedDevice()
            const releaseMatch =
                this.selectedRelease() === 'all' || screen.release === this.selectedRelease()
            return deviceMatch && releaseMatch
        })
    })

    // Group filtered screens by section
    screensBySection = computed(() => {
        const grouped = this.filteredScreens().reduce(
            (acc, screen) => {
                if (!acc[screen.section_name]) {
                    acc[screen.section_name] = []
                }
                acc[screen.section_name].push(screen)
                return acc
            },
            {} as Record<string, Screen[]>,
        )

        return Object.entries(grouped).map(([sectionName, screens]) => ({
            sectionName,
            screens,
        }))
    })

    constructor() {
        effect(() => {
            console.log('showing screen:', this.shownScreenId())
        })
    }

    showScreenDetails(screenId: number) {
        this.shownScreenId.set(screenId)
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

    getScreenById(id: number) {
        return this.screens.find(screen => screen.id === id)
    }
}
