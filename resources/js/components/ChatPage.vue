<template>
    <div>
        <slot name="heading">
            
        </slot>
    </div>
</template>

<script setup>
const props = defineProps({
    id: {
        type: String,
        required: true
    }
})


</script>