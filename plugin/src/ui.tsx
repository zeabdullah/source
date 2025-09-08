import { render } from '@create-figma-plugin/ui'
import { h } from 'preact'
import type { UIProps } from './main'
import { AuthContextProvider } from './contexts/AuthContext'
import App from './components/App'
import '!./output.css'

function Plugin(props: UIProps) {
    return (
        <AuthContextProvider>
            <App />
        </AuthContextProvider>
    )
}

export default render(Plugin)
