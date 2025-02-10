<template>
    <div class="shrink-0 p-2 flex items-center justify-between rounded-t-lg" :style="{ backgroundColor: $store.state.config.mainColor }">
        <button class="outline-none text-white text-sm">
            
            <span class="sr-only">Back</span>
        </button>
        <div class="flex-grow text-white text-sm text-center">
            <slot name="content">
                {{ $store.state.title }}
            </slot>
        </div>
        <span>

        </span>
    </div>
</template>
