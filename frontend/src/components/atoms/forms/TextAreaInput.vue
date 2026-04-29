<script setup>
    const props = defineProps({
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
        },
        isRequired: {
            type: Boolean,
            default: true
        }
    })

    const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <textarea class="form-control" :id="props.id" :name="props.name" :placeholder="props.placeholder" :value="props.modelValue" @input="emit('update:modelValue', $event.target.value)" :required="props.isRequired"/>
</template>