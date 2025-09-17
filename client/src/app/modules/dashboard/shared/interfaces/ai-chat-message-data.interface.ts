export interface AiChatMessageData {
    id: number
    user_id: number | null
    content: string
    sender: 'user' | 'ai'
    created_at: string
    updated_at: string
}
