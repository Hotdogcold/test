<?php include "db_conn.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>HIHI</h1>
    
    <?php
    $sql = "SELECT * FROM products WHERE image_path IS NOT NULL AND image_path != ''";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        while ($images = mysqli_fetch_assoc($res)) { ?>

        <div class="alb">
            <img src="<?=$images['image_path']?>">
        </div>

    <?php } } else {
        echo "No images available.";
    } ?>
</body>
</html>
