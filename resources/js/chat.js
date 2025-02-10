import { createApp } from 'vue'
import { createStore } from 'vuex'
import Chat from './components/Chat.vue'

const app = createApp(Chat)

const store = createStore({
    state: {
        config: window.botmanWidget,
        open: false,
        title: 'Chat',
        messages: [],
        conversation: null,
    },
    mutations: {
        setTitle(state, title) {
            state.title = title
        },
        setOpen(state, open) {
            state.open = open
        },
        addMessage(state, message) {
            state.messages.push(message)
        },
        setMessages(state, messages) {
            state.messages = messages
        },
        setConversation(state, conversation) {
            state.conversation = conversation
        },
    }
})

app.use(store)

app.mount('#chat')
