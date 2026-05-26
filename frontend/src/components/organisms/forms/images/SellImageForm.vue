<script setup>
    import { ref } from 'vue'
    import axios from '@/utils/axios.js'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import router from '@/router'

    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import SubmitBtn from '@/components/atoms/buttons/forms/SubmitBtn.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'

    const props = defineProps({
        imageId: {
            type: String,
            required: true
        },
        errorAlertRef: {
            type: Object,
            required: true
        }
    })

    const errorHandlingStore = useErrorHandlingStore()
    const price = ref('')

    async function handleSell(e) {  
        try {
            e.preventDefault()

            await axios.patch('/images/sell/' + props.imageId, {
                "price": price.value
            })

            errorHandlingStore.successMessage = 'Successfully put image on sale.'
            router.push('/images/' + props.imageId) 
        }
        catch (ex){
            if (ex.response){
                props.errorAlertRef.displayErrorMessage(ex.response.data.message)
            }
            else {
                props.errorAlertRef.displayErrorMessage(ex.message)
            }
        }
    }
</script>

<template>
    <form @submit="handleSell">
        <BaseFormField labelName="Price" type="number" id="price" placeholder="Price" v-model="price"/>
        <SubmitBtn text="Sell" />
    </form>
</template>