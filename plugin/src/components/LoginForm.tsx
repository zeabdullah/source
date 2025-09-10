import {
    Banner,
    Button,
    IconWarning16,
    Stack,
    Textbox,
    useInitialFocus,
} from '@create-figma-plugin/ui'
import { h } from 'preact'
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
            <Stack space="small">
                <Textbox
                    {...useInitialFocus()}
                    placeholder="Email"
                    value={email}
                    onValueInput={val => handleChange('email', val)}
                    disabled={api.loading}
                />
                <Textbox
                    placeholder="Password"
                    value={password}
                    onValueInput={val => handleChange('password', val)}
                    password
                    disabled={api.loading}
                />

                {api.error && (
                    <Banner icon={<IconWarning16 />} variant="warning">
                        {api.error}
                    </Banner>
                )}

                <Button fullWidth disabled={!isFormValid || api.loading} loading={api.loading}>
                    Log in
                </Button>
            </Stack>
        </form>
    )
}
