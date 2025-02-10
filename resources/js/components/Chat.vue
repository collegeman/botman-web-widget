<template>
    <div 
        class="flex flex-col bg-gray-100 rounded-lg border border-gray-200 p-0 h-screen"
    >
        <ChatHeader />
        <ChatBody>
            <ChatPage id="home" title="Start a conversation" description="What channel do you prefer?">
                
            </ChatPage>
        </ChatBody>
        <ChatFooter />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

import ChatHeader from './ChatHeader.vue'
import ChatBody from './ChatBody.vue'
import ChatFooter from './ChatFooter.vue'
import ChatPage from './ChatPage.vue'

let open = ref(false)

window.addEventListener('message', (event) => {
    if (event.data?.type === 'botman-web-widget.widget.toggle') {
        open.value = event.data.data.open
    }
})

onMounted(() => {
    window.parent.postMessage({
        type: 'botman-web-widget.chat.init',
        data: {
            //
        }
    }) 
})
</script>
