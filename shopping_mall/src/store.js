import { createApp } from 'vue'
import { createStore } from 'vuex'
import App from './App.vue'

export const store = createStore({
    state : {
        product_show_product_name : "hello",
        product_show_uploader : "",
        product_show_price : 0,
        product_show_rate : 0,
    },

    mutations : {
      set_product_show_product_name(att) {
        this.product_show_product_name = att;
      },

      set_product_show_uploader(att) {
        this.product_show_uploader = att;
      },

      set_product_show_product_name(att) {
        this.product_show_product_name = att;
      },

      set_product_show_product_name(att) {
        this.product_show_product_name = att;
      }
    }
});

const app = createApp(App)

app.use(store)