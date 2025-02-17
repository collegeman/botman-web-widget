import { createApp, nextTick } from 'vue'
import { createStore } from 'vuex'
import Chat from './components/Chat.vue'
import { v4 as uuidv4 } from 'uuid'

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
        waiting: false,
        error: false,
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
                    nextTick(() => setTimeout(() => state.messages[page.id].push({ 
                        id: uuidv4(),
                        time: new Date().getTime(),
                        text: page.introMessage, 
                        from: 'chatbot' 
                    }), 500))
                }
            }
        },
        messages(state, { message, pageId }) {
            let data = typeof message === 'string' ? { text: message } : message
            if (typeof data.id === "undefined") {
                data.id = uuidv4()
            }
            if (typeof data.time === "undefined") {
                data.time = new Date().getTime()
            }
            state.messages[pageId || state.page].push(data)
        },
        loading(state, value) {
            state.loading = value
        },
        waiting(state, value) {
            state.waiting = value
        },
        error(state, value) {
            state.error = value
        },
    },
})

app.use(store)

app.mount('#chat')
