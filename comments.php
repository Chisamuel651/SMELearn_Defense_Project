<?php 
    include 'components/connect.php';

    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
        header('location: home.php');
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
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="css/style.css">
    <title>All Comments | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- comment section starts here -->
    <section class="comments">
        <h1 class="heading">your comments</h1>
        <div class="box-container">
            <?php 
                $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
                $select_comments->execute([$user_id]);

                if($select_comments->rowCount() > 0){
                    while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){
                        $comment_id = $fetch_comment['id'];

                        $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
                        $select_content->execute([$fetch_comment['content_id']]);
                        $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="box" <?php if($fetch_comment['user_id'] == $user_id){ echo 'style ="order:1"';}?>>
                <div class="comment_content"> 
                    <p> - <?= $fetch_content['title']; ?> -</p> 
                    <a href="watch_video.php?get_id=<?= $fetch_content['id']; ?>">view content</a>
                </div>
                <p class="comment-box"><?= $fetch_comment['comment']; ?></p>

                <?php
                if($fetch_comment['user_id'] == $user_id){
                ?>

                <form action="" method="post">
                    <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
                    <!-- <input type="submit" value="update comment" name="option_comment" class="inline-option-btn"> -->
                    <input type="submit" value="delete comment" onclick="return confirm('do you want to delete this comment?')" name="delete_comment" class="inline-delete-btn">
                </form>
                <?php } ?>
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

    <!-- footer section starts here -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends here -->
    <script src="javascript/script.js"></script>
</body>
</html>