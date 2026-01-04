<script setup lang="ts">
import { onMounted } from 'vue';
import api from '@/services/api';
import { userState } from './stores/auth';
import { useRouter } from 'vue-router';

const router = useRouter();

onMounted(async () => {
  const params = new URLSearchParams(window.location.search);
  const tokenFromUrl = params.get('token');
  if (tokenFromUrl) {
    localStorage.setItem('auth_token', tokenFromUrl);

    window.history.replaceState({}, document.title, window.location.pathname);
  }

  const token = localStorage.getItem('auth_token');

  if (token) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;

    try {
      const response = await api.get('/me');

      userState.value = response.data;

      if (window.location.pathname === '/' || window.location.pathname === '/login') {
          router.push('/dashboard');
      }

    } catch (error) {
      console.error('Token inválido ou expirado', error);
      localStorage.removeItem('auth_token');
      userState.value = null;
      router.push('/');
    }
  } else {
    if (window.location.pathname !== '/' && window.location.pathname !== '/login') {
        router.push('/');
    }
  }
});
</script>

<template>
  <RouterView />
</template>

<style scoped>
</style>
