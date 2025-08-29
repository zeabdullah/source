export interface LaravelApiResponse<TPayload = unknown> {
    message: string
    payload: TPayload | null
}
