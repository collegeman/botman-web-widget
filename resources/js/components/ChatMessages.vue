<template>
    <div class="p-4">
        <slot>
            <ChatMessage
                v-for="message in $store.state.messages[props.pageId]"
                :key="message.id"
                :message="message"
            />
            <ChatMessage
                v-if="$store.state.loading"
                :loading="true"
                :message="{ from: 'chatbot' }"
            />
        </slot>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useStore } from 'vuex'
import ChatMessage from './ChatMessage.vue'

const store = useStore()

const props = defineProps({
    pageId: {
        type: String,
        required: true,
    },
})

onMounted(() => store.state.showChatInput = true)
onUnmounted(() => store.state.showChatInput = false)
</script>