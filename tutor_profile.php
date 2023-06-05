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
        header('location: teachers.php');
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
    <title>Tutor Profile | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- teacher profile section begins here -->
    <section class="tutor-profile">
        <h1 class="heading">teacher profile</h1>

        <?php 
            $select_tutors = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? LIMIT 1");
            $select_tutors->execute([$get_id]);

            if($select_tutors->rowCount() > 0){
                while($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)){
                    $tutor_id = $fetch_tutor['id'];

                    $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
                    $count_likes->execute([$tutor_id]);
                    $total_likes = $count_likes->rowCount();

                    $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
                    $count_comments->execute([$tutor_id]);
                    $total_comments = $count_comments->rowCount();

                    $count_content = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
                    $count_content->execute([$tutor_id]);
                    $total_content = $count_content->rowCount();

                    $count_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
                    $count_playlist->execute([$tutor_id]);
                    $total_playlist = $count_playlist->rowCount();
        ?>
        <div class="details">
            <div class="tutor">
                <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
                <h3 class="name" ><?= $fetch_tutor['name']; ?></h3>
                <span class="profession"><?= $fetch_tutor['profession']; ?></span>
                <p class="email"><?= $fetch_tutor['email']; ?></p>
            </div>

            <div class="box-container">
                <p>total courses: <span><?= $total_playlist; ?></span></p>
                <p>total contents: <span><?= $total_content; ?></span></p>
                <p>total comments: <span><?= $total_comments; ?></span></p>
                <p>total likes: <span><?= $total_likes; ?></span></p>
            </div>
        </div>
        <?php 
                }
            }else{
                echo '<p class="empty">no available teacher!</p>';
            }
        ?>
    </section>
    <!-- teacher profile section ends here -->

    <!-- course sectiong begins -->
    <section class="courses">
        <h1 class="heading">Teacher's Courses</h1>
        <div class="box-container">
            <?php 
            $select_tutors = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? LIMIT 1");
            $select_tutors->execute([$get_id]);
            $fetch_tutor_id = $select_tutors->fetch(PDO::FETCH_ASSOC);

            $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? AND status = ? ORDER BY date DESC");
            $select_courses->execute([$fetch_tutor_id['id'],'active']);
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
                <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn" >view course</a>
            </div>      
            <?php 
                }
            }else{
                echo '<p class="empty">no course is added yet!</p>';
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