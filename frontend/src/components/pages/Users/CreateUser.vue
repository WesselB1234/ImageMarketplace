
<template>

    <h1>Create User</h1>

    <include api="/errorAlert" />

    <a href="/users" class="btn btn-secondary">Return back to users</a>
    
    <form action="/users/create" method="post" class="mt-4">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
        </div>

        <div class="mb-3">
            <label for="image_tokens" class="form-label">Image tokens</label>
            <input type="number" class="form-control" id="image_tokens" name="image_tokens" min="0" placeholder="Enter number of tokens" required >
        </div>

        <!-- <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                foreach (UserRole::cases() as $case){ ?>
                    <option value="<?php echo $case->name; ?>"  echo (isset($viewModel) && $case->name == $viewModel->getRole()->value ? "selected" : "") ?>>
                         echo $case->name; ?>
                    </option>
                } ?>  
            </select>
        </div> -->

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</template>
