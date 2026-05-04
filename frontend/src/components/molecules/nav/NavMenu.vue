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
        <NavLink href="/portfolio" label="Portfolio" />
        <NavLink href="/images" label="Images on sale" />
        <NavLink href="/privacy" label="Privacy" />
        <NavLink href="/settings" label="Settings" />
        <NavLink v-if="isAdmin" href="/users" label="Users" />
    </ul>
</template>