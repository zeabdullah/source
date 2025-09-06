import { Button, Container, render, Textbox, VerticalSpace } from '@create-figma-plugin/ui'
import { Fragment, h } from 'preact'
import type { UIProps } from './main'
import '!./output.css'
import { useState, useEffect } from 'preact/hooks'
import { emit } from '@create-figma-plugin/utilities'
import { LoginSuccessPayload, UserSession, Project } from './types'

function Plugin(props: UIProps) {
    const [isLoggedIn, setIsLoggedIn] = useState(false)
    const [userSession, setUserSession] = useState<UserSession | null>(null)
    const [projects, setProjects] = useState<Project[]>([])
    const [loadingProjects, setLoadingProjects] = useState(false)

    useEffect(() => {
        const handleMessage = (event: MessageEvent) => {
            if (event.data.pluginMessage?.type === 'restore-session') {
                const session = event.data.pluginMessage.session as UserSession
                setUserSession(session)
                setIsLoggedIn(true)
                fetchProjects(session.token)
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
                    projects={projects}
                    loading={loadingProjects}
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

            // debugger
            onLoginSuccess(data.payload)
        } catch (err) {
            console.warn(err)
            setError(err instanceof Error ? err.message : 'Network error')
            // debugger
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
    projects,
    loading,
    onLogout,
}: {
    user: UserSession['user']
    projects: Project[]
    loading: boolean
    onLogout: () => void
}) {
    return (
        <Fragment>
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center">
                    {user.avatar_url && (
                        <img
                            src={user.avatar_url}
                            alt={user.name}
                            height={32}
                            width={32}
                            class="w-8 h-8 rounded-full mr-3 border border-gray-200"
                        />
                    )}
                    <div>
                        <p class="text-lg leading-tight font-bold">Welcome, {user.name}!</p>
                        <p class="text-xs text-gray-500">{user.email}</p>
                    </div>
                </div>
                <Button onClick={onLogout} secondary>
                    Logout
                </Button>
            </div>

            <VerticalSpace space="small" />

            <p class="text- font-medium pb-2">Your Projects</p>

            {loading ? (
                <p class="text-center text-gray-600">Loading projects...</p>
            ) : projects.length === 0 ? (
                <p class="text-center text-gray-600">No projects found</p>
            ) : (
                <div class="space-y-2">
                    {projects.map(project => (
                        <div key={project.id} class="border rounded p-3">
                            <div class="font-medium">{project.name}</div>
                            {project.description && (
                                <div class="text-xs text-gray-500 mt-1">{project.description}</div>
                            )}
                            {project.figma_file_name && (
                                <div class="text-xs text-blue-400 mt-1">
                                    📎 {project.figma_file_name}
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            )}
        </Fragment>
    )
}

export default render(Plugin)
