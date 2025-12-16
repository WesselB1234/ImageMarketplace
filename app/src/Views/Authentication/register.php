<?php 
    $title = "Register";
    $partialsDir = __DIR__."../../Partials";
    
    require $partialsDir."/header.php"; 
?>

<main class="container">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm" style="width: 22rem;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Register</h3>
                <?php include $partialsDir."/errorAlert.php";?>
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
                        <a href="/login">Login into an existing account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="assets/js/AlertMessaging.js"></script>
<script src="assets/js/Register.js"></script>