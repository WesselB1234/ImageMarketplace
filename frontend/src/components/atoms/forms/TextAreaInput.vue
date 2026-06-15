<script setup>
    const props = defineProps({
        id: {
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

    function onTextAreaInputChange(e){
        emit('update:modelValue', e.target.value)
    }
</script>

<template>
    <textarea class="form-control" :id="props.id" :placeholder="props.placeholder" :value="props.modelValue" @change="onTextAreaInputChange" :required="props.isRequired"/>
</template>