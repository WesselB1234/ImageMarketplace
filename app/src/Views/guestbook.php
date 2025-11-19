<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guestbook</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <form action="/guestbook" method="post" class="p-3">

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email (optional)</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com">
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <?php if (!empty($posts)) {?>
    <div class="messages-holder">
        <h1>Guestbook</h1>
        <?php foreach ($posts as $post) { ?>
            <div class="message-box">
                <h2>
                    <?= $post['name'] ?>
                </h2>
                <p>
                    <?= nl2br(str_replace('\n', "\n", $post["message"])); ?>
                <p>
                
                <i>
                    <?= "Posted at: " . $post['posted_at'] ?>
                </i>
            </div> 
        <?php } ?>
    </ul>
    <?php } 
    else { ?>
        <p>No entries found in the guestbook.</p>
    <?php } ?>
</body>
</html>