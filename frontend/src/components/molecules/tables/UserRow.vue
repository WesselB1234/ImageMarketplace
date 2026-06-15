<script setup>
    import { getPriceFormatted } from '@/utils/stringFormatter'
    import axios from '@/utils/axios.js'
    
    const props = defineProps({
        errorAlertRef: {
            type: Object,
            required: true
        },
        successAlertRef: {
            type: Object,
            required: true
        },
        user: {
            type: Object,
            required: true
        }
    });

    async function handleUserDelete(e) {

        try {
            const userId = e.target.dataset.userId;

            await axios.delete('/users/' + userId)

            e.target.parentElement.parentElement.remove()
            props.successAlertRef.displaySuccessMessage('Successfully deleted user with id: #' + userId)
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