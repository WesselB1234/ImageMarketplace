<script setup>
    import { computed } from 'vue'
    import BaseInput from '@/components/atoms/forms/BaseInput.vue';

    const props = defineProps({
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
        },
        modelValue: {
            type: String,
        }
    })

    const emit = defineEmits(['update:modelValue'])

    const value = computed({
        get: () => props.modelValue,
        set: (val) => emit('update:modelValue', val)
    })
</script>

<template>
    <div class="mb-3">
        <label :for="id" class="form-label">{{ labelName }}</label>
        <BaseInput v-model="value" :type="props.type" :id="props.id" :name="props.name" :placeholder="props.placeholder" />
    </div>
</template>