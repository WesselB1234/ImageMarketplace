<script setup>
    import LogoutButton from '@/components/atoms/nav/LogoutBtn.vue'
    import { getPriceFormatted } from '@/utils/stringFormatter'

    defineProps({
        imageTokens: { 
            type: Number, 
            default: null 
        },
        username: { 
            type: String, 
            default: null 
        },
    })
</script>

<template>
    <div class="form-inline my-2 my-lg-0">
        <div class="nav-link">
            Image tokens balance: {{ imageTokens ? getPriceFormatted(imageTokens) : 'Loading...' }}
            Logged in as: {{ username }}
        </div>
        <LogoutButton />
    </div>
</template>