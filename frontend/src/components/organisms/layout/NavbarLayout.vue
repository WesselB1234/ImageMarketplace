<script setup>
    import { onMounted, ref, computed } from 'vue'
    import { useAuthStore } from '@/stores/authStore.js'

    import NavBrand from '@/components/atoms/nav/NavBrand.vue'
    import NavToggler from '@/components/atoms/nav/NavToggler.vue'
    import NavMenu from '@/components/molecules/nav/NavMenu.vue'
    import UserInfo from '@/components/molecules/nav/UserInfo.vue'

    const authStore = useAuthStore()
    const loggedInUser = ref(null)
    const decodedAuthToken = ref(null)

    onMounted(async () => {
        loggedInUser.value = await authStore.getLoggedInUser()
        decodedAuthToken.value = authStore.decodedAuthToken
    })

    const isAdmin = computed(() => decodedAuthToken.value?.data.role === 'Admin')
</script>

<template>
    <nav class="navbar navbar-expand-xl navbar-light bg-light shadow mb-4">
        <NavBrand />
        <NavToggler />

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <NavMenu :isAdmin="isAdmin" />
            <UserInfo :imageTokens="loggedInUser?.imageTokens":username="loggedInUser?.username" />
        </div>
    </nav>
</template>