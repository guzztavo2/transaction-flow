import { ref } from 'vue'
import { publicRoutes, privateRoutes, ErrorRoute } from './routes'
import { useAuthStore } from '@/stores/auth'

export const currentRoute = ref(window.location.pathname || "/");

window.addEventListener('popstate', () => {
    currentRoute.value = window.location.pathname || "/"
});

export function navigateTo(path: string) {
    window.history.pushState({}, '', path);
    currentRoute.value = path;
}

export function resolveRoute() {
    const auth = useAuthStore();
    const path = currentRoute.value;
    if (path in publicRoutes)
        return publicRoutes[path as keyof typeof publicRoutes] || publicRoutes['/'];

    return ErrorRoute;
}