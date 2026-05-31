<script setup>
    defineProps({
        text: {
            type: String,
        },
    })
</script>

<template>
    <Button class="btn btn-danger">{{ text }}</Button>
</template>