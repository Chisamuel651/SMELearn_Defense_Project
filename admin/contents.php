<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        header('location:login.php');
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
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Page | Admin</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>
    <!-- content section starts here -->
    <section class="contents">
        <h1 class="heading">all content</h1>

        <div class="box-container">
            <?php
                $select_content = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
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
                    <input type="submit" onclick="return confirm('do you want to delete this course?')" class="delete-btn" value="delete content" name="delete_content">

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