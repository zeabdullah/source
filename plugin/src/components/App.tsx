import { Container, VerticalSpace } from '@create-figma-plugin/ui'
import { Fragment, h } from 'preact'
import { useState, useEffect } from 'preact/hooks'
import { PLUGIN_EVENT } from '../constants'
import { useAuth } from '../contexts/AuthContext'
import { useApi } from '../hooks/useApi'
import { UserSession } from '../types'
import { LoginForm } from './LoginForm'
import { ProjectList } from './ProjectList'
import { UserHeader } from './UserHeader'

export default function App() {
    const [selectedFrames, setSelectedFrames] = useState<Array<{ id: string }>>([])
    const { isLoggedIn, setUserSession, setIsLoggedIn } = useAuth()
    const api = useApi()

    useEffect(() => {
        async function handleMessage(event: MessageEvent) {
            switch (event.data.pluginMessage?.type) {
                case PLUGIN_EVENT.RESTORE_SESSION: {
                    const session = event.data.pluginMessage.session as UserSession
                    setUserSession(session)
                    setIsLoggedIn(true)
                    break
                }
                case PLUGIN_EVENT.SELECTION_CHANGED: {
                    const newFrames = event.data.pluginMessage.selectedFrames as Array<{
                        id: string
                    }>
                    setSelectedFrames(newFrames)
                    break
                }
            }
        }

        window.addEventListener('message', handleMessage)
        return () => {
            window.removeEventListener('message', handleMessage)
        }
    }, [])

    return (
        <Container space="medium">
            <VerticalSpace space="medium" />
            {isLoggedIn ? (
                <Fragment>
                    <UserHeader />
                    <VerticalSpace space="small" />

                    <ProjectList selectedFrames={selectedFrames} />
                </Fragment>
            ) : (
                <Fragment>
                    <p class="text-lg font-bold text-center mb-4">Log in to continue</p>
                    <LoginForm />
                </Fragment>
            )}
            <VerticalSpace space="medium" />
        </Container>
    )
}
