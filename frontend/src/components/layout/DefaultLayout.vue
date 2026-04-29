<script setup>
    import NavbarLayout from '@/components/organisms/layout/NavbarLayout.vue'
    import FooterLayout from '@/components/organisms/layout/FooterLayout.vue'
</script>

<template>

    <NavbarLayout />

    <main class="container">
        <router-view></router-view>
    </main>

    <FooterLayout />
</template>