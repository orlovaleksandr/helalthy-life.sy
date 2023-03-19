import { createApp } from 'vue';
import App from './App.vue';
import store from "./store";

globalThis.__VUE_OPTIONS_API__ = true;
globalThis.__VUE_PROD_DEVTOOLS__ = false;

createApp(App)
    .use(store)
    .mount('#app')

