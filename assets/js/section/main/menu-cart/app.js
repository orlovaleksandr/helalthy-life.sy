import { createApp } from 'vue';
import App from './App.vue';
import store from "./store";


createApp(App)
    .use(store)
    .mount('#appMenuCart')

window.vueMenuCartInstance = {};
window.vueMenuCartInstance.addCartProduct =
    (productData) =>  store.dispatch('cart/addCartProduct', productData)
