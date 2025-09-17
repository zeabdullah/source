export interface CommentData {
    id: number
    content: string
    user: {
        id: number
        name: string
        email: string
        avatar_url: string | null
    }
    created_at: string
    updated_at: string
}
