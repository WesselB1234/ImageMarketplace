<div class="alert alert-danger <?php echo (!isset($errorMessage) ? "d-none" : "") ?>" id="errorAlert" role="alert">
    An error has occured: <span id="errorMessageHolder"><?php echo (isset($errorMessage) ? $errorMessage : "") ?></span>
</div>