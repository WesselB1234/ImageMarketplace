<script setup>
    import { getPriceFormatted } from '@/utils/stringFormatter'
    import axios from '@/utils/axios.js'

    async function handleUserDelete(e) {

        try {
            const userId = e.target.dataset.userId;

            await axios.delete('/users/delete/' + userId)
            e.target.parentElement.parentElement.remove()
        }
        catch (ex){
            if (ex.response){
                errorAlertRef.value.displayErrorMessage(ex.response.data.message)
            }
            else {
                errorAlertRef.value.displayErrorMessage(ex.message)
            }
        }
    }

    defineProps({
        user: {
            type: Object,
            required: true
        }
    });
</script>
<template>
    <tr>
        <td>{{ user.userId }}</td>
        <td>{{ user.username }}</td>
        <td>{{ getPriceFormatted(user.imageTokens) }}</td>
        <td>{{ user.role }}</td>
        <td>
            <RouterLink :to="'users/update/' + user.userId" class="btn btn-primary">Update</RouterLink> |
            <button class="btn btn-danger user-deletion-btn" @click="handleUserDelete" :data-user-id="user.userId">Delete</button>
        </td>
    </tr>
</template>