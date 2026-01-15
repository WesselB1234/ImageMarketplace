<?php
    use App\Models\Helpers\StringFormatter;
?>

<div class="alert alert-success <?php echo (!isset($successMessage) ? "d-none" : "") ?>" id="successAlert" role="alert">
    <?php echo (isset($successMessage) ? StringFormatter::getStringWithoutHtmlElements($successMessage) : "") ?>
</div>