<script setup>
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import SubmitBtn from '@/components/atoms/buttons/forms/SubmitBtn.vue'
    import FileFormField from '@/components/molecules/forms/FileFormField.vue'
    import TextAreaField from '@/components/molecules/forms/TextAreaField.vue'

    const props = defineProps({
        vModel: {
            type: Object,
            required: true
        }
    });
</script>

<template>
    <form>
        <BaseFormField labelName="Image name" id="name" placeholder="Enter image name" v-model="vModel.name"/>
        <FileFormField labelName="Image file (extension must be .png)" id="image" accept="image/*" v-model="vModel.image"/>
        <TextAreaField labelName="Description" id="description" placeholder="Enter description" v-model="vModel.description"/>
        <TextAreaField labelName="Alt text (shows if image is not able to load on the screen)" id="alt_text" placeholder="Enter alt text" v-model="vModel.altText"/>
        <SubmitBtn text="Upload" />
  </form>
</template>