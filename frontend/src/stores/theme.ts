import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useThemeStore = defineStore('theme', () => {
    const currentTheme = ref(localStorage.getItem('user-theme') || 'dark');

    function applyTheme() {
        document.documentElement.setAttribute('data-theme', currentTheme.value);
        localStorage.setItem('user-theme', currentTheme.value);
    }

    function toggleTheme() {
        currentTheme.value = currentTheme.value === 'light' ? 'dark' : 'light';
        applyTheme();
    }

    applyTheme();

    return { currentTheme, toggleTheme };
});
