<template>
    <div>
        <textarea
            ref="textarea"
            class="block w-full border-none outline-none focus:outline-none focus:ring-0 active:outline-none resize-none text-sm rounded-b-lg placeholder:text-gray-300"
            @input="onInput"
            @keydown="onKeyDown"
            :placeholder="$store.state.config.placeholderText"
            v-model="$store.state.input.text"
        ></textarea>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const emit = defineEmits(['submit'])

const textarea = ref(null)

// automatically expand the height of the textarea when the user types
const onInput = (event) => {
    textarea.value.style.height = 'auto'
    textarea.value.style.height = textarea.value.scrollHeight + 'px'
}

const onKeyDown = (event) => {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault()
        emit('submit')
    }
}
</script>