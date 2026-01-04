import { defineStore } from 'pinia';
import { ref } from 'vue';
import api from '@/services/api';

export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  cpf?: string;
  birth_date?: string;
}

export const userState = ref(null);

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('auth_token'));
  const user = ref<User | null>(null);
  const isAuthenticated = ref(!!localStorage.getItem('auth_token'));

  function setToken(newToken: string) {
    token.value = newToken;
    isAuthenticated.value = true;
    localStorage.setItem('auth_token', newToken);
  }

  function clearAuth() {
    token.value = null;
    user.value = null;
    isAuthenticated.value = false;
    localStorage.removeItem('auth_token');
  }

  async function fetchUser() {
    try {
      const { data } = await api.get('/me');
      user.value = data;
      userState.value = data;
      return data;
    } catch (error) {
      console.error('Falha ao buscar usuário', error);
      clearAuth();
      throw error;
    }
  }

  return {
    token,
    user,
    isAuthenticated,
    setToken,
    clearAuth,
    fetchUser
  };
});
