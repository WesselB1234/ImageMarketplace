<?php 
    $title = "Privacy";
    $enabledNavLink = "Privacy";
    $partialsDir = __DIR__."../../Partials";
    
    require $partialsDir."/navbarHeader.php"; 
?>

<main class="container">
    
    <h1>Privacy</h1>

    <p class="text-muted">
        Last updated: 12-1-2026
    </p>

    <p>
        This Privacy Policy describes how
        <span class="fw-bold">Image Marketplace</span>
        collects and uses information
        about you when you use our website.
    </p>

    <hr class="my-4">

    <h2 class="h4 mt-4">1. Information we collect</h2>
    <p>
        We collect various types of information in connection with the Service, including:
    </p>
    <ul>
        <li>
            <span class="fw-bold">Account information:</span>
            Username, password, image tokens balance, role, and owned images.
        </li>
        <li>
            <span class="fw-bold">Files:</span>
            Uploaded images will be placed in our file system.
        </li>
    </ul>

    <hr class="my-4">

    <h2 class="h4 mt-4">2. How we use your information</h2>
    <p>
        We use the information we collect for purposes including:
    </p>
    <ul>
        <li>
            <span class="fw-bold">Authentication</span>
            We use your account credentials (such as email and password) to verify your identity and allow you to securely sign in to the Service.
        </li>
        <li>
            <span class="fw-bold">Authorization</span>
            We use your account information and assigned permissions to determine what actions, features, and resources you are allowed to access within the Service.
        </li>
        <li>
            <span class="fw-bold">Running the image marketplace</span>
            We process information related to your uploads, purchases, and sales to operate, maintain, and improve the functionality of the image marketplace.
        </li>
    </ul>

    <hr class="my-4">

    <h2 class="h4 mt-4">3. How we share your information</h2>
    <p>
        We do not sell your personal information to third parties.
    </p>

    <hr class="my-4">

    <h2 class="h4 mt-4">4. Cookies</h2>
    <p>
        We do not make use of cookies.
    </p>

    <hr class="my-4">

    <h2 class="h4 mt-4">5. Data retention</h2>
    <p>
        We retain information only as long as necessary or required by law.
    </p>

    <hr class="my-4">

    <h2 class="h4 mt-4">6. Data security</h2>
    <p>
        We implement reasonable safeguards but cannot guarantee absolute security.
    </p>

    <hr class="my-4">

    <h2 class="h4 mt-4">7. Your right to delete your account</h2>
    <p>
        You have the right to delete your account.
        You can delete your account by pressing the "Delete account" button on the settings page: <a href="/settings">Settings page</a>
    </p>
</main>

<?php require $partialsDir."/footer.php"; ?>
