import { createApp, nextTick } from 'vue'
import { createStore } from 'vuex'
import Chat from './components/Chat.vue'

const app = createApp(Chat)

const config = window.botmanWidget

// add default page
if (!config.pages?.length) {
    config.pages = [
        {
            id: 'chat',
            title: 'Chat',
            buttonTitle: 'Chat with our bot',
            buttonDescription: 'Available to help 24/7',
            icon: config.icons.bot,
            introMessage: config.introMessage,
            chatServer: config.chatServer,
        }
    ]
}

config.pages.forEach(page => page.pristine = true)

const store = createStore({
    state: {
        config,
        open: false,
        title: null,
        page: null,
        messages: config.pages.reduce((messages, page) => { messages[page.id] = []; return messages }, {}),
        conversation: null,
        input: {
            text: '',
            attachment: null,
        },
        showChatInput: false,
        loading: false,
    },
    getters: {
        chatServer: (state) => (pageId) => {
            return state.config.pages.find(page => page.id === pageId)?.chatServer || state.config.chatServer
        },
        showBackButton(state) {
            return state.page !== 'home'
        },
        pristine: (state) => (pageId) => {
            return state.config.pages.find(page => page.id === pageId).pristine
        },
        introMessage: (state) => (pageId) => {
            return state.config.pages.find(page => page.id === pageId).introMessage
        },
    },
    mutations: {
        resetInput(state) {
            state.input.text = ''
            state.input.attachment = null
        },
        pristine(state, { pageId, value }) {
            state.config.pages.find(page => page.id === pageId).pristine = value
        },
        page(state, pageId) {
            state.page = pageId
            const page = state.config.pages.find(page => page.id === pageId)
            if (page?.pristine) {
                page.pristine = false
                if (page.introMessage) {
                    nextTick(() => setTimeout(() => state.messages[page.id].push({ text: page.introMessage, from: 'chatbot' }), 500))
                }
            }
        },
        messages(state, { message, pageId }) {
            state.messages[pageId || state.page].push(typeof message === 'string' ? { text: message } : message)
        },
        loading(state, value) {
            state.loading = value
        },
    },
})

app.use(store)

app.mount('#chat')
