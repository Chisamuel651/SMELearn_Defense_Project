<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        header('location:login.php');
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>
    <!-- search contents starts here -->
    <section class="contents">
        <h1 class="heading">content</h1>

        <div class="box-container">
            <?php
                if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
                    $search_box = $_POST['search_box'];
                    $select_content = $conn->prepare("SELECT * FROM `content` WHERE title LIKE '%{$search_box}%' AND tutor_id = ? ORDER BY date DESC");
                    $select_content->execute([$tutor_id]);

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
                    <input type="submit" class="delete-btn" value="delete content" name="delete_content">

                </form>
            </div>

            <?php
                    }
                }else{
                    echo '<p class="empty">no content found!</p>
                    ';
                }
            }else{
                echo '<p class="empty">search something!</p>';
            }
            ?>   
        </div>
    </section>
    <!-- search contents ends here -->

    <!-- search playlist begins here -->
    <section class="playlist">
        <h1 class="heading">courses</h1>
        <div class="box-container">
            <?php
            if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
                $search_box = $_POST['search_box'];
                $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE title LIKE '%{$search_box}%' AND tutor_id = ? ORDER BY date DESC");
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
                    echo '<p class="empty">no course has been found!</p>';
                }
            }else{
                echo '<p class="empty">search something!</p>';
            }
            ?>
            
        </div>
    </section>
    <!-- search playlist ends here -->

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