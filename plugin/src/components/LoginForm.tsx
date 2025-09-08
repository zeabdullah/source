import { Button, Textbox, VerticalSpace } from '@create-figma-plugin/ui'
import { Fragment, h } from 'preact'
import { useState } from 'preact/hooks'
import { LoginSuccessPayload } from '../types'

interface LoginFormProps {
    onLoginSuccess: (data: LoginSuccessPayload) => void
}

export function LoginForm({ onLoginSuccess }: LoginFormProps) {
    const [formState, setFormState] = useState({
        email: '',
        password: '',
    })
    const [loading, setLoading] = useState(false)
    const [error, setError] = useState<string | null>(null)
    const { email, password } = formState
    const isValid = Boolean(email && password)

    function handleChange(name: 'email' | 'password', value: string) {
        setFormState(prev => ({ ...prev, [name]: value }))
    }

    async function handleSubmit(event: Event) {
        event.preventDefault()

        if (!isValid || loading) return

        setLoading(true)
        setError(null)

        try {
            const resp = await fetch('http://localhost:8000/api/plugin/login', {
                method: 'POST',
                body: JSON.stringify({ email, password }),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
            const data = await resp.json()

            if (!resp.ok) {
                throw new Error(data.message || 'Login failed')
            }

            onLoginSuccess(data.payload)
        } catch (err) {
            console.warn(err)
            setError(err instanceof Error ? err.message : 'Network error')
        } finally {
            setLoading(false)
        }
    }

    return (
        <form onSubmit={handleSubmit}>
            <Textbox
                placeholder="Email"
                value={email}
                onValueInput={val => handleChange('email', val)}
                disabled={loading}
            />
            <VerticalSpace space="small" />
            <Textbox
                placeholder="Password"
                value={password}
                onValueInput={val => handleChange('password', val)}
                password
                disabled={loading}
            />
            <VerticalSpace space="small" />
            {error && (
                <Fragment>
                    <p class="text-red-500 text-xs text-center">{error}</p>
                    <VerticalSpace space="small" />
                </Fragment>
            )}
            <Button fullWidth disabled={!isValid || loading} loading={loading}>
                Log in
            </Button>
        </form>
    )
}
