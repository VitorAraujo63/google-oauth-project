<template>
  <div class="login-container">
    <main class="login-content">
      <h1 class="title">
        <span class="title-small">Esse é meu projeto</span>
        <span class="title-large">
          <strong>OAuth</strong> com o google
        </span>
      </h1>

      <button class="google-btn" @click="handleGoogleLogin">
        <img
          src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
          alt="Google logo"
          class="google-icon"
        />
        <span class="btn-text">Continue with Google</span>
      </button>
    </main>

    <footer class="tech-marquee-container">
      <div class="marquee-track">
        <div class="tech-list">
          <span>Laravel 12</span>
          <span>Vue.js 3</span>
          <span>Google OAuth</span>
          <span>TypeScript</span>
          <span>Pinia</span>
          <span>Vite</span>
          <span>Docker</span>
          <span>CI/CD</span>
          <span>MySQL</span>
          <span>Sass</span>
          <span>Nginx</span>
          <span>Service Pattern</span>
        </div>
        <div class="tech-list">
          <span>Laravel 12</span>
          <span>Vue.js 3</span>
          <span>Google OAuth</span>
          <span>TypeScript</span>
          <span>Pinia</span>
          <span>Vite</span>
          <span>Docker</span>
          <span>CI/CD</span>
          <span>MySQL</span>
          <span>Sass</span>
          <span>Nginx</span>
          <span>Service Pattern</span>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import api from '@/services/api';

const isLoading = ref(false);
const errorMessage = ref('');

const handleGoogleLogin = async () => {
  try {
    isLoading.value = true;
    errorMessage.value = '';

    const { data } = await api.get('/auth/google/url');

    window.location.href = data.url;

  } catch (error) {
    console.error('Erro ao iniciar login:', error);
    errorMessage.value = 'Não foi possível conectar ao servidor. Tente novamente.';
    isLoading.value = false;
  }
};


</script>

<style scoped lang="scss">
.login-container {
  background: url('@/assets/img/background.svg') no-repeat center center, #121212;
  background-size: cover;
  min-height: 100vh;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: #ffffff;
  position: relative;
  overflow: hidden;
}

.login-content {
  text-align: center;
  z-index: 2;
  margin-bottom: 60px;
}

.title {
  display: flex;
  flex-direction: column;
  margin-bottom: 40px;
  line-height: 1.3;
  text-shadow: 0 4px 10px rgba(0,0,0,0.5);

  .title-small {
    font-size: 1.6rem;
    font-weight: 300;
    opacity: 0.9;
  }

  .title-large {
    font-size: 2.8rem;
    font-weight: 400;
    margin-top: 5px;

    strong {
      font-weight: 700;
      font-size: 3.5rem;
    }
  }
}

.google-btn {
  background-color: #ffffff;
  color: #333333;
  border: none;
  border-radius: 50px;
  padding: 14px 32px;
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1.1rem;
  font-weight: 500;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  transition: all 0.2s ease;
  margin: 0 auto;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
    background-color: #f2f2f2;
  }

  &:active {
    transform: scale(0.98);
  }

  .google-icon {
    width: 24px;
    height: 24px;
  }
}


.tech-marquee-container {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  background: rgba(40, 40, 40, 0.6);
  backdrop-filter: blur(5px);
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding: 15px 0;
  overflow: hidden;
  white-space: nowrap;
  display: flex;
}

.marquee-track {
  display: flex;
  animation: scroll 20s linear infinite;
}

.tech-list {
  display: flex;
  gap: 40px;
  padding-right: 40px;

  span {
    color: #fff;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;

    text-shadow:
      0 0 5px rgba(255, 255, 255, 0.6),
      0 0 10px rgba(255, 255, 255, 0.4);

    display: flex;
    align-items: center;

    &::after {
      content: '•';
      margin-left: 40px;
      color: rgba(255,255,255, 0.3);
      text-shadow: none;
    }

    &:last-child::after {
      display: none;
    }
  }
}

@keyframes scroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}

@media (max-width: 768px) {
  .title .title-small { font-size: 1.2rem; }
  .title .title-large { font-size: 2rem; strong { font-size: 2.5rem; } }

  .marquee-track {
    animation-duration: 15s;
  }

  .tech-list span {
    font-size: 0.8rem;
  }
}
</style>
