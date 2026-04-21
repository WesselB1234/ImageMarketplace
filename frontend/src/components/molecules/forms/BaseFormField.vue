<script setup lang="ts">
    import BaseInput from '@/components/atoms/forms/BaseInput.vue';

    defineProps({
        labelName: {
            type: String
        },
        type: {
            type: String,
            default: 'text'
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
    <div class="mb-3">
        <label :for="id" class="form-label">{{ labelName }}</label>
        <BaseInput :type="type" :id="id" :name="name" :placeholder="placeholder" />
    </div>
</template>