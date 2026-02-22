<?php
    use App\Models\Helpers\StringFormatter;
?>

<div class="alert alert-danger <?php echo (!isset($errorMessage) ? "d-none" : "") ?>" id="errorAlert" role="alert">
    An error has occured: <span id="errorMessageHolder"><?php echo (isset($errorMessage) ? StringFormatter::getStringWithoutHtmlElements($errorMessage) : "") ?></span>
</div>