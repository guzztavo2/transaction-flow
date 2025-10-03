
export const publicRoutes = {
    "/": { component: () => import('@/pages/homeView.vue') },
}

export const privateRoutes = {
    // "/dashboard": { component: () => import('@/views/DashboardView.vue') },
}

export const ErrorRoute = { component: () => import('@/pages/errorView.vue') }; 