<script setup>
    import LogoutButton from '@/components/atoms/nav/LogoutBtn.vue'

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
            Image tokens balance: {{ imageTokens }}
            Logged in as: {{ username }}
        </div>
        <LogoutButton />
    </div>
</template>