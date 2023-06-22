<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        header('location:login.php');
    }

    $count_content = $conn->prepare("SELECT * FROM `content` WHERE tutor_id=?");
    $count_content->execute([$tutor_id]);
    $total_contents = $count_content->rowCount();

    $count_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id=?");
    $count_playlist->execute([$tutor_id]);
    $total_playlists = $count_playlist->rowCount();

    $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id=?");
    $count_likes->execute([$tutor_id]);
    $total_likes = $count_likes->rowCount();

    $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id=?");
    $count_comments->execute([$tutor_id]);
    $total_comments = $count_comments->rowCount();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>
    
    <!-- dashboard section begins -->
    <section class="dashboard">
        <h1 class="heading">tutor dashboard</h1>

        <div class="box-container">
            <div class="box">
                <h3>welcome!</h3>
                <p><?= $fetch_profile['name']; ?></p>
                <a href="profile.php" class="btn">view profile</a>
            </div>

            <div class="box">
                <h3><?= $total_contents; ?></h3>
                <p>contents uploaded</p>
                <a href="add_content.php" class="btn">add new content</a>
            </div>

            <div class="box">
                <h3><?= $total_playlists; ?></h3>
                <p>playlists uploaded</p>
                <a href="add_playlist.php" class="btn">add new playlist</a>
            </div>

            <div class="box">
                <h3><?= $total_likes; ?></h3>
                <p>total likes</p>
                <a href="contents.php" class="btn">view contents</a>
            </div>

            <div class="box">
                <h3><?= $total_comments; ?></h3>
                <p>total comments</p>
                <a href="comments.php" class="btn">view comments</a>
            </div>

            <div class="box">
                <h3>quick links</h3>
                <p>login or register</p>
                <div class="flex-btn">
                    <a href="../components/admin_logout.php" class="option-btn">logout</a>
                    <a href="admin-video-conf/lobby.php" class="option-btn">video room</a>
                </div>
            </div>
        </div>
    </section>
    <!-- dashboard section ends -->



    <!-- footer section -->
    <?php include '../components/footer.php'; ?>
    <!-- custome js -->
    <script src="../javascript/admin-script.js"></script>
</body>
</html>