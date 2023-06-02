import './bootstrap';
import { createApp } from 'vue'
import CreatePurchase from './purchase/CreatePurchase.vue'
import { plugin as ZiggyVue } from 'ziggy-vue'
import VueSweetalert2 from 'vue-sweetalert2';

// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';

const app =  createApp({})
app.use(ZiggyVue)
app.use(VueSweetalert2);
app.component('create-purchase',CreatePurchase)
app.mount('#app')
