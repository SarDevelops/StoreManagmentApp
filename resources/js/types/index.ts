export * from './auth';
export * from './navigation';
export * from './ui';


import { PageProps as InertiaPageProps } from '@inertiajs/core'

export interface AuthUser {
    id: number
    name: string
    email: string
}

export interface Auth {
    user: AuthUser | null

    permissions: string[]
}

export interface PageProps
    extends InertiaPageProps {

    auth: Auth
}
