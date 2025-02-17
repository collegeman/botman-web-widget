<template>
    <ul class="flex flex-col p-4 list-none">
        <slot>
            <ChatMessage
                v-for="message in $store.state.messages[props.pageId]"
                :key="message.id"
                :message="message"
                @message="onMessage"
            />
            <ChatMessage
                v-if="$store.state.loading"
                :loading="true"
                :message="{ from: 'chatbot' }"
                @message="onMessage"
            />
            <ChatMessage
                v-if="$store.state.error"
                :message="{ from: 'chatbot', text: '<span class=\'text-red-500\'>Oops! Please try again.</span>' }"
                @message="onMessage"
            />
        </slot>
    </ul>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useStore } from 'vuex'
import ChatMessage from './ChatMessage.vue'

const store = useStore()

const emit = defineEmits(['message'])

const props = defineProps({
    pageId: {
        type: String,
        required: true,
    },
})

onMounted(() => store.state.showChatInput = true)
onUnmounted(() => store.state.showChatInput = false)

const onMessage = (message, $el) => {
    emit('message', message, $el)
}
</script>