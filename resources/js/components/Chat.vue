<template>
    <div 
        :class="[
            'flex flex-col bg-gray-100 border-gray-200 p-0 h-screen',
            {
                'rounded-lg border': !$store.state.config.isMobile
            }
        ]"
    >
        <ChatHeader 
            @back="onBack"
            @close="emitMessage('chat.close')"
        />
        <ChatBody>
            <ChatPage 
                v-if="$store.state.page === 'home'"
            >
                <template #heading>
                    <div class="pt-8 pb-4" :style="{ backgroundColor: $store.state.config.mainColor }">
                        <h1 class="text-lg font-bold text-white text-center mb-2">
                            Start a conversation
                        </h1>
                        <p class="text-gray-200 text-sm text-center">
                            What channel do you prefer?
                        </p>
                    </div>
                </template>
                <div class="p-4">
                    <ChatPageButton
                        v-for="page in $store.state.config.pages"
                        :key="page.id"
                        @click="onPageButtonClick(page.id)"
                    >
                        <template 
                            v-if="page.avatar"
                            #icon
                        >
                            <div class="w-10 h-10 relative">
                                <img class="rounded-full w-10 h-10" :src="page.avatar" />
                                <div class="rounded-full w-3 h-3 border border-2 border-white bg-green-500 absolute bottom-0 right-0"></div>
                            </div>
                        </template>
                        <template 
                            v-else
                            #icon-svg
                        >
                            <span class="icon" v-html="page.icon"></span>
                        </template>
                        <template #title>
                            {{ page.buttonTitle || page.title }}
                        </template>
                        <template #description>
                            {{ page.buttonDescription || page.description }}
                        </template>
                    </ChatPageButton>
                    <!--
                    <ChatPageButton
                        @click="$store.state.page = 'ai'"
                    >
                        <template #icon-svg>
                            <span class="icon" v-html="$store.state.config.icons.bot"></span>
                        </template>
                        <template #title>
                            AI Answers
                        </template>
                        <template #description>
                            Instant answers from your data
                        </template>
                    </ChatPageButton>
                    <ChatPageButton
                        @click="$store.state.page = 'search'"
                    >
                        <template #icon-svg>
                            <span class="icon" v-html="$store.state.config.icons.search"></span>
                        </template>
                        <template #title>
                            Search
                        </template>
                        <template #description>
                            Search your data
                        </template>
                    </ChatPageButton>
                    <ChatPageButton
                        @click="$store.state.page = 'email'"
                    >
                        <template #icon-svg>
                            <span class="icon" v-html="$store.state.config.icons.email"></span>
                        </template>
                        <template #title>
                            Email
                        </template>
                        <template #description>
                            We respond within 48 hours
                        </template>
                    </ChatPageButton>
                    <ChatPageButton
                        @click="$store.state.page = 'chat'"
                    >
                        <template #icon>
                            <div class="w-10 h-10 relative">
                                <img class="rounded-full w-10 h-10" src="https://placehold.co/100x100" />
                                <div class="rounded-full w-3 h-3 border border-2 border-white bg-green-500 absolute bottom-0 right-0"></div>
                            </div>
                        </template>
                        <template #title>
                            Chat
                        </template>
                        <template #description>
                            Message with someone now
                        </template>
                    </ChatPageButton>
                    -->
                </div>
            </ChatPage>

            <template 
                v-for="page in $store.state.config.pages"
            >
                <ChatPage 
                    :key="page.id"
                    v-if="$store.state.page === page.id"
                    :title="page.title"
                >
                    <ChatMessages 
                        :pageId="page.id"
                    />
                </ChatPage>
            </template>

            <!--
            <ChatPage 
                v-if="$store.state.page === 'messages'"
                title="Start a conversation" 
                description="What channel do you prefer?"
            >
                <ChatMessages />
            </ChatPage>
            <ChatPage 
                v-if="$store.state.page === 'ai'"
                title="AI Answers" 
            >
                <ChatMessages />
            </ChatPage>
            <ChatPage 
                v-if="$store.state.page === 'email'"
                title="Send an email" 
            >
                <ChatMessages />
            </ChatPage>
            <ChatPage 
                v-if="$store.state.page === 'search'"
                title="Search" 
            >
                <ChatMessages />
            </ChatPage>
            <ChatPage 
                v-if="$store.state.page === 'chat'"
                title="Chat" 
            >
                <ChatMessages />
            </ChatPage>
            -->
        </ChatBody>
        <ChatFooter>
            <ChatInput 
                v-if="$store.state.showChatInput"
                @submit="onChatInputSubmit"
            />
        </ChatFooter>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useStore } from 'vuex'
import { emitMessage, api } from '../utils'
import ChatHeader from './ChatHeader.vue'
import ChatBody from './ChatBody.vue'
import ChatFooter from './ChatFooter.vue'
import ChatMessages from './ChatMessages.vue'
import ChatInput from './ChatInput.vue'
import ChatPage from './ChatPage.vue'
import ChatPageButton from './ChatPageButton.vue'

const store = useStore()

const writeToMessages = (message, pageId = null) => {
    store.commit('messages', { message, pageId })
}

const sayAsBot = (message) => {
    writeToMessages({
      ...message,
      ...{
        type: 'text',
        from: 'chatbot',
      }  
    })
}

const whisper = (message) => {
    say(message, false)
}

const say = (message, showMessage = true) => {
    const pageId = store.state.page
    store.commit('loading', true)
    let timeout = 0
    api({
        ...{
            chatServer: store.getters.chatServer(pageId),
        },
        ...message,
        ...{
            perMessageCallback: (message) => {
                writeToMessages({
                    ...message,
                    ...{
                        from: 'chatbot',
                        timeout: timeout += (message.timeout || 0),
                    }
                }, pageId)
            }, 
            callback: (response) => {
                store.commit('loading', false)
                if (message.callback) {
                    message.callback(response)
                }
            }
        }
    })
    if (showMessage) {
        writeToMessages({
            ...message,
            ...{
                from: 'visitor',
            }
        }, pageId)
    }
}

window.addEventListener('message', (event) => {
    if (event.data?.method === 'botman-web-widget.widget.toggle') {
        $store.state.open = event.data.params.open
    }
    if (event.data?.method === 'botman-web-widget.chat.api') {
        api({ ...event.data.params, ...{
            callback: (data) => {
                emitMessage('chat.api.response', data)
            }, 
            errorHandler: (error) => {
                emitMessage('chat.api.error', {
                    message: error.message,
                    status: error.response.status,
                    headers: error.response.headers,
                    data: error.response.data,
                })
            }
        }})
    }
    if (event.data?.method === 'botman-web-widget.chat.sayAsBot') {
        sayAsBot(event.data.params)
    }
    if (event.data?.method === 'botman-web-widget.chat.whisper') {
        whisper(event.data.params)
    }
    if (event.data?.method === 'botman-web-widget.chat.say') {
        say(event.data.params)
    }
    if (event.data?.method === 'botman-web-widget.chat.page') {
        store.commit('page', event.data.params.id)
    }
    if (event.data?.method === 'botman-web-widget.chat.writeToMessages') {
        writeToMessages(event.data.params)
    }
})

onMounted(() => {
    emitMessage('chat.init')
    store.commit('page', store.state.config.defaultPage || 'home')
})

const onBack = () => {
    emitMessage('chat.back')
    store.commit('page', 'home')
}

const onPageButtonClick = (pageId) => {
    store.commit('page', pageId)
}

const onChatInputSubmit = (message) => {
    say({ ...store.state.input })
    store.commit('resetInput')
}

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        emitMessage('chat.esc')
    }
})
</script>