<template>
    <div class="flex w-full">
        <div class="flex-shrink-0 mr-2">
            <div class="w-8 h-8 rounded-lg bg-gray-200 flex items-center justify-center">
                <span class="icon w-6 h-6">
                    <span v-html="$store.state.config.icons.user"></span>
                </span>
            </div>
        </div>
        <div class="py-2 px-4 bg-white rounded-lg text-sm mb-4 :last:mb-0">
            {{ message.text }}
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useStore } from 'vuex'

const props = defineProps({
    message: {
        type: Object,
        required: true
    }
})

const store = useStore()
</script>