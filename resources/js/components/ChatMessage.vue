<template>
    <div
        v-if="visible" 
        class="flex w-full mb-2 :last:mb-0"
    >
        <div class="flex-shrink-0 mt-1.5 mr-2">
            <div 
                class="w-6 h-6 rounded-full bg-gray-500 flex items-center justify-center"
                :style="avatarStyle"
            >
                <img 
                    v-if="message.additionalParameters?.avatar"
                    :src="message.additionalParameters.avatar"
                    class="w-full h-full object-cover"
                >
                <span 
                    v-else
                    class="icon w-4 h-4"
                >
                    <span v-html="icon"></span>
                </span>
            </div>
        </div>
        <div 
            v-if="loading || message.type === MessageTypes.TypingIndicator"
            class="py-2 text-sm"
        >
            <div class="my-1 w-3 h-3 rounded-full bg-black animate-pulse"></div>
        </div>
        <div 
            v-else
            class="py-2 px-4 bg-white rounded-lg text-sm"
        >
            {{ message.text }}
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useStore } from 'vuex'
import { MessageTypes } from '../utils'

const props = defineProps({
    message: {
        type: Object,
        required: true
    },
    loading: {
        type: Boolean,
        default: false,
    },
})

let visible = ref(props.message.type === MessageTypes.TypingIndicator)

const store = useStore()

const avatarStyle = computed(() => {
    let style = {}
    if (props.message.from === 'chatbot') {
        style.backgroundColor = store.state.config.mainColor
    }
    return style
})

const icon = computed(() => {
    if (props.message.additionalParameters?.icon) {
        return store.state.config.icons[props.message.additionalParameters.icon]
    } else if (props.message.from === 'visitor') {
        return store.state.config.icons.user
    } else {
        return store.state.config.icons.bot
    }
})

onMounted(() => {
    setTimeout(() => {
        visible.value = props.message.type !== MessageTypes.TypingIndicator 
    }, (props.message.timeout || 0) * 1000)
})
</script>