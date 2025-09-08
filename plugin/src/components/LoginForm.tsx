import { Button, Textbox, VerticalSpace } from '@create-figma-plugin/ui'
import { Fragment, h } from 'preact'
import { useState } from 'preact/hooks'
import { useApi } from '../hooks/useApi'
import { useAuth } from '../contexts/AuthContext'

interface LoginFormProps {}

export function LoginForm({}: LoginFormProps) {
    const [formState, setFormState] = useState({
        email: '',
        password: '',
    })
    const api = useApi()
    const { login } = useAuth()

    const { email, password } = formState
    const isFormValid = Boolean(email && password)

    function handleChange(name: 'email' | 'password', value: string) {
        setFormState(prev => ({ ...prev, [name]: value }))
    }

    async function handleSubmit(event: Event) {
        event.preventDefault()
        if (!isFormValid || api.loading) return

        try {
            const payload = await api.post('/plugin/login', { email, password })
            login(payload)
        } catch {
            console.warn('Failed to login:', api.error)
        }
    }

    return (
        <form onSubmit={handleSubmit}>
            <Textbox
                placeholder="Email"
                value={email}
                onValueInput={val => handleChange('email', val)}
                disabled={api.loading}
            />
            <VerticalSpace space="small" />
            <Textbox
                placeholder="Password"
                value={password}
                onValueInput={val => handleChange('password', val)}
                password
                disabled={api.loading}
            />
            <VerticalSpace space="small" />
            {api.error && (
                <Fragment>
                    <p class="text-red-500 text-xs text-center">{api.error}</p>
                    <VerticalSpace space="small" />
                </Fragment>
            )}
            <Button fullWidth disabled={!isFormValid || api.loading} loading={api.loading}>
                Log in
            </Button>
        </form>
    )
}
