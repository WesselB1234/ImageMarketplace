<script setup>
    import { useAuthStore } from '@/stores/authStore.js'
    import { onMounted, ref } from 'vue'

    const authStore = useAuthStore()
    let loggedInUser = ref(null)
    let decodedAuthToken = ref(null)

    onMounted(async () => {
        const authStore = useAuthStore()
        loggedInUser.value = await authStore.getLoggedInUser()
        decodedAuthToken.value = authStore.decodedAuthToken
    })
</script>

<template>
     <nav class="navbar navbar-expand-xl navbar-light bg-light shadow mb-4">

        <div class="navbar-brand" href="#">Image Marketplace</div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-link">
                    <a class="nav-btn" href="/portfolio">Portfolio</a>
                </li>
                <li class="nav-link">
                    <a class="nav-btn" href="/images">Images on sale</a>
                </li>
                <li class="nav-link">
                    <a class="nav-btn" href="/privacy">Privacy</a>
                </li>
                <li class="nav-link">
                    <a class="nav-btn" href="/settings">Settings</a>
                </li>
                <li v-if="decodedAuthToken?.data.role === 'Admin'" class="nav-link">
                    <a class="nav-btn" href="/users">Users</a>
                </li>
            </ul>
            <div class="form-inline my-2 my-lg-0">
                <div class="nav-link">
                    Image tokens balance: {{ loggedInUser?.imageTokens }}
                    Logged in as: {{ loggedInUser?.username }}
                </div>
                <a href="/logout" class="btn btn-danger text-light">Logout</a>
            </div>
        </div>
    </nav>
</template>