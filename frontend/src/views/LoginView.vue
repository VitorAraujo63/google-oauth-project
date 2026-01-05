<template>
  <div class="login-container">
    <main class="login-content">
      <h1 class="title">
        <span class="title-small">Esse é meu projeto</span>
        <span class="title-large">
          <strong>OAuth</strong> com o google
        </span>
      </h1>

      <button class="gsi-material-button" @click="handleGoogleLogin">
        <div class="gsi-material-button-state"></div>
        <div class="gsi-material-button-content-wrapper">
          <div class="gsi-material-button-icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" xmlns:xlink="http://www.w3.org/1999/xlink" style="display: block;">
              <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
              <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
              <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
              <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
              <path fill="none" d="M0 0h48v48H0z"></path>
            </svg>
          </div>
          <span class="gsi-material-button-contents">Continue with Google</span>
          <span style="display: none;">Continue with Google</span>
        </div>
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

.gsi-material-button {
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
  -webkit-appearance: none;
  background-color: WHITE;
  background-image: none;
  border: 1px solid #747775;
  -webkit-border-radius: 20px;
  border-radius: 20px;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  color: #1f1f1f;
  cursor: pointer;
  font-family: 'Roboto', arial, sans-serif;
  font-size: 14px;
  height: 40px;
  letter-spacing: 0.25px;
  outline: none;
  overflow: hidden;
  padding: 0 12px;
  position: relative;
  text-align: center;
  -webkit-transition: background-color .218s, border-color .218s, box-shadow .218s;
  transition: background-color .218s, border-color .218s, box-shadow .218s;
  vertical-align: middle;
  white-space: nowrap;
  width: auto;
  max-width: 400px;
  min-width: min-content;
}

.gsi-material-button .gsi-material-button-icon {
  height: 20px;
  margin-right: 10px;
  min-width: 20px;
  width: 20px;
}

.gsi-material-button .gsi-material-button-content-wrapper {
  -webkit-align-items: center;
  align-items: center;
  display: flex;
  -webkit-flex-direction: row;
  flex-direction: row;
  -webkit-flex-wrap: nowrap;
  flex-wrap: nowrap;
  height: 100%;
  justify-content: space-between;
  position: relative;
  width: 100%;
}

.gsi-material-button .gsi-material-button-contents {
  -webkit-flex-grow: 1;
  flex-grow: 1;
  font-family: 'Roboto', arial, sans-serif;
  font-weight: 500;
  overflow: hidden;
  text-overflow: ellipsis;
  vertical-align: top;
}

.gsi-material-button .gsi-material-button-state {
  -webkit-transition: opacity .218s;
  transition: opacity .218s;
  bottom: 0;
  left: 0;
  opacity: 0;
  position: absolute;
  right: 0;
  top: 0;
}

.gsi-material-button:disabled {
  cursor: default;
  background-color: #ffffff61;
  border-color: #1f1f1f1f;
}

.gsi-material-button:disabled .gsi-material-button-contents {
  opacity: 38%;
}

.gsi-material-button:disabled .gsi-material-button-icon {
  opacity: 38%;
}

.gsi-material-button:not(:disabled):active .gsi-material-button-state,
.gsi-material-button:not(:disabled):focus .gsi-material-button-state {
  background-color: #303030;
  opacity: 12%;
}

.gsi-material-button:not(:disabled):hover {
  -webkit-box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .30), 0 1px 3px 1px rgba(60, 64, 67, .15);
  box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .30), 0 1px 3px 1px rgba(60, 64, 67, .15);
}

.gsi-material-button:not(:disabled):hover .gsi-material-button-state {
  background-color: #303030;
  opacity: 8%;
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
