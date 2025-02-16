<template>
    <div>
        <slot name="heading"></slot>
        <div 
            :class="[
                'relative w-full',
                {
                    'border-t-[50px]': $slots.heading
                },
            ]"
            :style="{ borderTopColor: $store.state.config.mainColor }"
        >
            <div 
                :class="[
                    'relative w-full',
                    {
                        '-top-[50px]': $slots.heading
                    },
                ]"
            >
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useStore } from 'vuex'

const store = useStore()

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        default: null,
    },
    description: {
        type: String,
        default: null,
    }
})

onMounted(() => {
    store.state.title = props.title
})
</script>