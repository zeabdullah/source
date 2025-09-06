export interface LoginSuccessPayload {
    token: string
    user: {
        id: number
        name: string
        email: string
        avatar_url: string | null
    }
}

export interface Project {
    id: number
    owner_id: number
    name: string
    description: string | null
    figma_file_key: string | null
    figma_file_name: string | null
    figma_last_synced: string | null
}

export type UserSession = LoginSuccessPayload
