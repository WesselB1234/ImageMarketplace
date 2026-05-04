<script setup>

</script>

<template>
     <nav class="navbar navbar-expand-xl navbar-light bg-light shadow mb-4">

        <div class="navbar-brand" href="#">Image Marketplace</div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-link">
                    <a class="nav-btn" href="/portfolio">Portfolio</a>
                </li>
                <li class="nav-link">
                    <a class="nav-btn" href="/images">Images on sale</a>
                </li>
                <li class="nav-link">
                    <a class="nav-btn" href="/privacy">Privacy</a>
                </li>
                <li class="nav-link">
                    <a class="nav-btn" href="/settings">Settings</a>
                </li>
                <!-- <?php if ($this->loggedInUser->getRole() === UserRole::Admin){?> -->
                    <li class="nav-link">
                        <a class="nav-btn" href="/users">Users</a>
                    </li>
                <!-- <?php } ?> -->
            </ul>
            <div class="form-inline my-2 my-lg-0">
                <div class="nav-link">
                    Image tokens balance:
                    Logged in as: 
                </div>
                <a href="/logout" class="btn btn-danger text-light">Logout</a>
            </div>
        </div>
    </nav>
</template>