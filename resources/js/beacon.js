import { createApp } from 'vue'
import { createStore } from 'vuex'
import Beacon from './components/Beacon.vue'

const app = createApp(Beacon)

const store = createStore({
    state: {
        //
    },
    mutations: {
        //
    }
})

app.use(store)

app.mount('#beacon')
