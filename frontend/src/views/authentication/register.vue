<script setup>
    import Nani from '../../components/atoms/Nani.vue'
</script>

<template>
    <main class="container">
        <section class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card shadow-sm authentication-box">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Register</h3>
                    <form action="/register" method="POST" id="registerForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>

                        <div class="mb-3">
                            <label for="repeat_password" class="form-label">Repeat password</label>
                            <input type="password" class="form-control" id="repeat_password" placeholder="Enter previous password" required>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </div>

                        <div class="d-grid">
                            <router-link to="/auth/login">Login into an existing account</router-link>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <Nani/>
</template>
