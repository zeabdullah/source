import { ComponentChildren, createContext, h } from 'preact'
import { useContext, useMemo, useState } from 'preact/hooks'
import { LoginSuccessPayload, UserSession } from '../types'
import { emit } from '@create-figma-plugin/utilities'
import { PLUGIN_EVENT } from '../constants'

const AuthContext = createContext<{
    isLoggedIn: boolean
    userSession: UserSession | null
    setIsLoggedIn: (isLoggedIn: boolean) => void
    setUserSession: (userSession: UserSession | null) => void
    logout: () => void
    login: (payload: LoginSuccessPayload) => void
} | null>(null)

export function AuthContextProvider({ children }: { children: ComponentChildren }) {
    const [isLoggedIn, setIsLoggedIn] = useState(false)
    const [userSession, setUserSession] = useState<UserSession | null>(null)

    const authContextValue = useMemo(
        () => ({
            isLoggedIn,
            userSession,
            setIsLoggedIn,
            setUserSession,
            logout() {
                setUserSession(null)
                setIsLoggedIn(false)
                emit(PLUGIN_EVENT.LOGOUT)
            },
            login(payload: LoginSuccessPayload) {
                setUserSession(payload)
                setIsLoggedIn(true)
                emit(PLUGIN_EVENT.LOGIN_SUCCESS, payload)
            },
        }),
        [isLoggedIn, userSession],
    )

    return <AuthContext.Provider value={authContextValue}>{children}</AuthContext.Provider>
}

export function useAuth() {
    const context = useContext(AuthContext)
    if (!context) {
        throw new Error('useAuth must be used within an AuthContextProvider')
    }
    return context
}
