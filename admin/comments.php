<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        header('location:login.php');
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
    <title>Comments Page | Admin</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>
    <!-- comment section starts  here-->
    <section class="comments">
        <h1 class="heading">tutor comments</h1>
        <div class="box-container">
            <?php 
                $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
                $select_comments->execute([$tutor_id]);

                if($select_comments->rowCount() > 0){
                    while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){
                        $comment_id = $fetch_comment['id'];

                        $select_commentor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                        $select_commentor->execute([$tutor_id]);
                        $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);

                        $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
                        $select_content->execute([$fetch_comment['content_id']]);
                        $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="box">
                <div class="comment-content">
                    <p><?= $fetch_content['title']; ?></p>
                    <a href="view_content.php?get_id=<?= $fetch_content['id']; ?>">view content</a>
                </div>
                <div class="user">
                    <img src="../uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
                    <div>
                        <h3><?= $fetch_commentor['name']; ?> </h3> 
                        <span><?= $fetch_comment['date']; ?></span>
                    </div>
                </div>
                <p class="comment-box"><?= $fetch_comment['comment']; ?></p>

                <form action="" method="post">
                    <input type="hidden" name="comment_id" value="><?= $fetch_comment['id']; ?>">
                    <input type="submit" value="delete comment" onclick="return confirm('do you want to delete this comment?')" name="delete_comment" class="inline-delete-btn">
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