import { useState } from 'preact/hooks'

export interface UseApiOptions {
    baseUrl?: string
}

export interface ApiResponse<T = any> {
    data: T | null
    loading: boolean
    error: string | null
}

export function useApi(options: UseApiOptions = {}) {
    const { baseUrl = 'http://localhost:8000/api' } = options

    const [loading, setLoading] = useState(false)
    const [error, setError] = useState<string | null>(null)

    async function request<T = any>(endpoint: string, options: RequestInit = {}): Promise<T> {
        setLoading(true)
        setError(null)

        try {
            const url = `${baseUrl}${endpoint}`
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    ...options.headers,
                },
                ...options,
            })

            const data = await response.json()

            if (!response.ok) {
                throw new Error(data.message || `Request failed with status ${response.status}`)
            }

            return data.payload || data
        } catch (err) {
            const errorMessage = err instanceof Error ? err.message : 'Network error'
            setError(errorMessage)
            throw err instanceof Error ? err : new Error(errorMessage)
        } finally {
            setLoading(false)
        }
    }

    function get<T = any>(endpoint: string, headers?: Record<string, string>): Promise<T> {
        return request(endpoint, { method: 'GET', headers })
    }

    function post<T = any>(
        endpoint: string,
        body?: any,
        headers?: Record<string, string>,
    ): Promise<T> {
        return request(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', ...headers },
            body: JSON.stringify(body),
        })
    }

    function put<T = any>(
        endpoint: string,
        body?: any,
        headers?: Record<string, string>,
    ): Promise<T> {
        return request(endpoint, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', ...headers },
            body: JSON.stringify(body),
        })
    }

    function del<T = any>(endpoint: string, headers?: Record<string, string>): Promise<T> {
        return request(endpoint, { method: 'DELETE', headers })
    }

    return {
        request,
        get,
        post,
        put,
        delete: del,
        loading,
        error,
        setError,
    }
}
