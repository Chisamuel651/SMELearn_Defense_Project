<?php 
    include 'components/connect.php';

    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

    if(isset($_GET['get_id'])){
        $get_id = $_GET['get_id'];
    }else{
        $get_id = '';
        header('location: courses.php');
    }

    if(isset($_POST['like_content'])){
        if($user_id != ''){
            $like_id = $_POST['content_id'];
            $like_id = filter_var($like_id, FILTER_SANITIZE_STRING);

            $get_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
            $get_content->execute([$like_id]);
            $fetch_get_content = $get_content->fetch(PDO::FETCH_ASSOC);

            $tutor_id = $fetch_get_content['tutor_id'];

            $verify_like = $conn->prepare("SELECT * FROM `like` WHERE user_id = ? AND content_id = ?");
            $verify_like->execute([$user_id, $like_id]);

            if($verify_like->rowCount() > 0){
                $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND content_id = ?");
                $remove_likes->execute([$user_id, $like_id]);
                $message[] = 'removed like';
            }else{
                $add_likes = $conn->prepare("INSERT INTO `likes` (user_id, tutor_id, content_id) VALUES (?, ?, ?)");
                $add_likes->execute([$user_id, $tutor_id, $like_id]);
                $message[] = 'like added';
            }
        }else{
            $message[] = 'please login first!';
        }
    }
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
    <title>Watch Course Video | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- watch video tutorial begins here -->
    <section class="watch-video">
        <?php
            $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND status = ?");
            $select_content->execute([$get_id, 'active']);

            if($select_content->rowCount() > 0){
                while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
                    $content_id = $fetch_content['id'];
                    $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE content_id = ?");
                    $select_likes->execute([$content_id]);
                    $total_likes = $select_likes->rowCount();

                    $user_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
                    $user_likes->execute([$user_id, $content_id]);
                    // $total_user_likes = $user_likes->rowCount();

                    $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                    $select_tutor->execute([$fetch_content['tutor_id']]);
                    $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);


        ?>

        <div class="content">
            <video src="course_content_files/<?= $fetch_content['video']; ?>" controls autoplay poster="course_content_files/<?= $fetch_content['thumb']; ?>" class="video"></video>
            <h3 class="title"><?= $fetch_content['title']; ?></h3>
            <div class="info">
                <p><i class="fas fa-calendar"></i> <span><?= $fetch_content['date']; ?></span></p>
                <p><i class="far fa-heart"></i> <span><?= $total_likes; ?></span></p>
            </div>

            <div class="tutor">
                <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
                <div>
                    <h3><?= $fetch_tutor['name']; ?></h3>
                    <span><?= $fetch_tutor['profession']; ?></span>
                </div>
            </div>
    
            <form action="" method="post" class="flex">
                <input type="hidden" name="content_id" value="<?= $content_id; ?>">
                <a href="playlist.php?get_id=<?= $fetch_content['playlist_id']; ?>" class="inline-btn">view playlist</a>
                <?php if($user_likes->rowCount() > 0){ ?>
                <button type="submit" name="like_content"><i class="fas fa-heart" style="color: #e74c3c;"></i> <span>liked</span></button>
                <?php }else{ ?>
                <button type="submit" name="like_content"><i class="far fa-heart"></i> <span>like</span></button>
                <?php } ?>
            </form>
            <div class="description">
                <p><?= $fetch_content['description']; ?></p>
            </div>
        </div>

        <?php 
                }
            }else{
                echo '<p class="empty">no content was found!</p>';
            }
        ?>
    </section>
    <!-- watch video tutorial ends here -->

    <!-- footer section starts here -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends here -->
    <script src="javascript/script.js"></script>
</body>
</html>