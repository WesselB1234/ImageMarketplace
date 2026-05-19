<script setup>
    const props = defineProps({
        text: {
            type: String,
        },
        to: {
            type: String,
        }
    })
</script>

<template>
    <router-link :to="to" class="btn btn-secondary mb-4">{{ text }}</router-link>
</template>
