<?php 
    include 'components/connect.php';

    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
        header('location: home.php');
    }

    if(isset($_POST['delete'])){
        $delete_id = $_POST['delete_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

        $verify_like = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
        $verify_like->execute([$user_id,$delete_id]);

        if($verify_like->rowCount() > 0){
            $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND content_id = ?");
            $remove_likes->execute([$user_id, $delete_id]);
            $message[] = 'removed like';
        }else{
            $message[] = 'like already removed!';
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
    <title>All Likes | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- course sectiong begins -->
    <section class="courses">
        <h1 class="heading">Liked Courses</h1>
        <div class="box-container">
            <?php 
            $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id");
            $select_likes->execute([$user_id]);

            if($select_likes->rowCount() > 0){
                while($fetch_likes = $select_likes->fetch(PDO::FETCH_ASSOC)){
                    $select_courses = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND status = ? ORDER BY date DESC");
                    $select_courses->execute([$fetch_likes['content_id'],'active']);
                    if($select_courses->rowCount() > 0){
                        while($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)){
                            $course_id = $fetch_courses['id'];

                            $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                            $select_tutor->execute([$fetch_courses['tutor_id']]);
                            $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="box">
                <div class="tutor">
                    <img src="uploaded_files/<?= $fetch_tutor['image'] ?>" alt="">
                    <div>
                        <h3 class="title"><?= $fetch_tutor['name'] ?></h3>
                        <span><?= $fetch_courses['date'] ?></span>
                    </div>
                </div>

                <div class="thumb">
                    
                    <img src="course_content_files/<?= $fetch_courses['thumb']; ?>" alt="">
                </div>

                <h3 class="title"><?= $fetch_courses['title']; ?></h3>
                <form action="" method="post" class="flex-btn">
                    <input type="hidden" name="delete_id" value="<?= $fetch_likes['content_id']; ?>">
                    <a href="watch_video.php?get_id=<?= $course_id; ?>" class="inline-btn" >view video</a>
                    <input type="submit" value="remove" class="inline-delete-btn" name="delete" >
                </form>
            </div>      
            <?php 
                        }
                    }else{
                        echo '<p class="empty">no course is added yet!</p>';
                    }
                }
            }else{
                echo '<p class="empty">nothing liked yet!</p>';
            }
            ?>
        </div>
    </section>
    <!-- course sectiong ends -->

    <!-- footer section starts here -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends here -->
    <script src="javascript/script.js"></script>
</body>
</html>