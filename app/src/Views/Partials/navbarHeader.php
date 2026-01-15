<?php
    use App\Models\Helpers\StringFormatter;
    use App\Models\Enums\UserRole;

    require __DIR__."../../Partials/header.php"; 
?> 

<header>
    <nav class="navbar navbar-expand-xl navbar-light bg-light shadow mb-4">

        <div class="navbar-brand" href="#">Image Marketplace</div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-link">
                    <a class="nav-btn <?php echo ($enabledNavLink === "Portfolio" ? "nav-enabled" : "") ?>" href="/portfolio">Portfolio</a>
                </li>
                <li class="nav-link">
                    <a class="nav-btn <?php echo ($enabledNavLink === "Images" ? "nav-enabled" : "") ?>" href="/images">Images on sale</a>
                </li>
                <li class="nav-link">
                    <a class="nav-btn <?php echo ($enabledNavLink === "Privacy" ? "nav-enabled" : "") ?>" href="/privacy">Privacy</a>
                </li>
                <?php if ($_SESSION["user"]->getRole() === UserRole::Admin){?>
                    <li class="nav-link">
                        <a class="nav-btn <?php echo ($enabledNavLink === "Users" ? "nav-enabled" : "") ?>" href="/users">Users</a>
                    </li>
                <?php } ?>
            </ul>
            <div class="form-inline my-2 my-lg-0">
                <div class="nav-link">
                    Image tokens balance: <?php echo StringFormatter::getDottedNumberStringFromNumber($_SESSION["user"]->getImageTokens()); ?> |
                    Logged in as: <?php echo StringFormatter::getStringWithoutHtmlElements($_SESSION["user"]->getUsername()); ?>
                </div>
                <a href="/logout" class="btn btn-danger text-light">Logout</a>
            </div>
        </div>
    </nav>
</header>