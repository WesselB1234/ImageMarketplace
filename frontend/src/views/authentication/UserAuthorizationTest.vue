<script setup>  
    import { onMounted, ref } from 'vue'
    import axios, { getAuthToken } from "@/utils/axios.js"
    import { jwtDecode } from "jwt-decode"

    const user = ref('')

    onMounted(async () => {
        try {
            const response = await axios.get('/auth/user-test')

            console.log(response.data)
            user.value = response.data
        }
        catch (ex){
            console.log(ex.response.data)
        }
    })
</script>

<template>
    user authorization 
    <br>
    {{ JSON.stringify(user, null, 2) }}
    <br>
    <br>
    jwt 
    <br>
    {{ JSON.stringify(jwtDecode(getAuthToken(), null, 2)) }}
</template>
