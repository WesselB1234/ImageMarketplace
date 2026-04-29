<script setup>
    const props = defineProps({
        buttonText: {
            type: String,
        },
        route: {
            type: String,
        }
    })
</script>

<template>
    <router-link :to="props.route" class="btn btn-secondary mb-4">{{ props.buttonText }}</router-link>
</template>
