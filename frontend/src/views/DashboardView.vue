<template>
    <div class="dashboard-container">
        <header class="top-header">
            <h1 class="logo-text">Dashboard users</h1>

            <div class="header-controls">
                <ThemeToggle />

                <div class="user-profile">
                    <template v-if="userState">
                        <div class="profile-content">
                            <img
                                :src="userState.avatar"
                                alt="Avatar"
                                class="avatar-img"
                            >
                            <div class="text-info">
                                <span class="welcome-text">Welcome,</span>
                                <span class="name">{{ userState.name }}</span>
                            </div>
                        </div>
                    </template>

                    <template v-else>
                        <div class="profile-loading">
                            <div class="skeleton-avatar"></div>
                            <div class="skeleton-text"></div>
                        </div>
                    </template>
                </div>

                <button class="btn-logout" @click="logout">Sair</button>
            </div>
        </header>

        <div class="search-section">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input
                    type="text"
                    placeholder="Search"
                    @input="handleSearch"
                >
            </div>
        </div>

        <main class="content-table">

            <div v-if="userStore.loading" class="loading-msg">Carregando dados...</div>

            <div v-else>
                <div class="table-header desktop-only">
                    <div class="th">ID</div>
                    <div class="th">Criado</div>
                    <div class="th">Usuario</div>
                    <div class="th">CPF</div>
                    <div class="th">Data nascimento</div>
                    <div class="th">Email</div>
                </div>

                <div class="table-rows">
                    <div v-for="user in userStore.users" :key="user.id" class="table-row">
                        <div class="td td-id" data-label="ID"># {{ user.id }}</div>

                        <div class="td" data-label="Criado">
                            {{ new Date(user.created_at).toLocaleString('pt-BR') }}
                        </div>

                        <div class="td td-user" data-label="Usuario">
                            <div class="user-info-flex">
                                <img
                                    v-if="user.avatar"
                                    :src="user.avatar"
                                    class="user-avatar"
                                    @error="(e) => (e.target as HTMLImageElement).style.display='none'"
                                >
                                <div v-else class="user-avatar placeholder">
                                    {{ user.name.charAt(0) }}
                                </div>
                                <span>{{ user.name }}</span>
                            </div>
                        </div>

                        <div class="td" data-label="CPF">
                            {{ formatHiddenCPF(user.cpf) }}
                        </div>

                        <div class="td" data-label="Data nascimento">
                            {{ new Date(user.birth_date).toLocaleDateString('pt-BR') }}
                        </div>

                        <div class="td" data-label="Email">{{ user.email }}</div>
                    </div>
                </div>
            </div>

            <div class="table-footer">
                <Pagination
                    :current="userStore.pagination.current_page"
                    :total="userStore.pagination.last_page"

                    @change="(p) => userStore.fetchUsers(p, currentSearch)"
                />
            </div>

        </main>
    </div>
</template>


<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useUserStore } from '@/stores/users';
import ThemeToggle from '@/components/common/ThemeToggle.vue';
import Pagination from '@/components/common/Pagination.vue';
import { userState } from '@/stores/auth';
import api from '@/services/api';

const userStore = useUserStore();
let timer: number;
const currentSearch = ref('');

function formatHiddenCPF(cpf: string) {
    if (!cpf) return '***.***.***-**';

    const clean = cpf.replace(/\D/g, '');

    return clean.replace(/^(\d{3})(\d{1}).*/, '$1.$2**.***-**');
}

const handleSearch = (e: Event) => {
    clearTimeout(timer);

    const inputElement = e.target as HTMLInputElement;
    let val = inputElement.value;

    const isNumeric = /^\d+$/.test(val.replace(/\.|-/g, ''));

    if (isNumeric && val.length > 4) {
        val = val.substring(0, 4);
    }

    currentSearch.value = val;

    timer = setTimeout(() => {
        userStore.fetchUsers(1, currentSearch.value);
    }, 500);
};

onMounted(() => {
    userStore.fetchUsers();
});

function logout() {
    api.post('/logout').catch(() => {});
    localStorage.removeItem('auth_token');
    userState.value = null;
    window.location.href = '/';
}
</script>

<style scoped lang="scss">
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* HEADER */
.top-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
    gap: 20px;

    .logo-text { font-size: 1.5rem; font-weight: 700; margin: 0; }

    .header-controls {
        display: flex;
        align-items: center;
        gap: 20px;

        /* PERFIL DO USUÁRIO */
        .user-profile {
            display: flex;
            align-items: center;
            min-width: 150px;

            .profile-content {
                display: flex;
                align-items: center;
                gap: 10px;
                text-align: right;
            }

            .avatar-img {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                object-fit: cover;
                border: 2px solid var(--border-color);
            }

            .text-info {
                display: flex;
                flex-direction: column;
                line-height: 1.2;
                font-size: 0.85rem;
            }

            .welcome-text {
                color: var(--text-secondary);
                font-size: 0.8rem;
            }

            .name {
                font-size: 0.95rem;
                font-weight: 600;
                color: var(--text-primary);
            }

            /* Loading Skeleton */
            .profile-loading {
                display: flex;
                align-items: center;
                gap: 10px;
                opacity: 0.6;

                .skeleton-avatar {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background-color: var(--border-color);
                }

                .skeleton-text {
                    width: 80px;
                    height: 12px;
                    background-color: var(--border-color);
                    border-radius: 4px;
                }
            }
        }

        .btn-logout {
            background-color: var(--color-danger);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;

            &:hover { opacity: 0.9; }
        }
    }
}

/* SEARCH */
.search-section {
    display: flex;
    justify-content: center;
    margin-bottom: 30px;

    .search-box {
        position: relative;
        width: 100%;
        max-width: 600px;

        .search-icon {
            position: absolute;
            left: 15px; top: 50%; transform: translateY(-50%);
            color: var(--text-secondary);
        }

        input {
            width: 100%;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            padding: 12px 12px 12px 45px;
            border-radius: 25px;
            color: var(--text-primary);
            outline: none;
            font-size: 1rem;
            box-sizing: border-box;

            &:focus { border-color: #666; }
        }
    }
}

/* TABLE LAYOUT (CSS GRID) */
.table-header {
    display: grid;
    grid-template-columns: 0.5fr 1.5fr 2fr 1.5fr 1.5fr 2fr;
    padding: 15px;
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 1.1rem;
}

.table-row {
    display: grid;
    grid-template-columns: 0.5fr 1.5fr 2fr 1.5fr 1.5fr 2fr;
    padding: 20px 15px;
    align-items: center;
    border-bottom: 1px solid var(--border-color);

    .td { font-size: 0.95rem; }

    .user-info-flex {
        display: flex; align-items: center; gap: 15px;
        font-weight: 500; font-size: 1rem;

        .user-avatar {
            width: 40px; height: 40px; border-radius: 50%; object-fit: cover;
            &.placeholder {
                background: var(--input-bg);
                border: 1px solid var(--border-color);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--text-primary);
                font-weight: bold;
            }
        }
    }
}

.table-footer {
    display: flex;
    justify-content: flex-end;
    padding: 30px 0;
}

/* RESPONSIVO (MOBILE) */
@media (max-width: 768px) {
    .desktop-only { display: none; }

    .top-header {
        flex-direction: column; align-items: flex-start;
        .header-controls { width: 100%; justify-content: space-between; }
        .text-info { display: block; }
    }

    .table-row {
        display: flex;
        flex-direction: column;
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        margin-bottom: 15px;
        padding: 0;
        overflow: hidden;

        .td {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 12px 15px;
            box-sizing: border-box;

            &::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--text-secondary);
            }
        }

        .td-user {
            order: -1;
            background-color: var(--input-bg);
            border-bottom: 1px solid var(--border-color);
            justify-content: flex-start;
            padding: 15px;

            &::before { display: none; }
        }
    }

    .table-footer { justify-content: center; }
}
</style>
