<?php 
    include 'components/connect.php';

    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
        header('location: home.php');
    }

    $count_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id=?");
    $count_bookmark->execute([$user_id]);
    $total_bookmarks = $count_bookmark->rowCount();

    $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id=?");
    $count_likes->execute([$user_id]);
    $total_likes = $count_likes->rowCount();

    $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id=?");
    $count_comments->execute([$user_id]);
    $total_comments = $count_comments->rowCount();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="css/style.css">
    <title>Profile | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- profile section begins here -->
    <section class="tutor-profile">
        <h1 class="heading">profile details</h1>

        <div class="details">
            <div class="tutor">
                <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
                <h3><?= $fetch_profile['name']; ?></h3>
                <p><?= $fetch_profile['email']; ?></p>
                <span>student</span>
                <a href="update.php" class="inline-btn">update profile</a>
            </div>
        
            <div class="box-container">
                <div class="box">
                    <h3><?= $total_bookmarks; ?></h3>
                    <p>total bookmarks</p>
                    <a href="bookmark.php" class="btn">total bookmarked</a>
                </div>

                <div class="box">
                    <h3><?= $total_likes; ?></h3>
                    <p>total likes</p>
                    <a href="likes.php" class="btn">total liked</a>
                </div>

                <div class="box">
                    <h3><?= $total_comments; ?></h3>
                    <p>total comments</p>
                    <a href="comments.php" class="btn">total commented</a>
                </div>
            </div>
        </div>
    </section>
    <!-- profile section ends here -->

    <!-- footer section starts here -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends here -->
    <script src="javascript/script.js"></script>
</body>
</html>