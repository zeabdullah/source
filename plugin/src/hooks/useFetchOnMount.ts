import { useState, useEffect } from 'preact/hooks'
import { useApi } from './useApi'

type UseFetchOnMountResult<TData extends unknown = unknown> =
    | { loading: true; error: null; data: null }
    | { loading: false; error: string; data: null }
    | { loading: false; error: null; data: TData }

export function useFetchOnMount<TData extends unknown = unknown>(
    url: string,
    bearerToken?: string,
): UseFetchOnMountResult<TData> {
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState<string | null>(null)
    const [data, setData] = useState<TData | null>(null)
    const api = useApi()

    useEffect(() => {
        const controller = new AbortController()

        setLoading(true)
        setError(null)
        ;(async () => {
            let headers: RequestInit['headers'] = {}
            if (bearerToken) {
                headers['Authorization'] = `Bearer ${bearerToken}`
            }

            try {
                const data = await api.get<TData>(url, headers, controller.signal)
                setData(data)
            } catch (err) {
                if (err instanceof DOMException && err.name === 'AbortError') return
                setError(err instanceof Error ? err.message : 'Error fetching data')
            } finally {
                setLoading(false)
            }
        })()

        return () => {
            controller.abort()
        }
    }, [url, bearerToken])

    if (loading) {
        return { loading: true, error: null, data: null }
    }

    if (error) {
        return { loading: false, error: error, data: null }
    }

    return { loading: false, error: null, data: data as TData }
}
