import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

import Toast, { type PluginOptions } from "vue-toastification";
import "vue-toastification/dist/index.css";

const app = createApp(App)

app.use(createPinia())
app.use(router)

const options: PluginOptions = {
    timeout: 3000,
    position: "top-right" as any
};
app.use(Toast, options);

app.mount('#app')