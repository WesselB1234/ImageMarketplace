<?php require __DIR__."../../Partials/header.php"; ?> 

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow mb-4">

        <div class="navbar-brand" href="#">Image Marketplace</div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="/portfolio">Portfolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/images">Images</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/users">Users</a>
                </li>
            </ul>
            <div class="form-inline my-2 my-lg-0">
                <div class="text-start nav-link">Image tokens: 1000</div>
                <a class="btn btn-danger text-light" >Logout as testman</a>
            </div>
        </div>
    </nav>
</header>