<template>
    <main class="container" >
        <section class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card shadow-sm authentication-box">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Login</h3>

                    <form action="/login" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </div>

                        <div class="d-grid">
                            <router-link to="/register">Register a new account</router-link>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</template>
