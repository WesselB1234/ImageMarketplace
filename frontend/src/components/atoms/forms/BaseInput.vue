<script setup>
const props = defineProps({
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
    },
    modelValue: {
        type: String,
    }
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <input class="form-control" :type="type" :id="id" :name="name" :placeholder="placeholder" :value="props.modelValue" @input="emit('update:modelValue', $event.target.value)"/>
</template>