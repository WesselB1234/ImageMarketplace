<?php 
    $title = "Users";
    $enabledNavLink = "Users";
    $partialsDir = __DIR__."../../../Partials";
    
    require $partialsDir."/navbarHeader.php"; 
?>

<main class="container">

    <h1>Users</h1>

    <?php 
        include $partialsDir."/errorAlert.php";
        include $partialsDir."/successAlert.php"; 
    ?>

    <a class="btn btn-success" href="users/create">Create new user</a>

    <table class="table mt-4">
        <thead class="table-dark">
            <th scope="col">#</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Image tokens</th>
            <th scope="col">Role</th>
            <th scope="col">Actions</th>
        </thead>
        <tbody>
            <?php foreach($viewModel as $user){ ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($user->userId, ENT_QUOTES, 'UTF-8'); ?></th>
                    <td><?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->imageTokens, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->role->value, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="users/update/<?php echo htmlspecialchars($user->userId, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary">Update</a> |
                        <a href="users/delete/<?php echo htmlspecialchars($user->userId, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</main>

<?php require $partialsDir."/footer.php"; ?>
