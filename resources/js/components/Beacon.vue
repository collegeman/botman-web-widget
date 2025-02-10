<template>
    <button 
        class="block w-full rounded-full text-white p-4 focus:outline outline-4 outline-offset-2 outline-blue-500"
        :style="style"
        @click.prevent="toggleChat"
    > 
        <span v-if="open" v-html="config.icons.open"></span>
        <span v-if="!open" v-html="config.icons.closed"></span>
        <span class="sr-only">Click to Chat</span>
    </button>
</template>

<script setup>
import { ref, computed } from 'vue'

let open = ref(false)

let config = ref(window.botmanWidget)

let style = computed(() => {
    return {
        backgroundColor: config.value.beaconColor,
        width: config.value.beaconSize + 'px',
        height: config.value.beaconSize + 'px',
    }
})

let toggleChat = () => {
    window.parent.postMessage({
        type: 'botman-web-widget.beacon.click',
        data: {
            //
        }
    })
}

window.addEventListener('message', (event) => {
    if (event.data?.type === 'botman-web-widget.widget.toggle') {
        open.value = event.data.data.open
    }
})
</script>

<style scoped>
span svg {
    display: block;
    width: 100%;
    height: 100%;
}
:hover {
    background-color: none !important;
}
:active {
    scale: 0.95;
}
</style>