<script setup>
    import { onMounted, ref, watch } from 'vue'
    import { useErrorHandlingStore } from "@/stores/errorHandlingStore.js"

    const errorHandlingStore = useErrorHandlingStore();

    const displayer = ref('d-none')
    const successMessage = ref('')

    function displaySuccessMessage(){
        displayer.value = ''
        successMessage.value = errorHandlingStore.successMessage
        errorHandlingStore.successMessage = null
    }

    function handleDismissClick(){
        displayer.value = 'd-none'
    }

    onMounted(() => {

        if (errorHandlingStore.successMessage) {
            displaySuccessMessage()
        }

        watch(
            () => errorHandlingStore.successMessage,
            (newValue) => {
                if (!newValue) {
                    return
                }
                
                displaySuccessMessage()
            }
        )
    })
</script>

<template>
    <div :class="'alert alert-success ' + displayer" role="alert">
        {{ successMessage }}
        <button @click="handleDismissClick" type="button" class="close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>
