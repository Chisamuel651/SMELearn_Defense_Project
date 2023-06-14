<?php 
    include 'components/connect.php';

    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
        header('location: home.php');
    }

    if(isset($_POST['delete_bookmark'])){
        if($user_id != ''){
            $delete_id = $_POST['delete_id'];
            $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

            $verify_list = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
            $verify_list->execute([$user_id, $delete_id]);

            if($verify_list->rowCount() > 0){
                $remove_list = $conn->prepare("DELETE FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
                $remove_list->execute([$user_id, $delete_id]);
                $message[] = 'course removed from favorites!';
            }else{
                $message[] = 'course already removed!';
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
    <title>Bookmarked</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- course sectiong begins -->
    <section class="courses">
        <h1 class="heading">Bookmarked Courses</h1>
        <div class="box-container">
            <?php 
            $select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
            $select_bookmark->execute([$user_id]);
            if($select_bookmark->rowCount() > 0){
                while($fetch_bookmark = $select_bookmark->fetch(PDO::FETCH_ASSOC)){                   
                    $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND status = ? ORDER BY date DESC");
                    $select_courses->execute([$fetch_bookmark['playlist_id'],'active']);
                    if($select_courses->rowCount() > 0){
                        while($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)){
                            $course_id = $fetch_courses['id'];
                            $count_course = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
                            $count_course->execute([$course_id]);
                            $total_courses = $count_course->rowCount();

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
                    <span><?= $total_courses; ?></span>
                    <img src="thumb_files/<?= $fetch_courses['thumb']; ?>" alt="">
                </div>

                <h3 class="title"><?= $fetch_courses['title']; ?></h3>
                <form action="" method="post" class="flex-btn">
                    <input type="hidden" name="delete_id" value="<?= $course_id; ?>">
                    <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn" >view course</a>
                    <input type="submit" value="unsave" class="inline-delete-btn" onclick="return confirm('remove from favorites?')" name="delete_bookmark">
                </form>
            </div>      
            <?php 
                        }
                    }else{
                        echo '<p class="empty">no course is added yet!</p>';
                    }
                }  
            }else{
                echo '<p class="empty">nothing bookmarked!</p>';
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