import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
      meta: { requiresGuest: true }
    },
    {
      path: '/auth/callback',
      name: 'auth-callback',
      component: () => import('../views/AuthCallbackView.vue')
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/DashboardView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/complete-register',
      name: 'complete-register',
      component: () => import('../views/CompleteRegisterView.vue'),
      meta: { requiresAuth: true }
    }
  ]
})

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('auth_token');

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' });
  }
  else if (to.meta.requiresGuest && isAuthenticated) {
    next({ name: 'dashboard' });
  }
  else {
    next();
  }
});

export default router
