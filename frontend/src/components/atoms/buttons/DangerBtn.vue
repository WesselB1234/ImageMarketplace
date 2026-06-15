<script setup>
    defineProps({
        text: {
            type: String,
        },
    })
</script>

<template>
    <button class="btn btn-danger">{{ text }}</button>
</template>