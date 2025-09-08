import { Button } from '@create-figma-plugin/ui'
import { h } from 'preact'
import { UserSession } from '../types'

interface UserHeaderProps {
    user: UserSession['user']
    onLogout: () => void
}

export function UserHeader({ user, onLogout }: UserHeaderProps) {
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
            <Button onClick={onLogout} secondary>
                Logout
            </Button>
        </div>
    )
}
