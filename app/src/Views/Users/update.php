<?php
    use App\Models\Enums\UserRole;
    
    $title = "Update user";
    $enabledNavLink = "Users";
    $partialsDir = __DIR__."../../Partials";
    
    require $partialsDir."/navbarHeader.php"; 
?>

<main class="container">

    <h1>Update User: #<?php echo $viewModel->userId; ?></h1>

    <?php include $partialsDir."/errorAlert.php";?>

    <a href="/users" class="btn btn-secondary">Return back to users</a>
    
    <form action="/users/update/<?php echo $viewModel->userId ?>" method="post" class="mt-4">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required value="<?php 
                echo (isset($viewModel) && $viewModel->username !== null ? htmlspecialchars($viewModel->username, ENT_QUOTES, "UTF-8") : "") 
            ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required value="<?php 
                echo (isset($viewModel) && $viewModel->email !== null ? htmlspecialchars($viewModel->email, ENT_QUOTES, "UTF-8") : "") 
            ?>">
        </div>

        <div class="mb-3">
            <label for="image_tokens" class="form-label">Image tokens</label>
            <input type="number" class="form-control" id="image_tokens" name="image_tokens" min="0" placeholder="Enter number of tokens" required value="<?php 
                echo (isset($viewModel) && $viewModel->imageTokens !== null ? $viewModel->imageTokens : "") 
            ?>">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <?php foreach (UserRole::cases() as $case){ ?>
                    <option value="<?php echo $case->name; ?>" <?php echo (isset($viewModel) && isset($viewModel->role) && $case->name == $viewModel->role->value ? "selected" : "") ?>>
                        <?php echo $case->name; ?>
                    </option>
                <?php } ?>  
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</main>

<?php require $partialsDir."/footer.php"; ?>
