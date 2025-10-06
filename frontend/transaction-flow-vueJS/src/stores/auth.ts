import { defineStore } from 'pinia'
import { User } from '@/types/User'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null as User | null,
        token: null as string | null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.user,
    },

    actions: {
        login(userData: User, token: string) {
            this.user = userData
            this.token = token
        },
        logout() {
            this.user = null
            this.token = null
        },
    },
});