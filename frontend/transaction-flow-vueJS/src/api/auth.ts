import { api } from './http'
import { User } from '@/types/User'

export async function login(email: string, password: string, remember: boolean = false): Promise<User> {
    try {
        const response = await api.post('/login', { email, password, remember })
        const userData = response.data.user
        return new User(userData.name, userData.email);
    } catch (error: unknown) {
        if (typeof error === 'object' && error !== null && 'response' in error) {
            const err = error as { response?: { data?: { message?: string } } };
            throw new Error(err.response?.data?.message || 'Login failed');
        }
        throw new Error('Login failed');
    }
}