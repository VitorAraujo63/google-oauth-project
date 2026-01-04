<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/services/api';
import { useAuthStore } from '@/stores/auth';
import { useToast } from "vue-toastification";

const router = useRouter();
const authStore = useAuthStore();
const toast = useToast();
const isLoading = ref(false);

const form = ref({
  cpf: '',
  birth_date: ''
});

const errors = ref({
  cpf: '',
  birth_date: ''
});


const handleCpfInput = (event: Event) => {
    const input = event.target as HTMLInputElement;
    let value = input.value;

    value = value.replace(/\D/g, '');
    if (value.length > 11) {
        value = value.slice(0, 11);
    }
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

    form.value.cpf = value;
    input.value = value;
};

const submitForm = async () => {
  errors.value = { cpf: '', birth_date: '' };

  try {
    isLoading.value = true;
    const { data } = await api.post('/auth/complete-registration', form.value);

    authStore.user = data.user;
    toast.success("Cadastro completado! Bem-vindo(a).");
    router.push({ name: 'dashboard' });

  } catch (error: any) {
    console.error(error);
    if (error.response?.status === 422) {
      const apiErrors = error.response.data.errors;
      if (apiErrors.cpf) errors.value.cpf = apiErrors.cpf[0];
      if (apiErrors.birth_date) errors.value.birth_date = apiErrors.birth_date[0];
      toast.warning("Verifique os campos em vermelho.");
    } else {
      toast.error("Ocorreu um erro no servidor. Tente novamente.");
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="register-container">
    <div class="card">
      <h2>Complete seu Cadastro</h2>
      <p>Precisamos de mais algumas informações.</p>

      <form @submit.prevent="submitForm">

        <div class="form-group">
          <label>CPF</label>
          <input
            :value="form.cpf"
            @input="handleCpfInput"
            type="text"
            placeholder="000.000.000-00"
            class="input-field"
            :class="{ 'input-error': errors.cpf }"
            maxlength="14"
            required
          >
          <span v-if="errors.cpf" class="error-text">{{ errors.cpf }}</span>
        </div>

        <div class="form-group">
          <label>Data de Nascimento</label>
          <input
            v-model="form.birth_date"
            type="date"
            class="input-field"
            :class="{ 'input-error': errors.birth_date }"
            required
          >
          <span v-if="errors.birth_date" class="error-text">{{ errors.birth_date }}</span>
        </div>

        <button type="submit" :disabled="isLoading">
          {{ isLoading ? 'Salvando...' : 'Finalizar Cadastro' }}
        </button>
      </form>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.register-container {
    display: flex; justify-content: center; align-items: center; height: 100vh; background: #f0f2f5;
}
.card {
    background: white; padding: 2rem; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    h2 { margin-bottom: 0.5rem; color: #333; }
    p { margin-bottom: 1.5rem; color: #666; }
}

.form-group {
  margin-bottom: 1rem;
  display: flex;
  flex-direction: column;

  label { font-size: 0.9rem; font-weight: 500; color: #333; margin-bottom: 0.5rem; }

  .input-field {
    padding: 0.8rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1rem;
    width: 100%;
    box-sizing: border-box;
    transition: all 0.2s;

    &:focus { outline: none; border-color: #4285F4; box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.2); }

    &.input-error { border-color: #d93025; background-color: #fff8f8; }
  }

  .error-text {
    color: #d93025;
    font-size: 0.8rem;
    margin-top: 0.25rem;
  }
}

button {
  width: 100%; padding: 1rem; background: #4285F4; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; font-weight: bold; margin-top: 1rem; transition: background 0.2s;
  &:disabled { background: #ccc; cursor: not-allowed; }
  &:hover:not(:disabled) { background: #3367d6; }
}
</style>
