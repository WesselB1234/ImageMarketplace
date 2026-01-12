<?php 
    $title = "Upload image";
    $partialsDir = __DIR__."../../Partials";
    
    require $partialsDir."/navbarHeader.php"; 
?>

<main class="container">

    <h1>Upload image</h1>

    <?php 
        include $partialsDir."/errorAlert.php";
    ?>

    <a href="/portfolio" class="btn btn-secondary">Return back to portfolio</a>

    <form class="mt-4" action="/images/upload" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Image name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter image name" required value="<?php 
                echo (isset($viewModel) ? htmlspecialchars($viewModel->getName(), ENT_QUOTES, "UTF-8") : "")
            ?>">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description" placeholder="Enter description" required value="<?php 
                echo (isset($viewModel) ? htmlspecialchars($viewModel->getDescription(), ENT_QUOTES, "UTF-8") : "")
            ?>">
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image file (extension must be .png)</label>
            <input type="file" id="image" class="form-control" name="image" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label for="alt_text" class="form-label">Alt text (shows if image is not able to load on the screen)</label>
            <textarea class="form-control" id="alt_text" name="alt_text" rows="2"><?php echo (isset($viewModel) ? htmlspecialchars($viewModel->getAltText(), ENT_QUOTES, "UTF-8") : "")?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Upload</button>
    </form>
</main>

<?php require $partialsDir."/footer.php"; ?>
