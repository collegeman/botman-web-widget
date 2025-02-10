<template>
    <div 
        :class="[
            'shrink-0 p-2 relative',
            {
                'rounded-t-lg': !$store.state.config.isMobile
            }
        ]" 
        :style="{ 
            backgroundColor: $store.state.config.mainColor 
        }"
    >
        <div 
            class="flex-grow text-white text-sm text-center cursor-pointer"
            @click.prevent="emit('close')"
        >
            <slot name="content">
                {{ $store.state.title }}
            </slot>
        </div>
        <button 
            class="absolute left-2 top-1/2 -translate-y-1/2 outline-none text-white text-sm"
            @click.prevent="emit('back')"
        >
            <span class="icon block h-4 w-4" v-html="$store.state.config.icons.back"></span>
            <span class="sr-only">Back</span>
        </button>
        <button 
            :class="[
                'absolute right-2 top-1/2 -translate-y-1/2 outline-none text-white text-sm',
                {
                    'hidden': !$store.state.config.isMobile
                }
            ]"
            @click.prevent="emit('close')"
        >
            <span class="icon block h-4 w-4" v-html="$store.state.config.icons.close"></span>
            <span class="sr-only">Close</span>
        </button>
    </div>
</template>

<script setup>
const emit = defineEmits([
    'back', 
    'close',
])
</script>