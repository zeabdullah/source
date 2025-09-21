export interface ScreenData {
    id: number
    project_id: number
    section_name: string | null
    figma_node_name: string | null
    figma_svg_url: string | null
    figma_node_id: string
    figma_file_key: string
    data: unknown
    description: string | null
    created_at: string
    updated_at: string
}
