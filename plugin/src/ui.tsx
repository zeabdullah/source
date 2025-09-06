import { Button, Container, render, Textbox, VerticalSpace } from '@create-figma-plugin/ui'
import { Fragment, h } from 'preact'
import type { UIProps } from './main'
import '!./output.css'
import { useState, useEffect } from 'preact/hooks'
import { emit } from '@create-figma-plugin/utilities'
import { LoginSuccessPayload, UserSession, Project } from './types'
import { PLUGIN_EVENT } from './constants'

function Plugin(props: UIProps) {
    const [isLoggedIn, setIsLoggedIn] = useState(false)
    const [userSession, setUserSession] = useState<UserSession | null>(null)
    const [projects, setProjects] = useState<Project[]>([])
    const [loadingProjects, setLoadingProjects] = useState(false)
    const [selectedFrames, setSelectedFrames] = useState<Array<{ id: string }>>([])

    useEffect(() => {
        const handleMessage = (event: MessageEvent) => {
            switch (event.data.pluginMessage?.type) {
                case PLUGIN_EVENT.RESTORE_SESSION: {
                    const session = event.data.pluginMessage.session as UserSession
                    setUserSession(session)
                    setIsLoggedIn(true)
                    fetchProjects(session.token)
                    break
                }
                case PLUGIN_EVENT.SELECTION_CHANGED: {
                    const newFrames = event.data.pluginMessage.selectedFrames as Array<{
                        id: string
                    }>
                    setSelectedFrames(newFrames)
                    break
                }

                default: {
                    break
                }
            }
        }

        window.onmessage = handleMessage
        return () => {
            window.onmessage = null
        }
    }, [])

    async function fetchProjects(token: string) {
        setLoadingProjects(true)
        try {
            const resp = await fetch('http://localhost:8000/api/projects', {
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json',
                },
            })
            const data = await resp.json()

            if (!resp.ok) {
                throw new Error(data.message || 'Failed to fetch projects')
            }

            setProjects(data.payload || [])
        } catch (err) {
            console.error('Failed to fetch projects:', err)
        } finally {
            setLoadingProjects(false)
        }
    }

    async function handleLoginSuccess(payload: LoginSuccessPayload) {
        setUserSession(payload)
        setIsLoggedIn(true)
        emit('login-success', payload)
        await fetchProjects(payload.token)
    }

    function handleLogout() {
        setUserSession(null)
        setIsLoggedIn(false)
        setProjects([])
        emit('logout')
    }

    return (
        <Container space="medium">
            <VerticalSpace space="medium" />
            {isLoggedIn ? (
                <ProjectList
                    user={userSession!.user}
                    token={userSession!.token}
                    projects={projects}
                    loading={loadingProjects}
                    selectedFrames={selectedFrames}
                    onLogout={handleLogout}
                />
            ) : (
                <Fragment>
                    <p class="text-lg font-bold text-center mb-4">Log in to continue</p>
                    <LoginForm onLoginSuccess={handleLoginSuccess} />
                </Fragment>
            )}
            <VerticalSpace space="medium" />
        </Container>
    )
}

function LoginForm({ onLoginSuccess }: { onLoginSuccess(data: unknown): void }) {
    const [formState, setFormState] = useState({
        email: '',
        password: '',
    })
    const [loading, setLoading] = useState(false)
    const [error, setError] = useState<string | null>(null)
    const { email, password } = formState
    const isValid = Boolean(email && password)

    function handleChange(name: 'email' | 'password', value: string) {
        setFormState(prev => ({ ...prev, [name]: value }))
    }

    async function handleSubmit(event: Event) {
        event.preventDefault()

        if (!isValid || loading) return

        setLoading(true)
        setError(null)

        const formData = new FormData()
        formData.append('email', email)
        formData.append('password', password)

        try {
            const resp = await fetch('http://localhost:8000/api/plugin/login', {
                method: 'POST',
                body: formData,
                headers: { Accept: 'application/json' },
            })
            const data = await resp.json()

            if (!resp.ok) {
                throw new Error(data.message || 'Login failed')
            }

            onLoginSuccess(data.payload)
        } catch (err) {
            console.warn(err)
            setError(err instanceof Error ? err.message : 'Network error')
        } finally {
            setLoading(false)
        }
    }

    return (
        <form onSubmit={handleSubmit}>
            <Textbox
                placeholder="Email"
                value={email}
                onValueInput={val => handleChange('email', val)}
                disabled={loading}
            />
            <VerticalSpace space="small" />
            <Textbox
                placeholder="Password"
                value={password}
                onValueInput={val => handleChange('password', val)}
                password
                disabled={loading}
            />
            <VerticalSpace space="small" />
            {error && (
                <Fragment>
                    <p class="text-red-500 text-xs text-center">{error}</p>
                    <VerticalSpace space="small" />
                </Fragment>
            )}
            <Button fullWidth disabled={!isValid || loading} loading={loading}>
                Log in
            </Button>
        </form>
    )
}

function ProjectList({
    user,
    token,
    projects,
    loading,
    selectedFrames,
    onLogout,
}: {
    user: UserSession['user']
    token: string
    projects: Project[]
    loading: boolean
    selectedFrames: Array<{ id: string }>
    onLogout: () => void
}) {
    const [selectedProject, setSelectedProject] = useState<Project | null>(null)
    const [isConnectingProject, setIsConnectingProject] = useState(false)
    const [figmaAccessToken, setFigmaAccessToken] = useState<string>('')
    const [showTokenInput, setShowTokenInput] = useState(false)
    const [pendingProject, setPendingProject] = useState<Project | null>(null)
    const [fileKey, setFileKey] = useState('')
    const [showFrameSelection, setShowFrameSelection] = useState(false)

    const handleProjectSelect = async (project: Project) => {
        // If project is already connected, show frame selection
        if (project.figma_file_key && project.figma_file_key === fileKey) {
            setSelectedProject(project)
            setShowFrameSelection(true)
            return
        }

        setIsConnectingProject(true)

        try {
            if (!fileKey) {
                console.error('No Figma file key found')
                setIsConnectingProject(false)
                return
            }

            const accessToken = ''

            if (!accessToken) {
                setPendingProject(project)
                setShowTokenInput(true)
                setIsConnectingProject(false)
                return
            }

            await connectProjectToFigma(project, fileKey, accessToken)
        } catch (err) {
            console.error('Failed to connect project:', err)
            setIsConnectingProject(false)
        }
    }

    const connectProjectToFigma = async (
        project: Project,
        fileKey: string,
        accessToken: string,
    ) => {
        const formData = new FormData()
        formData.append('figma_file_key', fileKey)
        formData.append('figma_access_token', accessToken)
        try {
            const resp = await fetch(
                `http://localhost:8000/api/projects/${project.id}/figma/connect`,
                {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json',
                    },
                    body: formData,
                },
            )

            const data = await resp.json()

            if (!resp.ok) {
                throw new Error(data.message || 'Failed to connect project')
            }

            setSelectedProject(project)
            setShowTokenInput(false)
            setShowFrameSelection(true)
            console.log('Project connected successfully:', data)
        } catch (err) {
            console.error('Failed to connect project:', err)
            throw err
        } finally {
            setIsConnectingProject(false)
        }
    }

    const handleTokenSubmit = async () => {
        if (!figmaAccessToken.trim() || !pendingProject) return

        setIsConnectingProject(true)
        try {
            if (!fileKey) {
                console.error('No Figma file key found')
                setIsConnectingProject(false)
                return
            }

            await connectProjectToFigma(pendingProject, fileKey, figmaAccessToken)
        } catch (err) {
            console.error('Failed to connect project:', err)
            setIsConnectingProject(false)
        }
    }

    if (selectedProject && showFrameSelection) {
        return (
            <FrameSelection
                project={selectedProject}
                user={user}
                selectedFrames={selectedFrames}
                onBack={() => {
                    setShowFrameSelection(false)
                    setSelectedProject(null)
                }}
                onLogout={onLogout}
                token={token}
            />
        )
    }

    if (selectedProject) {
        return (
            <Fragment>
                <UserHeader user={user} onLogout={onLogout} />

                <VerticalSpace space="small" />

                <div class="border rounded p-3 bg-green-50">
                    <div class="font-medium text-green-800">✅ Connected to Project</div>
                    <div class="text-sm text-green-700 mt-1">{selectedProject.name}</div>
                    {selectedProject.description && (
                        <div class="text-xs text-green-600 mt-1">{selectedProject.description}</div>
                    )}
                </div>

                <VerticalSpace space="small" />

                <p class="text-sm text-neutral-600 text-center">
                    Ready to select frames from this Figma file!
                </p>
            </Fragment>
        )
    }

    return (
        <Fragment>
            <UserHeader user={user} onLogout={onLogout} />

            <VerticalSpace space="small" />

            {showTokenInput && (
                <Fragment>
                    <div class="border rounded p-3 bg-yellow-100 mb-3">
                        <p class="text-sm font-medium text-yellow-800 mb-2">
                            Figma Access Token Required
                        </p>
                        <p class="text-xs text-yellow-800 mb-3">
                            Enter your Figma personal access token to connect projects.
                        </p>
                        <Textbox
                            placeholder="Figma Access Token"
                            value={figmaAccessToken}
                            onValueInput={setFigmaAccessToken}
                            password
                        />
                        <VerticalSpace space="small" />
                        <div class="flex gap-2">
                            <Button
                                onClick={handleTokenSubmit}
                                disabled={!figmaAccessToken.trim() || isConnectingProject}
                                loading={isConnectingProject}
                            >
                                Connect
                            </Button>
                            <Button
                                onClick={() => {
                                    setShowTokenInput(false)
                                    setPendingProject(null)
                                }}
                                secondary
                                disabled={isConnectingProject}
                                class="!text-black"
                            >
                                Cancel
                            </Button>
                        </div>
                    </div>
                </Fragment>
            )}

            <p class="text-sm font-medium pb-2">
                Select a project to connect with this Figma file:
            </p>

            <VerticalSpace space="medium" />
            <Textbox
                placeholder="Copy your file key here..."
                value={fileKey}
                onValueInput={v => setFileKey(v)}
                password
            />
            <VerticalSpace space="medium" />

            {loading ? (
                <p class="text-center text-neutral-600">Loading projects...</p>
            ) : projects.length === 0 ? (
                <p class="text-center text-neutral-600">No projects found</p>
            ) : (
                <div class="space-y-2">
                    {projects.map(project => (
                        <div
                            key={project.id}
                            class="border rounded p-3 hover:bg-neutral-700"
                            onClick={() => handleProjectSelect(project)}
                        >
                            <div class="font-medium">{project.name}</div>
                            {project.description && (
                                <div class="text-xs text-neutral-500 mt-1">
                                    {project.description}
                                </div>
                            )}
                            {project.figma_file_name && (
                                <div class="text-xs text-blue-400 mt-1">
                                    📎 {project.figma_file_name}
                                </div>
                            )}
                            {isConnectingProject && (
                                <div class="text-xs text-neutral-500 mt-1">Connecting...</div>
                            )}
                        </div>
                    ))}
                </div>
            )}
        </Fragment>
    )
}

function UserHeader({ user, onLogout }: { user: UserSession['user']; onLogout(): void }) {
    return (
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                {user.avatar_url && (
                    <img
                        src={user.avatar_url}
                        alt={user.name}
                        height={32}
                        width={32}
                        class="w-8 h-8 rounded-full mr-3 border border-neutral-200"
                    />
                )}
                <div>
                    <p class="text-lg leading-tight font-bold">Welcome, {user.name}!</p>
                    <p class="text-xs text-neutral-500">{user.email}</p>
                </div>
            </div>
            <Button onClick={onLogout} secondary>
                Logout
            </Button>
        </div>
    )
}

function FrameSelection({
    project,
    user,
    selectedFrames,
    onBack,
    onLogout,
    token,
}: {
    project: Project
    user: UserSession['user']
    selectedFrames: Array<{ id: string }>
    onBack(): void
    onLogout(): void
    token: string
}) {
    const [isExporting, setIsExporting] = useState(false)
    const [error, setError] = useState<string | null>(null)
    const [success, setSuccess] = useState(false)

    async function exportToProject(token: string) {
        setIsExporting(true)
        setError(null)
        setSuccess(false)

        const json = JSON.stringify({
            frame_ids: selectedFrames.map(f => f.id),
        })

        try {
            const resp = await fetch(
                `http://localhost:8000/api/projects/${project.id}/screens/export`,
                {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: json,
                },
            )
            const data = await resp.json()
            if (!resp.ok) {
                throw new Error(data.message || 'Failed to export frames')
            }
            setSuccess(true)
        } catch (err: any) {
            setError(err.message || 'Failed to export frames')
        } finally {
            setIsExporting(false)
        }
    }

    return (
        <Fragment>
            <UserHeader user={user} onLogout={onLogout} />

            <VerticalSpace space="small" />

            <div class="border rounded p-3 bg-blue-50">
                <div class="font-medium text-blue-800">📋 Frame Selection</div>
                <div class="text-sm text-blue-700 mt-1">Project: {project.name}</div>
                {project.description && (
                    <div class="text-xs text-blue-600 mt-1">{project.description}</div>
                )}
            </div>

            <VerticalSpace space="small" />

            <div class="text-center">
                <p class="text-sm font-medium mb-2">Select frames in Figma</p>
                <p class="text-xs text-neutral-600 mb-4">
                    Click on frames in your Figma file to select them. Only frames can be selected.
                </p>

                <div class="border rounded p-3 bg-neutral-50">
                    <div class="text-sm font-medium text-neutral-700">
                        Selected Frames: {selectedFrames.length}
                    </div>
                    {selectedFrames.length > 0 && (
                        <div class="text-xs text-neutral-500 mt-1">
                            {selectedFrames.length} frame{selectedFrames.length !== 1 ? 's' : ''}{' '}
                            ready for export
                        </div>
                    )}
                </div>

                <VerticalSpace space="small" />

                {error && <div class="text-xs text-red-600 mt-2">{error}</div>}
                {success && (
                    <div class="text-xs text-green-600 mt-2">Frames exported successfully!</div>
                )}
                {isExporting && <div class="text-xs text-neutral-400 mt-2">Exporting...</div>}

                <div class="flex gap-2 mt-2">
                    <Button onClick={onBack} secondary disabled={isExporting}>
                        Back to Projects
                    </Button>
                    {selectedFrames.length > 0 && (
                        <Button onClick={() => exportToProject(token)} disabled={isExporting}>
                            {isExporting
                                ? `Exporting...`
                                : `Export to Project (${selectedFrames.length})`}
                        </Button>
                    )}
                </div>
            </div>
        </Fragment>
    )
}

export default render(Plugin)
