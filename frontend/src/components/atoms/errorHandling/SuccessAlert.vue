<script setup>
    const props = defineProps({
        message: {
            type: String
        },
    })
</script>

<template>
    <div class="alert alert-success" role="alert">{{ message }}</div>
</template>
