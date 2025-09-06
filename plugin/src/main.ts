import { on, showUI } from '@create-figma-plugin/utilities'
import { LoginSuccessPayload, UserSession } from './types'

const data = {
    greeting: 'lol',
} as const satisfies Record<string, unknown>

export type UIProps = typeof data

export default async function () {
    showUI({ height: 300, width: 280, title: 'Source' }, data)

    const existingSession = (await figma.clientStorage.getAsync(
        'user-session',
    )) as UserSession | null

    if (existingSession) {
        figma.ui.postMessage({ type: 'restore-session', session: existingSession })
    }

    on('login-success', async (data: LoginSuccessPayload) => {
        await figma.clientStorage.setAsync('user-session', data)
    })

    on('logout', async () => {
        await figma.clientStorage.deleteAsync('user-session')
    })
}
