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

    if(isset($_POST['save_list'])){
        if($user_id != ''){
            $list_id = $_POST['list_id'];
            $list_id = filter_var($list_id, FILTER_SANITIZE_STRING);

            $verify_list = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
            $verify_list->execute([$user_id, $list_id]);

            if($verify_list->rowCount() > 0){
                $remove_list = $conn->prepare("DELETE FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
                $remove_list->execute([$user_id, $list_id]);
                $message[] = 'course removed from favorites!';
            }else{
                $add_list = $conn->prepare("INSERT INTO `bookmark` (user_id, playlist_id) VALUES (?, ?)");
                $add_list->execute([$user_id, $list_id]);
                $message[] = 'course saved to your favorites!';
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
    <title>View All Courses | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- view playlist begins here -->
    <section class="playlist-details">
        <h1 class="heading">playlist details</h1>

        <div class="row">
            <?php 
                $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND status = ? LIMIT 1");
                $select_playlist->execute([$get_id, 'active']);
                if($select_playlist->rowCount() > 0){
                    while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
                        $playlist_id = $fetch_playlist['id'];

                        $count_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND status = ?");
                        $count_content->execute([$playlist_id, 'active']);
                        $total_content = $count_content->rowCount();

                        $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
                        $select_tutor->execute([$fetch_playlist['tutor_id']]);
                        $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

                        $select_bookmark = $conn->prepare('SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?');
                        $select_bookmark->execute([$user_id, $playlist_id]);
            ?>
            <div class="col">
                <form action="" method="post">
                    <input type="hidden" name="list_id" value="<?= $playlist_id; ?>">
                    <?php if($select_bookmark->rowCount() > 0 ){ ?>
                        <button type="submit" name="save_list" class="inline-btn"> <i class="fas fa-bookmark"></i><span>saved</span> </button>
                    <?php }else{ ?>
                        <button type="submit" name="save_list" class="inline-btn"> <i class="far fa-bookmark"></i><span>save list</span> </button>
                    <?php } ?>
                </form>

                <div class="thumb">
                    <span><?= $total_content; ?></span>
                    <img src="thumb_files/<?= $fetch_playlist['thumb']; ?>" alt="">
                </div>
            </div>

            <div class="col">
                <div class="tutor">
                    <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
                    <div>
                        <h3><?= $fetch_tutor['name']; ?></h3>
                        <span><?= $fetch_tutor['profession']; ?></span>

                    </div>
                </div>

                <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
                <p class="description"><?= $fetch_playlist['description']; ?></p>
                <div class="date"><i class="fas fa-calendar"></i> <span><?= $fetch_playlist['date']; ?></span></div>
            </div>
            <?php 
                    }
                }else{
                    echo '<p class="empty">sorry, playlist not found</p>';
                }
            ?>
        </div>
    </section>
    <!-- view playlist ends here -->

    <!-- playlist video content begins here -->
    <section class="playlist-videos">
        <h1 class="heading">playlist video</h1>

        <div class="box-container">
            <?php 
                $select_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND status = ? ORDER BY date DESC");
                $select_content->execute([$get_id, 'active']);

                if($select_content->rowCount() > 0){
                    while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
            ?>
            <a href="watch_video.php?get_id=<?= $fetch_content['id']; ?>" class="box">
                <i class="fas fa-play"></i>
                <img src="course_content_files/<?= $fetch_content['thumb']; ?>" alt="">
                <h3><?= $fetch_content['title']; ?></h3>
            </a>

            <?php 
                    }
                }else{
                    echo '<p class="empty">no content was added!</p>';
                }
            ?>
        </div>
    </section>

    <!-- playlist video content ends here -->
    
    <!-- footer section starts here -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends here -->
    <script src="javascript/script.js"></script>
</body>
</html>