<template>
    <div class="pagination-wrapper">
        <button
            class="btn-page"
            :disabled="current <= 1"
            @click="$emit('change', current - 1)"
        >
            Anterior
        </button>

        <span class="info">Página {{ current }} de {{ total }}</span>

        <button
            class="btn-page"
            :disabled="current >= total"
            @click="$emit('change', current + 1)"
        >
            Próximo
        </button>
    </div>
</template>

<script setup lang="ts">
defineProps<{ current: number; total: number }>();
defineEmits(['change']);
</script>

<style scoped lang="scss">
.pagination-wrapper {
    display: flex;
    align-items: center;
    gap: 15px;

    .info {
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .btn-page {
        background-color: var(--input-bg);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 8px 16px;
        border-radius: 6px;
        transition: 0.2s;

        &:disabled { opacity: 0.5; cursor: not-allowed; }
        &:not(:disabled):hover { border-color: var(--text-secondary); }
    }
}
</style>
