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
    const [abortController, setAbortController] = useState<AbortController | null>(null)

    async function request<T = any>(
        endpoint: string,
        { headers, ...options }: RequestInit = {},
    ): Promise<T> {
        setLoading(true)
        setError(null)

        // Cancel any previous request
        if (abortController) {
            abortController.abort()
        }
        const controller = new AbortController()
        setAbortController(controller)

        try {
            const url = `${baseUrl}${endpoint}`
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...headers,
                },
                ...options,
                signal: options.signal ?? controller.signal,
            })

            const data = await response.json()

            if (!response.ok) {
                throw new Error(data.message || `Request failed with status ${response.status}`)
            }

            return data.payload || data
        } catch (err) {
            if (err instanceof DOMException && err.name === 'AbortError') {
                setError('Request cancelled')
                throw err
            }
            const errorMessage = err instanceof Error ? err.message : 'Network error'
            setError(errorMessage)
            throw err instanceof Error ? err : new Error(errorMessage)
        } finally {
            setLoading(false)
            setAbortController(null)
        }
    }

    function get<T = any>(
        endpoint: string,
        headers?: RequestInit['headers'],
        signal?: AbortSignal,
    ): Promise<T> {
        return request(endpoint, { method: 'GET', headers, signal })
    }

    function post<T = any>(
        endpoint: string,
        body?: any,
        headers?: RequestInit['headers'],
        signal?: AbortSignal,
    ): Promise<T> {
        return request(endpoint, {
            method: 'POST',
            headers,
            body: JSON.stringify(body),
            signal,
        })
    }

    function put<T = any>(
        endpoint: string,
        body?: any,
        headers?: RequestInit['headers'],
        signal?: AbortSignal,
    ): Promise<T> {
        return request(endpoint, {
            method: 'PUT',
            headers,
            body: JSON.stringify(body),
            signal,
        })
    }

    function del<T = any>(
        endpoint: string,
        headers?: RequestInit['headers'],
        signal?: AbortSignal,
    ): Promise<T> {
        return request(endpoint, { method: 'DELETE', headers, signal })
    }

    function cancelRequest() {
        if (abortController) {
            abortController.abort()
            setAbortController(null)
        }
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
        cancelRequest,
    }
}
