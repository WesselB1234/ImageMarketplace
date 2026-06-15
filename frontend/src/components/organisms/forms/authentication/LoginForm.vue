<script setup>
    import AuthsubmitBtn from '@/components/atoms/buttons/forms/AuthsubmitBtn.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'

    const props = defineProps({
        vModel: {
            type: Object,
            required: true
        }
    });
</script>

<template>
    <form>
        <BaseFormField labelName="Username" id="username" placeholder="Enter your username" v-model="vModel.username"/>
        <BaseFormField labelName="Password" type="password" id="password" placeholder="Enter your password" v-model="vModel.password"/>
        <AuthsubmitBtn text="Login" />
  </form>
</template>