import { Button } from '@create-figma-plugin/ui'
import { h } from 'preact'
import { useAuth } from '../contexts/AuthContext'

interface UserHeaderProps {}

export function UserHeader({}: UserHeaderProps) {
    const { logout, userSession } = useAuth()
    const user = userSession!.user

    return (
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                {user.avatar_url && (
                    <img
                        src={user.avatar_url}
                        alt={user.name}
                        height={32}
                        width={32}
                        class="w-8 h-8 rounded-full mr-3 border border-neutral-200"
                    />
                )}
                <div>
                    <p class="text-lg leading-tight font-bold">Welcome, {user.name}!</p>
                    <p class="text-xs text-neutral-500">{user.email}</p>
                </div>
            </div>
            <Button onClick={logout} secondary danger>
                Logout
            </Button>
        </div>
    )
}
