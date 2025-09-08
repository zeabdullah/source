import { Button, Stack, VerticalSpace } from '@create-figma-plugin/ui'
import { Fragment, h } from 'preact'
import { useState } from 'preact/hooks'
import { Project } from '../types'
import { useAuth } from '../contexts/AuthContext'
import { useApi } from '../hooks/useApi'

interface FrameSelectionProps {
    project: Project
    selectedFrames: Array<{ id: string }>
    onBack: () => void
}

export function FrameSelection({ project, selectedFrames, onBack }: FrameSelectionProps) {
    const [success, setSuccess] = useState(false)

    const { userSession } = useAuth()
    const api = useApi()

    async function exportToProject(token: string) {
        setSuccess(false)

        try {
            await api.post(
                `/projects/${project.id}/screens/export`,
                {
                    frame_ids: selectedFrames.map(f => f.id),
                    figma_access_token: '',
                },
                { Authorization: `Bearer ${token}` },
            )
            setSuccess(true)
        } catch (err: any) {
            console.error('Failed to export frames:', err)
        }
    }

    return (
        <Fragment>
            <div class="text-lg font-medium">{project.name}</div>
            <VerticalSpace space="small" />
            <Stack space="small">
                <p class="text-sm">Select frames in Figma</p>
                <p class="text-xs text-neutral-400">
                    Click on frames in your Figma file to select them. Only frames can be selected.
                </p>

                <div class="border rounded p-3 bg-neutral-100">
                    <div class="text-sm font-medium text-neutral-700">Selected Frames:</div>
                    {selectedFrames.length > 0 && (
                        <div class="text-xs text-neutral-500 mt-1">
                            <span class="font-semibold">
                                {selectedFrames.length} frame
                                {selectedFrames.length !== 1 ? 's' : ''}{' '}
                            </span>
                            ready for export
                        </div>
                    )}
                </div>

                <div class="text-xs">
                    {api.error && <div class="text-red-600">{api.error}</div>}
                    {success && <div class="text-green-600">Frames exported successfully!</div>}
                    {api.loading && <div class="text-neutral-400">Exporting...</div>}
                </div>

                <div class="flex gap-2">
                    <Button onClick={onBack} secondary disabled={api.loading}>
                        Back to Projects
                    </Button>
                    {selectedFrames.length > 0 && (
                        <Button
                            onClick={() => exportToProject(userSession!.token)}
                            disabled={api.loading}
                        >
                            {api.loading
                                ? `Exporting...`
                                : `Export to Project (${selectedFrames.length})`}
                        </Button>
                    )}
                </div>
            </Stack>
        </Fragment>
    )
}
