<script setup>
    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue';
import UploadImageForm from '@/components/organisms/forms/images/UploadImageForm.vue'

</script>

<template>

    <h1>Upload image</h1>

    <ReturnBtn route="/auth/login" buttonText="Return back to portfolio" />
    <UploadImageForm />
</template>
