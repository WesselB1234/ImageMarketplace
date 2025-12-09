<?php 
    use App\Models\User;

    $title = "Users";
    
    require __DIR__."../../../Partials/navbarHeader.php"; 
?>

<main class="container">

    <h1>Users</h1>

    <a class="btn btn-success" href="users/create">Create new user</a>

    <table class="table mt-4">
        <thead class="table-dark">
            <th scope="col">#</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Image tokens</th>
            <th scope="col">Actions</th>
        </thead>
        <tbody>
            <?php foreach($viewModel as $user){ ?>
                <tr>
                    <th scope="row"><?php echo $user["id"]; ?></th>
                    <td><?php echo $user["username"]; ?></td>
                    <td><?php echo $user["email"]; ?></td>
                    <td><?php echo $user["image_tokens"]; ?></td>
                    <td>
                        <a href="users/update/<?php echo $user["id"]; ?>" class="btn btn-primary">Update</a> |
                        <a href="users/delete/<?php echo $user["id"]; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</main>

<?php require __DIR__."../../../Partials/footer.php"; ?>
