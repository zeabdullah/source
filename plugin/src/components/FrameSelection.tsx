import { Button, VerticalSpace } from '@create-figma-plugin/ui'
import { Fragment, h } from 'preact'
import { useState } from 'preact/hooks'
import { Project, UserSession } from '../types'
import { UserHeader } from './UserHeader'

interface FrameSelectionProps {
    project: Project
    user: UserSession['user']
    selectedFrames: Array<{ id: string }>
    onBack: () => void
    onLogout: () => void
    token: string
}

export function FrameSelection({
    project,
    user,
    selectedFrames,
    onBack,
    onLogout,
    token,
}: FrameSelectionProps) {
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
