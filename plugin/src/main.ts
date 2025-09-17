import { on, showUI } from '@create-figma-plugin/utilities'
import { LoginSuccessPayload, UserSession } from './types'
import { PLUGIN_EVENT } from './constants'

const props = {} as const satisfies Record<string, unknown>

export type UIProps = typeof props

export default async function () {
    showUI({ height: 360, width: 300, title: 'Source' }, props)

    figma.on('selectionchange', () => {
        figma.ui.postMessage({
            type: PLUGIN_EVENT.SELECTION_CHANGED,
            selectedFrames: figma.currentPage.selection.filter(s => s.type === 'FRAME'),
        })
    })

    const existingSession: UserSession | undefined = await figma.clientStorage.getAsync(
        'user-session',
    )

    if (existingSession) {
        figma.ui.postMessage({ type: PLUGIN_EVENT.RESTORE_SESSION, session: existingSession })
    }

    on(PLUGIN_EVENT.LOGIN_SUCCESS, async (data: LoginSuccessPayload) => {
        await figma.clientStorage.setAsync('user-session', data)
    })

    on(PLUGIN_EVENT.LOGOUT, async () => {
        await figma.clientStorage.deleteAsync('user-session')
    })
}
