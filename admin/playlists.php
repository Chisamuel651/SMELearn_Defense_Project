<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        header('location:login.php');
    }

    if(isset($_POST['delete_playlist'])){
        $delete_id = $_POST['delete_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

        $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
        $verify_playlist->execute([$delete_id]);

        if($verify_playlist->rowCount() > 0){
            $fetch_thumb = $verify_playlist->fetch(PDO::FETCH_ASSOC);
            $prev_thumb = $fetch_thumb['thumb'];
            
            if($prev_thumb != ''){
                unlink('../thumb_files/'.$prev_thumb);
            }

            $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
            $delete_bookmark->execute([$delete_id]);

            $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
            $delete_playlist->execute([$delete_id]);

            $message[] = 'playlist deleted!';
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
    <title>Playlist | Admin</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>
    <!-- view available playlists ends -->
    <section class="playlist">
        <h1 class="heading">all courses</h1>
        <div class="box-container">
            <div class="box" style="text-align:center;">
                <h3 class="title" style="padding-bottom: .5rem;">create new course</h3>
                <a href="add_playlist.php" class="btn">add course</a>
            </div>
            <?php
                $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
                $select_playlist->execute([$tutor_id]);
                if($select_playlist->rowCount() > 0){
                    while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){

                        $playlist_id = $fetch_playlist['id'];

                        $count_content = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
                        $count_content->execute([$playlist_id]);
                        $total_contents = $count_content->rowCount();
                        // var_dump($fetch_playlist);
            ?>
            <div class="box" >
                <div class="flex">
                    <div><i class="<?php if($fetch_playlist['status'] == 'active'){echo 'fa-solid fa-face-smile';}else{ echo 'fa-solid fa-face-frown';} ?>" style="color:<?php if($fetch_playlist['status'] == 'active'){echo '#70e000';}else{ echo '#d90429';} ?>;"></i><span style="color:<?php if($fetch_playlist['status'] == 'active'){echo '#70e000';}else{ echo '#d90429';} ?>;"><?php echo $fetch_playlist['status']; ?></span></div>
                    <div><i class="fa-solid fa-calendar"></i><span><?php echo $fetch_playlist['date']; ?></span></div>
                </div>
                <div class="thumb">
                    <span><?= $total_contents; ?></span>
                    <img src="../thumb_files/<?= $fetch_playlist['thumb']; ?>" alt="">
                </div>
                <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
                <p class="description"><?= $fetch_playlist['description']; ?></p>
                <form action="" method="post" class="flex-btn">
                    <input type="hidden" name="delete_id" value="<?= $playlist_id; ?>">
                    <a href="update_playlist.php?get_id=<?= $playlist_id; ?>" class="option-btn">update</a>
                    <input type="submit" value="delete" onclick="return confirm('do you want to delete this course?')" name="delete_playlist" class="delete-btn">
                </form>
                <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">view playlist</a>
            </div>
            <?php 
                    }
                }else{
                    echo '<p class="empty">course section is empty for the moment</p>';
                }
            ?>
            
        </div>
    </section>

    <!-- view available playlists ends -->
    <!-- footer section -->
    <?php include '../components/footer.php'; ?>
    <!-- custome js -->
    <script src="../javascript/admin-script.js"></script>

    <script>
        document.querySelectorAll('.description').forEach(content => {
            if(content.innerHTML.length > 100) content.innerHTML =content.innerHTML.slice(0, 100);
        })
    </script>
</body>
</html>

