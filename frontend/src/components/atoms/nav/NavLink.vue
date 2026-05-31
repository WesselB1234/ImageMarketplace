<script setup>
    import { computed } from 'vue';

    defineProps({
        to: { 
            type: String, 
            required: true 
        },
        text: { 
            type: String, 
            required: true 
        },
    })

    const navEnabled = computed(() => {
        
    })
</script>

<template>
    <li class="nav-link">
        <RouterLink class="nav-btn" :to="to">{{ text }}</RouterLink>
    </li>
</template>