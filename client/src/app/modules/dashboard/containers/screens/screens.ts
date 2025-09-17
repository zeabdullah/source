import { ChangeDetectionStrategy, Component, computed, inject, signal } from '@angular/core'
import { HttpClient } from '@angular/common/http'
import { FormsModule } from '@angular/forms'
import { ActivatedRoute } from '@angular/router'
import { catchError, of } from 'rxjs'
import { Select } from 'primeng/select'
import { Button } from 'primeng/button'
import { Drawer } from 'primeng/drawer'
import { InputText } from 'primeng/inputtext'
import { TabsModule } from 'primeng/tabs'
import { MessageService } from 'primeng/api'
import { SelectOption } from '~/modules/dashboard/shared/interfaces/select-option.interface'
import { Screen } from '~/modules/dashboard/shared/interfaces/screen.interface'
import { ExpandedImage } from '../../components/expanded-image/expanded-image'
import { Comment } from '../../components/comment/comment'
import { AiChatPanel } from '../../components/ai-chat-panel/ai-chat-panel'
import { LaravelApiResponse } from '~/shared/interfaces/laravel-api-response.interface'

@Component({
    selector: 'app-screens',
    imports: [
        FormsModule,
        InputText,
        Select,
        Drawer,
        Button,
        TabsModule,
        ExpandedImage,
        Comment,
        AiChatPanel,
    ],
    providers: [MessageService],
    templateUrl: './screens.html',
    styles: `
        ::ng-deep .p-drawer-content {
            overflow: hidden;
            padding-inline-end: 0;
            padding-bottom: 0;
        }
    `,
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class Screens {
    http = inject(HttpClient)
    route = inject(ActivatedRoute)
    messageService = inject(MessageService)

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
    screens = signal<Screen[]>([])
    isLoading = signal<boolean>(true)

    drawerVisible = false
    activeTab: 'comments' | 'ai-chat' = 'comments'

    constructor() {
        const projectId = this.route.parent?.snapshot.paramMap.get('projectId')
        if (projectId) {
            this.loadScreens(projectId)
        }
    }

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

    newComment = signal('')

    filteredScreens = computed(() => {
        return this.screens()
    })

    // Group filtered screens by section
    screensBySection = computed(() => {
        const grouped = this.filteredScreens().reduce(
            (acc, screen) => {
                const sectionName = screen.section_name || 'Uncategorized'
                if (!acc[sectionName]) {
                    acc[sectionName] = []
                }
                acc[sectionName].push(screen)
                return acc
            },
            {} as Record<string, Screen[]>,
        )

        return Object.entries(grouped).map(([sectionName, screens]) => ({
            sectionName,
            screens,
        }))
    })

    loadScreens(projectId: string) {
        this.isLoading.set(true)
        this.http
            .get<LaravelApiResponse<Screen[]>>(`/api/projects/${projectId}/screens`)
            .pipe(
                catchError(err => {
                    this.messageService.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Failed to load screens. ' + err.message,
                        life: 10_000,
                    })
                    return of<LaravelApiResponse<Screen[]>>({ message: '', payload: [] })
                }),
            )
            .subscribe(response => {
                this.screens.set(response.payload || [])
                this.isLoading.set(false)
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

    getScreenById(id: number) {
        return this.screens().find(screen => screen.id === id)
    }
}
