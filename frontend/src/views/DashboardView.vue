<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import api from '@/services/api';
import { useToast } from "vue-toastification";

// Tipagem do Usuário
interface User {
  id: number;
  name: string;
  email: string;
  cpf: string;
  birth_date: string;
  avatar?: string;
}

const authStore = useAuthStore();
const router = useRouter();
const toast = useToast();

// Estados
const users = ref<User[]>([]);
const search = ref('');
const isLoading = ref(false);

// --- PAGINAÇÃO ---
const currentPage = ref(1);
const lastPage = ref(1);
const totalUsers = ref(0); // Para mostrar "Total de X usuários"

const fetchUsers = async () => {
  try {
    isLoading.value = true;
    
    const { data } = await api.get('http://localhost:80/api/users', {
      params: { 
        search: search.value,
        page: currentPage.value // Envia a página atual para o Laravel
      }
    });

    users.value = data.data; 
    lastPage.value = data.last_page;
    currentPage.value = data.current_page;
    totalUsers.value = data.total;
    
  } catch (error) {
    console.error('Erro ao buscar usuários', error);
    toast.error("Erro ao carregar lista.");
  } finally {
    isLoading.value = false;
  }
};

// Funções de Navegação
const changePage = (page: number) => {
    if (page >= 1 && page <= lastPage.value) {
        currentPage.value = page;
        fetchUsers();
    }
};

// Observa a busca (resetando para página 1)
let timeout: number;
watch(search, () => {
  clearTimeout(timeout);
  timeout = setTimeout(() => {
    currentPage.value = 1; 
    fetchUsers();
  }, 500);
});

const handleLogout = () => {
  authStore.clearAuth();
  router.push({ name: 'login' });
};

onMounted(() => {
  fetchUsers();
});
</script>

<template>
  <div class="dashboard-container">
    <header class="top-bar">
      <h1>Painel de Usuários</h1>
      <div class="user-profile">
        <div class="info">
            <span class="name">Olá, {{ authStore.user?.name }}</span>
            <button @click="handleLogout" class="logout-link">Sair</button>
        </div>
      </div>
    </header>

    <main class="content">
      
      <div class="filter-section">
        <div class="search-box">
            <i class="icon">🔍</i>
            <input 
                v-model="search" 
                type="text" 
                placeholder="Buscar por Nome ou CPF..."
            >
        </div>
      </div>

      <div class="table-card">
        <div v-if="isLoading" class="skeleton-wrapper">
          <div class="skeleton-header"></div>
          <div v-for="i in 5" :key="i" class="skeleton-row">
             <div class="skeleton-cell w-small"></div>
             <div class="skeleton-cell w-large"></div>
             <div class="skeleton-cell w-medium"></div>
             <div class="skeleton-cell w-medium"></div>
          </div>
        </div>

        <div v-else>
            <table v-if="users.length > 0">
            <thead>
                <tr>
                <th>ID</th>
                <th>Usuário</th>
                <th>CPF</th>
                <th>Data Nasc.</th>
                <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                <td>#{{ user.id }}</td>
                <td>
                    <div class="user-cell">
                      <img 
                          v-if="user.avatar" 
                          :src="user.avatar" 
                          alt="Avatar" 
                          class="avatar-img"
                          @error="user.avatar = undefined" 
                      >
                      <div v-else class="mini-avatar">
                          {{ user.name.charAt(0).toUpperCase() }}
                      </div>
                      
                      <span class="user-name">{{ user.name }}</span>
                  </div>
                </td>
                <td>{{ user.cpf || '-' }}</td>
                <td>{{ user.birth_date ? new Date(user.birth_date).toLocaleDateString('pt-BR') : '-' }}</td>
                <td>{{ user.email }}</td>
                </tr>
            </tbody>
            </table>

            <div v-else class="empty-state">
                <p>Nenhum usuário encontrado.</p>
            </div>

            <div class="pagination-footer" v-if="users.length > 0">
                <span class="total-info">
                    Total: <strong>{{ totalUsers }}</strong> usuários
                </span>
                
                <div class="pagination-controls">
                    <button 
                        @click="changePage(currentPage - 1)" 
                        :disabled="currentPage === 1"
                        class="page-btn"
                    >
                        Anterior
                    </button>
                    
                    <span class="page-info">
                        Página {{ currentPage }} de {{ lastPage }}
                    </span>
                    
                    <button 
                        @click="changePage(currentPage + 1)" 
                        :disabled="currentPage === lastPage"
                        class="page-btn"
                    >
                        Próxima
                    </button>
                </div>
            </div>
        </div>
      </div>
      
    </main>
  </div>
</template>

<style lang="scss" scoped>
// ... (Mantenha o CSS anterior do layout, top-bar, skeleton, etc) ...

// Adicione este CSS para a Paginação
.pagination-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-top: 1px solid #eee;
    background-color: #fafafa;

    .total-info {
        font-size: 0.9rem;
        color: #666;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 1rem;

        .page-info {
            font-size: 0.9rem;
            font-weight: 500;
            color: #333;
        }

        .page-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            color: #555;
            transition: all 0.2s;

            &:hover:not(:disabled) {
                background: #f0f0f0;
                border-color: #ccc;
            }

            &:disabled {
                opacity: 0.5;
                cursor: not-allowed;
                background: #f9f9f9;
            }
        }
    }
}

// ... (Mantenha o restante do CSS: dashboard-container, table-card, skeleton, etc)
// VOU REPETIR O SKELETON E LAYOUT PRA GARANTIR QUE NÃO QUEBRE:

$primary-color: #4285F4;
$bg-color: #f4f6f8;

.dashboard-container { min-height: 100vh; background-color: $bg-color; font-family: 'Roboto', sans-serif; }
.top-bar { background: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); 
    .user-profile { display: flex; align-items: center; gap: 10px; }
    .logout-link { background: none; border: none; color: #d93025; cursor: pointer; }
}
.content { padding: 2rem; max-width: 1000px; margin: 0 auto; }
.filter-section { margin-bottom: 1.5rem; .search-box { position: relative; max-width: 400px; input { width: 100%; padding: 12px 12px 12px 40px; border: 1px solid #ddd; border-radius: 8px; } .icon { position: absolute; left: 12px; top: 12px; } } }
.table-card { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; 
    table { width: 100%; border-collapse: collapse; th { background: #fafafa; padding: 1rem; text-align: left; border-bottom: 1px solid #eee; } td { padding: 1rem; border-bottom: 1px solid #eee; } }
    .user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
    
    .avatar-img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .mini-avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #4285F4, #34A853);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .user-name {
        font-weight: 500;
        color: #333;
    }
    }
}
.empty-state { padding: 3rem; text-align: center; color: #888; }

.skeleton-wrapper { padding: 1rem; .skeleton-header { height: 40px; background: #eee; margin-bottom: 1rem; border-radius: 4px; } .skeleton-row { display: flex; gap: 1rem; margin-bottom: 1rem; border-bottom: 1px solid #f0f0f0; padding-bottom: 0.5rem; } .skeleton-cell { height: 20px; background: #eee; border-radius: 4px; position: relative; overflow: hidden; &::after { content: ""; position: absolute; top: 0; right: 0; bottom: 0; left: 0; transform: translateX(-100%); background-image: linear-gradient(90deg, rgba(255, 255, 255, 0) 0, rgba(255, 255, 255, 0.2) 20%, rgba(255, 255, 255, 0.5) 60%, rgba(255, 255, 255, 0)); animation: shimmer 2s infinite; } } .w-small { width: 5%; } .w-medium { width: 20%; } .w-large { width: 35%; } }
@keyframes shimmer { 100% { transform: translateX(100%); } }
</style>