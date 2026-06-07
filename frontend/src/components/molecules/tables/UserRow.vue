<script setup>
// script src="assets/js/UserDeletionAjax.js"script
    import { getPriceFormatted } from '@/utils/stringFormatter'

    const props = defineProps({
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
            <button class="btn btn-danger user-deletion-btn" :data-user-id="user.userId">Delete</button>
        </td>
    </tr>
</template>