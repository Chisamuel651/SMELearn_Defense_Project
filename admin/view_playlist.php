<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        header('location:login.php');
    }

    if(isset($_GET['get_id'])){
        $get_id = $_GET['get_id'];
    }else{
        $get_id = '';
        header('location: playlists.php');
    }

    if(isset($_POST['delete_playlist'])){

        $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
        $verify_playlist->execute([$get_id]);

        if($verify_playlist->rowCount() > 0){
            $fetch_thumb = $verify_playlist->fetch(PDO::FETCH_ASSOC);
            $prev_thumb = $fetch_thumb['thumb'];
            
            if($prev_thumb != ''){
                unlink('../thumb_files/'.$prev_thumb);
            }

            $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
            $delete_bookmark->execute([$get_id]);

            $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
            $delete_playlist->execute([$get_id]);

            header('location: playlists.php');
        }else{
            $message[] = "Course was already deleted!";
        }
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Playlist</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>
    <!-- view playlist section begins here -->
    <section class="playlist-details">
        <h1 class="heading">course details</h1>
        <?php
            $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
            $select_playlist->execute([$get_id]);
            if($select_playlist->rowCount() > 0){
                while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){

                    // $playlist_id = $fetch_playlist['id'];
                    $count_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id=?");
                    $count_content->execute([$get_id]);
                    $total_contents = $count_content->rowCount();
        ?>
        <div class="row">
            <div class="thumb">
                <img src="../thumb_files/<?= $fetch_playlist['thumb']; ?>" alt="">
                <div class="flex">
                    <p> <i class="fa-solid fa-video"></i> <span><?= $total_contents; ?></span></p>
                    <p><i class="fa-solid fa-calendar"></i> <span><?= $fetch_playlist['date']; ?></span></p>
                </div>
            </div>

            <div class="details">
                <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
                <p class="description"><?= $fetch_playlist['description']; ?></p>
                <form action="" method="post" class="flex-btn">
                    <input type="hidden" name="delete_id" value="<?= $fetch_playlist['id']; ?>">
                    <a href="update_playlist.php?get_id=<?= $fetch_playlist['id']; ?>" class="option-btn">update</a>
                    <input type="submit" value="delete" onclick="return confirm('do you want to delete this course?')" name="delete_playlist" class="delete-btn">
                </form>
            </div>
        </div>
        <?php 
                }
            }else{
                echo '<p class="empty">no available course for the moment!</p>';
            }
        ?>
    </section>
    <!-- view playlist section ends here -->

    <!-- content section starts here -->
    <section class="contents">
        <h1 class="heading">course content</h1>

        <div class="box-container">
            <?php
                $select_content = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? AND playlist_id = ?");
                $select_content->execute([$tutor_id, $get_id]);

                if($select_content->rowCount() > 0){
                    while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
                    // var_dump($fetch_content['tutor_id']);
            ?>

            <div class="box">
                <div class="flex">
                    <p><i class="<?php if($fetch_content['status'] == 'active'){echo 'fa-solid fa-thumbs-up';}else{ echo 'fa-solid fa-thumbs-down';} ?>" style="color:<?php if($fetch_content['status'] == 'active'){echo '#70e000';}else{ echo '#d90429';} ?>;"></i> <span style="color:<?php if($fetch_content['status'] == 'active'){echo '#70e000';}else{ echo '#d90429';} ?>;"><?php echo $fetch_content['status']; ?></span></p>
                    <p><i class="fa-solid fa-calendar"></i> <span><?= $fetch_content['date'] ?></span> </p>
                </div>

                <img src="../course_content_files/<?= $fetch_content['thumb']; ?>" alt="">
                <h3 class="title"><?= $fetch_content['title']; ?></h3>
                <a href="view_content.php?get_id=<?= $fetch_content['id']; ?>" class="btn">View Course Content</a>
                <form action="" method="post" class="flex-btn">
                    <input type="hidden" name="content_id" value="<?= $fetch_content['id']; ?>">
                    <a href="update_content.php?get_id=<?= $fetch_content['id']; ?>" class="option-btn">update</a>
                    <input type="submit" class="delete-btn" onclick="return confirm('do you want to delete this content?')" value="delete content" name="delete_content">

                </form>
            </div>

            <?php
                    }
                }else{
                    echo '<p class="empty">no content available yet! <a href="add_content.php" class="btn">add new content</a></p>
                    ';
                }
            ?>
            
            
        </div>
    </section>
    <!-- content section ends here -->
    <!-- footer section -->
    <?php include '../components/footer.php'; ?>
    <!-- custome js -->
    <script src="../javascript/admin-script.js"></script>
</body>
</html>