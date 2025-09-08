import { Container, render, VerticalSpace } from '@create-figma-plugin/ui'
import { Fragment, h } from 'preact'
import type { UIProps } from './main'
import '!./output.css'
import { useState, useEffect } from 'preact/hooks'
import { emit } from '@create-figma-plugin/utilities'
import { LoginSuccessPayload, UserSession, Project } from './types'
import { PLUGIN_EVENT } from './constants'
import { LoginForm } from './components/LoginForm'
import { ProjectList } from './components/ProjectList'

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

export default render(Plugin)
