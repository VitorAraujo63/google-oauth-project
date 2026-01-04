import { defineStore } from 'pinia';
import api from '@/services/api';

interface User {
    id: number;
    name: string;
    email: string;
    cpf: string;
    birth_date: string;
    avatar?: string;
    created_at: string;
}

export const useUserStore = defineStore('users', {
    state: () => ({
        users: [] as User[],
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 10,
            total: 0
        },
        loading: false
    }),

    actions: {
    async fetchUsers(page = 1, search = '') {
              this.loading = true;
              try {
                  const response = await api.get(`/users?page=${page}&search=${search}`);

                  this.users = response.data.data;


                  if (response.data.meta) {
                      this.pagination = {
                          current_page: response.data.meta.current_page,
                          last_page: response.data.meta.last_page,
                          total: response.data.meta.total,
                          per_page: response.data.meta.per_page
                      };
                  } else {
                      this.pagination = response.data;
                  }

              } catch (error) {
                  console.error("Erro ao buscar usuários:", error);
              } finally {
                  this.loading = false;
              }
          }
      }
});
