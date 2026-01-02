<script setup lang="ts">
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

onMounted(async () => {
  const token = route.query.token as string;

  if (!token) {
    router.push({ name: 'login', query: { error: 'no_token' } });
    return;
  }

  try {
    authStore.setToken(token);

    await authStore.fetchUser();

    const user = authStore.user;

    if (!user?.cpf || !user?.birth_date) {
        router.push({ name: 'complete-register' });
    } else {
        router.push({ name: 'dashboard' });
    }

  } catch (error) {
    console.error('Erro no callback de autenticação:', error);
    router.push({ name: 'login', query: { error: 'invalid_token' } });
  }
});
</script>

<template>
  <div class="callback-loading">
    <div class="spinner"></div>
    <p>Autenticando...</p>
  </div>
</template>

<style lang="scss" scoped>
.callback-loading {
  height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: #f4f6f8;
  color: #666;

  .spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #ddd;
    border-top-color: #4285F4;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }
}
</style>
