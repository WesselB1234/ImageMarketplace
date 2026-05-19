<template>

    <h1>Users</h1>

    success 
    fail

    <a class="btn btn-success" href="users/create">Create new user</a>

    <table class="table mt-4">
        <thead class="table-dark">
            <tr>
                <th scope="col">User ID</th>
                <th scope="col">Username</th>
                <th scope="col">Image tokens</th>
                <th scope="col">Role</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- < foreach($viewModel as $user){ ?>
                <tr>
                    <th scope="row">< echo $user->getUserId(); ?></th>
                    <td>< echo StringFormatter::getStringWithoutHtmlElements($user->getUsername()); ?></td>
                    <td>< echo StringFormatter::getDottedNumberStringFromNumber($user->getImageTokens()); ?></td>
                    <td>< echo $user->getRole()->value; ?></td>
                    <td>
                        <a href="users/update/<?php echo $user->getUserId() ?>" class="btn btn-primary">Update</a> |
                        <button class="btn btn-danger user-deletion-btn" data-user-id="<?php echo $user->getUserId(); ?>">Delete</button>
                    </td>
                </tr>
            < }?> -->
        </tbody>
    </table>

    <!-- <script src="assets/js/AlertMessaging.js"></script>
    <script src="assets/js/UserDeletionAjax.js"></script> -->
</template>
