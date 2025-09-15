import { Stack } from '@create-figma-plugin/ui'
import { h } from 'preact'
import { useState } from 'preact/hooks'
import { Project } from '../types'
import { FrameSelection } from './FrameSelection'
import { useAuth } from '../contexts/AuthContext'
import { useFetchOnMount } from '../hooks/useFetchOnMount'

interface ProjectListProps {
    selectedFrames: Array<{ id: string }>
}

export function ProjectList({ selectedFrames }: ProjectListProps) {
    const [selectedProject, setSelectedProject] = useState<Project | null>(null)
    const { userSession } = useAuth()

    const {
        data: projects,
        loading: isProjectsLoading,
        error: projectsError,
    } = useFetchOnMount<Project[]>('/projects', userSession!.token)

    async function handleProjectSelect(project: Project) {
        setSelectedProject(project)
    }

    return selectedProject ? (
        <FrameSelection
            project={selectedProject}
            selectedFrames={selectedFrames}
            onBack={() => {
                setSelectedProject(null)
            }}
        />
    ) : (
        <Stack space="medium">
            <p class="text-sm font-medium pb-2">
                Select a project to connect with this Figma file:
            </p>

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
                                    <p class="text-neutral-400 line-clamp-2">
                                        {project.description}
                                    </p>
                                )}
                            </Stack>
                        </div>
                    ))}
                </div>
            )}
        </Stack>
    )
}
