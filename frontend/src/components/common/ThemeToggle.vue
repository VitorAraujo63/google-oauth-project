<template>
    <div
        class="theme-toggle-track"
        @click="themeStore.toggleTheme"
        :class="{ 'dark-active': themeStore.currentTheme === 'dark' }"
        aria-label="Alternar tema Dark/Light"
        role="button"
    >
        <div class="icons-container">
            <svg class="icon sun-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="5"></circle>
                <line x1="12" y1="1" x2="12" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="23"></line>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                <line x1="1" y1="12" x2="3" y2="12"></line>
                <line x1="21" y1="12" x2="23" y2="12"></line>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
            </svg>

            <svg class="icon moon-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </div>

        <div class="toggle-ball"></div>
    </div>
</template>

<script setup lang="ts">
import { useThemeStore } from '@/stores/theme';
const themeStore = useThemeStore();
</script>

<style scoped lang="scss">
$toggle-width: 68px;
$toggle-height: 34px;
$ball-size: 28px;
$padding: 3px;

$color-sky-light: #25b5ee;
$color-sky-dark: #05265a;
$color-sun: #ddb500;
$color-moon: #ffffff;

.theme-toggle-track {
    width: $toggle-width;
    height: $toggle-height;
    background-color: $color-sky-light;
    border-radius: calc($toggle-height / 2);
    position: relative;
    cursor: pointer;
    display: flex;
    align-items: center;
    padding: $padding;
    box-sizing: border-box;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    transition: background-color 0.4s cubic-bezier(0.4, 0.0, 0.2, 1);
    border: 1px solid transparent;

    &.dark-active {
        background-color: $color-sky-dark;
        border-color: #334155;

        .toggle-ball {
            transform: translateX(calc($toggle-width - $ball-size - ($padding * 2)));
        }

        .sun-icon { color: rgba(255, 255, 255, 0.3); }
        .moon-icon { color: $color-moon; fill: currentColor; }
    }

    .icons-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 8px;
        box-sizing: border-box;
        pointer-events: none;
        z-index: 1;

        .icon {
            width: 18px;
            height: 18px;
            transition: color 0.3s ease, fill 0.3s ease;
        }

        .sun-icon {
            color: $color-sun;
            fill: $color-sun;
        }

        .moon-icon {
            color: rgba(255, 255, 255, 0.5);
        }
    }

    .toggle-ball {
        width: $ball-size;
        height: $ball-size;
        background-color: #FFFFFF;
        border-radius: 50%;
        position: absolute;
        top: $padding;
        left: $padding;
        z-index: 2;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
}
</style>
