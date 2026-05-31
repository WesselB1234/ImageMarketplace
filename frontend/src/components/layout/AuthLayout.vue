<script setup lang="ts">
import { RouterView } from 'vue-router';

</script>

<template>
    <main class="container">
        <section class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card shadow-sm authentication-box">
                <div class="card-body">
                    <RouterView></RouterView>
                </div>
            </div>
        </section>
    </main>
</template>