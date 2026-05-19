<script setup>
import NavLink from '@/components/atoms/nav/NavLink.vue'

defineProps({
    isAdmin: { 
        type: Boolean, 
        default: false 
    },
})
</script>

<template>
    <ul class="navbar-nav mr-auto">
        <NavLink to="/portfolio" text="Portfolio" />
        <NavLink to="/images" text="Images on sale" />
        <NavLink to="/privacy" text="Privacy" />
        <NavLink to="/settings" text="Settings" />
        <NavLink v-if="isAdmin" to="/users" text="Users" />
    </ul>
</template>