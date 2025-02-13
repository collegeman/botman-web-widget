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
            <ChatPage id="home" title="Start a conversation" description="What channel do you prefer?">
                
            </ChatPage>
        </ChatBody>
        <ChatFooter />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { emitMessage, api } from '../utils'
import ChatHeader from './ChatHeader.vue'
import ChatBody from './ChatBody.vue'
import ChatFooter from './ChatFooter.vue'
import ChatPage from './ChatPage.vue'

window.addEventListener('message', (event) => {
    if (event.data?.method === 'botman-web-widget.widget.toggle') {
        $store.state.open = event.data.params.open
    }
    if (event.data?.method === 'botman-web-widget.chat.api') {
        api(event.data.params.text, event.data.params.interactive, event.data.params.attachment, null, (data) => {
            emitMessage('chat.api.response', data)
        }, (error) => {
            emitMessage('chat.api.error', {
                message: error.message,
                status: error.response.status,
                headers: error.response.headers,
                data: error.response.data,
            })
        })
    }
})

onMounted(() => emitMessage('chat.init'))

const onBack = () => {
    emitMessage('chat.back')
}

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        emitMessage('chat.esc')
    }
})
</script>