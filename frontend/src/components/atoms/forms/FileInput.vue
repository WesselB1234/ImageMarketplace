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
            type: File
        },
        isRequired: {
            type: Boolean,
            default: true
        }
    })

    const emit = defineEmits(['update:modelValue'])
function onChange(e) {
  const file = e.target.files?.[0] || null
  emit('update:modelValue', file)
}
</script>

<template>
  <input
    type="file"
    :id="id"
    class="form-control"
    :name="name"
    :accept="accept"
    @change="onChange"
    :required="isRequired"
  />
</template>