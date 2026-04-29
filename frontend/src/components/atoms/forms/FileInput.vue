<script setup>
    const props = defineProps({
        id: {
            type: String
        },
        name: {
            type: String
        },
        accept: {
            type: String
        },
        modelValue: {
            type: String
        },
        isRequired: {
            type: Boolean,
            default: true
        }
    })

    const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <input type="file" :id="id" class="form-control" :name="name" :accept="accept" :value="props.modelValue" @input="emit('update:modelValue', $event.target.value)" :required="props.isRequired" />
</template>