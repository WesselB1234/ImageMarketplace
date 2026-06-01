<script setup>
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import SubmitBtn from '@/components/atoms/buttons/forms/SubmitBtn.vue'

    const props = defineProps({
        vModel: {
            type: Object,
            required: true
        }
    });
</script>

<template>
    <form @submit="handleSell">
        <BaseFormField labelName="Price" type="number" id="price" placeholder="Price" v-model="vModel.price"/>
        <SubmitBtn text="Sell" />
    </form>
</template>