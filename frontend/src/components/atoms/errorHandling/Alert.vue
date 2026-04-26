<script setup>
    import { ref } from 'vue'

    const displayer = ref('d-none')
    const message = ref('')

    const props = defineProps({
        classType: {
            type: String
        }
    })

    function displayAlertMessage(newMessage){
        displayer.value = ''
        message.value = newMessage
    }

    function handleDismissClick(){
        displayer.value = 'd-none'
    }
    
    defineExpose({
        displayAlertMessage
    })
</script>

<template>
    <div :class="'alert alert-' + props.classType + ' ' + displayer" role="alert">
        {{ message }}
        <button @click="handleDismissClick" type="button" class="close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>
