<script setup>
    defineProps({
        type: {
            type: String,
        },
        id: {
            type: String,
        },
        name: {
            type: String,
        },
        placeholder: {
            type: String,
        }
    })
</script>

<template>
    <input class="form-control" :type="type" :id="id" :name="name" :placeholder="placeholder">          
</template>