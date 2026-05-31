<script setup>
    import { computed } from 'vue';
    import router from '@/router/index.js'

    const props = defineProps({
        to: { 
            type: String, 
            required: true 
        },
        text: { 
            type: String, 
            required: true 
        },
    })

    console.log(router.currentRoute.value.fullPath)

    const navEnabled = computed(() => {
        if (router.currentRoute.value.fullPath === props.to) {
            return 'nav-enabled'
        }

        return ''
    })
</script>

<template>
    <li class="nav-link">
        <RouterLink :class="'nav-btn ' + navEnabled" :to="to">{{ text }}</RouterLink>
    </li>
</template>