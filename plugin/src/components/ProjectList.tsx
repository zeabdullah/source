import { Button, Textbox, VerticalSpace } from '@create-figma-plugin/ui'
import { Fragment, h } from 'preact'
import { useState } from 'preact/hooks'
import { Project, UserSession } from '../types'
import { UserHeader } from './UserHeader'
import { FrameSelection } from './FrameSelection'

interface ProjectListProps {
    user: UserSession['user']
    token: string
    projects: Project[]
    loading: boolean
    selectedFrames: Array<{ id: string }>
    onLogout(): void
}

export function ProjectList({
    user,
    token,
    projects,
    loading,
    selectedFrames,
    onLogout,
}: ProjectListProps) {
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
        try {
            const resp = await fetch(
                `http://localhost:8000/api/projects/${project.id}/figma/connect`,
                {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        figma_file_key: fileKey,
                        figma_access_token: accessToken,
                    }),
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
