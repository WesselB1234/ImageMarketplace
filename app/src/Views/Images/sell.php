<?php 
    $title = "Sell image";
    $partialsDir = __DIR__."../../Partials";
    
    require $partialsDir."/navbarHeader.php"; 
?>

<main class="container">

    <h1>Sell image</h1>

    <?php 
        include $partialsDir."/errorAlert.php";
    ?>

    <a href="/images/details/<?php echo $viewModel->image->imageId; ?>" class="btn btn-secondary">Return back to image details page</a>

    <form class="mt-4" action="/images/upload" method="post">
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="Enter selling price" required value="<?php 
                echo (isset($viewModel->price) ? htmlspecialchars($viewModel->name, ENT_QUOTES, "UTF-8") : "")
            ?>">
        </div>

        <button type="submit" class="btn btn-danger">Sell</button>
    </form>
</main>

<?php require $partialsDir."/footer.php"; ?>
