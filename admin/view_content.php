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

    if(isset($_POST['delete_content'])){
        $delete_id = $_POST['content_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

        $verify_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
        $verify_content->execute([$delete_id]);

        if($verify_content->rowCount() > 0){
            $fetch_content = $verify_content->fetch(PDO::FETCH_ASSOC);
            unlink('../course_content_files/'.$fetch_content['thumb']);
            unlink('../course_content_files/'.$fetch_content['video']);

            $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
            $delete_comment->execute([$delete_id]);

            $delete_like = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
            $delete_like->execute([$delete_id]);

            $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
            $delete_content->execute([$delete_id]);

            $message[] = 'content deleted !';
        }else{
            $message[] = 'content already deleted!';
        }
    }

    if(isset($_POST['delete_comment'])){
        $delete_id = $_POST['comment_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

        $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
        $verify_comment->execute([$delete_id]);

        if($verify_comment->rowCount() > 0){
            $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
            $delete_comment->execute([$delete_id]);
            $message[] = 'comment deleted successfully!';
        }else{
            $message[] = 'comment already deleted';
        }
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Content</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>
    <!-- view content section starts here -->
    <section class="view-content">
        <?php 
            $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
            $select_content->execute([$get_id]);

            if($select_content->rowCount() > 0){
                while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){ 
                    $content_id = $fetch_content['id'];

                    $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id=? AND content_id = ?");
                    $count_likes->execute([$tutor_id, $content_id]);
                    $total_likes = $count_likes->rowCount();

                    $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id=? AND content_id = ?");
                    $count_comments->execute([$tutor_id, $content_id]);
                    $total_comments = $count_comments->rowCount();

        ?>
        <div class="content">
            <video src="../course_content_files/<?= $fetch_content['video'] ?>" poster="../course_content_files/<?= $fetch_content['thumb'] ?>" controls autoplay></video>
            <div class="date"><i class="fa-solid fa-calendar"></i> <span><?= $fetch_content['date']; ?></span> </div>
            <h3 class="title"><?= $fetch_content['title']; ?></h3>
            <div class="flex">
                <div><i class="fa-regular fa-heart"></i> <span><?= $total_likes; ?></span> </div>
                <div><i class="fa-regular fa-comment"></i> <span><?= $total_comments; ?></span> </div>
            </div>
            <p class="description"><?= $fetch_content['description']; ?></p>
            <form action="" method="post" class="flex-btn">
                <input type="hidden" name="content_id" value="<?= $content_id; ?>">
                <input type="submit" value="delete content" name="delete_content" class="delete-btn">
                <a href="update_content.php?get_id=<?= $content_id; ?>" class="option-btn">update content</a>
            </form>
        </div>

        <?php 
                }
            }else{
                echo '<p class="empty">content was not found! <a href="add_content.php" class="btn">add new content</a></p>';
            }
        ?>
    </section>
    <!-- view content section ends here -->

    <!-- comment section starts  here-->
    <section class="comments">
        <h1 class="heading">user comments</h1>
        <div class="box-container">
            <?php 
                $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ? AND tutor_id = ?");
                $select_comments->execute([$get_id, $tutor_id]);

                if($select_comments->rowCount() > 0){
                    while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){
                        $comment_id = $fetch_comment['id'];
                        $select_commentor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                        $select_commentor->execute([$tutor_id]);

                        $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="box">
                <div class="user">
                    <img src="../uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
                    <div>
                        <h3><?= $fetch_commentor['name']; ?> <span class="creator"><i id="verify" class="fa-solid fa-square-check"></i></span></h3> 
                        <span><?= $fetch_comment['date']; ?></span>
                    </div>
                </div>
                <p class="comment-box"><?= $fetch_comment['comment']; ?></p>

                <form action="" method="post">
                    <input type="hidden" name="comment_id" value="><?= $fetch_comment['id']; ?>">
                    <input type="submit" value="delete comment" onclick="return confirm('do you want to delete this conent?')" name="delete_comment" class="inline-delete-btn">
                </form>
            </div>
            <?php 
                    }

                }else{
                    echo '<p class="empty">no comment has been added</p>';
                }
            ?>
            
        </div>
    </section>

    <!-- comment section ends here -->

    <!-- footer section -->
    <?php include '../components/footer.php'; ?>
    <!-- custome js -->
    <script src="../javascript/admin-script.js"></script>
</body>
</html>