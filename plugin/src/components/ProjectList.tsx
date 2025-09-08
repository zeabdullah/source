import {
    Button,
    IconPage16,
    LoadingIndicator,
    Stack,
    Text,
    Textbox,
    VerticalSpace,
} from '@create-figma-plugin/ui'
import { Fragment, h } from 'preact'
import { useState } from 'preact/hooks'
import { Project } from '../types'
import { FrameSelection } from './FrameSelection'
import { useApi } from '../hooks/useApi'
import { useAuth } from '../contexts/AuthContext'
import { useFetchOnMount } from '../hooks/useFetchOnMount'

interface ProjectListProps {
    selectedFrames: Array<{ id: string }>
}

export function ProjectList({ selectedFrames }: ProjectListProps) {
    const [selectedProject, setSelectedProject] = useState<Project | null>(null)
    const [isConnectingProject, setIsConnectingProject] = useState(false)
    const [showTokenInput, setShowTokenInput] = useState(false)
    const [pendingProject, setPendingProject] = useState<Project | null>(null)
    const [figmaAccessToken, setFigmaAccessToken] = useState<string>('')
    const [fileKey, setFileKey] = useState('')
    const [showFrameSelection, setShowFrameSelection] = useState(false)
    const [fileKeyError, setFileKeyError] = useState<string | null>(null)

    const { userSession } = useAuth()
    const api = useApi()

    const {
        data: projects,
        loading: isProjectsLoading,
        error: projectsError,
    } = useFetchOnMount<Project[]>('/projects', userSession!.token)

    async function handleProjectSelect(project: Project) {
        // If project is already connected, show frame selection
        if (project.figma_file_key) {
            setSelectedProject(project)
            setShowFrameSelection(true)
            return
        }

        setIsConnectingProject(true)

        try {
            if (!fileKey) {
                setFileKeyError('Please enter a Figma file key.')
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

    async function connectProjectToFigma(project: Project, fileKey: string, accessToken: string) {
        try {
            await api.post(
                `/projects/${project.id}/figma/connect`,
                {
                    figma_file_key: fileKey,
                    figma_access_token: accessToken,
                },
                { Authorization: `Bearer ${userSession!.token}` },
            )

            setSelectedProject(project)
            setShowTokenInput(false)
            setShowFrameSelection(true)
        } catch (err) {
            console.error('Failed to connect project:', err)
            throw err
        } finally {
            setIsConnectingProject(false)
        }
    }

    async function handleTokenSubmit() {
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
                selectedFrames={selectedFrames}
                onBack={() => {
                    setShowFrameSelection(false)
                    setSelectedProject(null)
                }}
            />
        )
    }

    if (selectedProject) {
        return (
            <Fragment>
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
                icon={<IconPage16 />}
                placeholder="Enter your file key..."
                value={fileKey}
                onValueInput={setFileKey}
            />
            <VerticalSpace space="medium" />
            {fileKeyError && <div class="text-red-600 text-sm">{fileKeyError}</div>}

            {isProjectsLoading ? (
                <p class="text-center text-neutral-600">Loading projects...</p>
            ) : projectsError !== null ? (
                <p class="text-center text-red-600">Error loading projects: {projectsError}</p>
            ) : projects.length === 0 ? (
                <p class="text-center text-neutral-600">No projects found</p>
            ) : (
                <div class="space-y-2">
                    {projects.map(project => (
                        <div class="p-3 rounded-md hover:bg-neutral-700 text-xs">
                            <Stack
                                key={project.id}
                                space="extraSmall"
                                onClick={() => handleProjectSelect(project)}
                            >
                                <p class="text-sm font-medium">{project.name}</p>
                                {project.description && (
                                    <p class="text-neutral-400">{project.description}</p>
                                )}
                                {project.figma_file_name && (
                                    <div class="text-blue-400">📎 {project.figma_file_name}</div>
                                )}
                                {isConnectingProject && <LoadingIndicator class="w-fit" />}
                            </Stack>
                        </div>
                    ))}
                </div>
            )}
        </Fragment>
    )
}
