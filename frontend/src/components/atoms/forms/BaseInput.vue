<script setup>
    const props = defineProps({
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
    <input class="form-control" :type="props.type" :id="props.id" :name="props.name" :placeholder="props.placeholder" :value="props.modelValue" @input="emit('update:modelValue', $event.target.value)" :required="props.isRequired"/>
</template>