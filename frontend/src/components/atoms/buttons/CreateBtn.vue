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
    <RouterLink :to="to" class="btn btn-success">{{ text }}</RouterLink>
</template>
