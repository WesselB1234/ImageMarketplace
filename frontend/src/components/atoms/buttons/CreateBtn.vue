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
    <router-link :to="to" class="btn btn-success">{{ text }}</router-link>
</template>
