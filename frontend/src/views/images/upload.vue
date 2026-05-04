<script setup>
    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue';
    import UploadImageForm from '@/components/organisms/forms/images/UploadImageForm.vue'


    console.log(import.meta.env.VITE_BACK_END_URL)
    const imgUrl = import.meta.env.VITE_BACK_END_URL + import.meta.env.VITE_USER_UPLOADED_IMAGES_URL + '/29.png'
</script>

<template>

    <h1>Upload image</h1>

    <ReturnBtn route="/auth/login" buttonText="Return back to portfolio" />
    <UploadImageForm />
    <img :src="imgUrl" alt="">
</template>
