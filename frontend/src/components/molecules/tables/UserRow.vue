<script setup>
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
        <td scope="row">{{ user.userId }}</td>
        <td>{{ user.username }}</td>
        <td>{{ getPriceFormatted(user.imageTokens) }}</td>
        <td>{{ user.role }}</td>
        <td>
            <a href="users/update/<?php echo $user->getUserId() ?>" class="btn btn-primary">Update</a> |
            <button class="btn btn-danger user-deletion-btn" data-user-id="<?php echo $user->getUserId(); ?>">Delete</button>
        </td>
    </tr>
</template>