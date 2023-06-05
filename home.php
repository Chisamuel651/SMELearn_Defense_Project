<?php 
    include 'components/connect.php';

    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

    $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
    $count_likes->execute([$user_id]);
    $total_likes = $count_likes->rowCount();

    $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
    $count_comments->execute([$user_id]);
    $total_comments = $count_comments->rowCount();

    $count_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
    $count_bookmark->execute([$user_id]);
    $total_bookmark = $count_bookmark->rowCount();
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
    <title>Home | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- quick select starts here -->
    <section class="quick-select">
        <h1 class="heading">quick options</h1>
        <div class="box-container">
            <?php if($user_id != ''){ ?>
            <div class="box">
                <h3 class="title">likes and coments</h3>
                <p>total likes: <span><?= $total_likes; ?></span> </p>
                <a href="likes.php" class="inline-btn">view likes</a>
                <p>total comments: <span><?= $total_comments; ?></span> </p>
                <a href="comments.php" class="inline-btn" >view comments</a>
                <p>courses saved: <span><?= $total_bookmark; ?></span> </p>
                <a href="bookmark.php" class="inline-btn" >view bookmark</a>
            </div>
            <?php }else{ ?>
            <div class="box" style="text-align: center;">
                <h3 class="title">login or register</h3>
                <div class="flex-btn">
                    <a href="login.php" class="option-btn">login</a>
                    <a href="register.php" class="option-btn">register</a>
                </div>
            </div>
            <?php } ?>

            <div class="box">
                <h3 class="title">top categories</h3>
                <div class="flex">
                    <a href="#" class="link"><i class="fa-solid fa-code"></i> <span>ICT300</span> </a>
                    <a href="#" class="link"><i class="fa-solid fa-code"></i> <span>ICT302</span> </a>
                    <a href="#" class="link"><i class="fa-solid fa-code"></i> <span>ICT304</span> </a>
                    <a href="#" class="link"><i class="fa-solid fa-code"></i> <span>ICT306</span> </a>
                    <a href="#" class="link"><i class="fa-brands fa-java"></i> <span>ICT308</span> </a>
                    <a href="#" class="link"><i class="fa-solid fa-code"></i> <span>ICT310</span> </a>
                    <a href="#" class="link"><i class="fa-solid fa-code"></i> <span>ICT314</span> </a>
                    <a href="#" class="link"><i class="fa-solid fa-wifi"></i> <span>ICT318</span> </a>
                </div>
            </div>

            <div class="box">
                <h3 class="title">popular courses</h3>
                <div class="flex">
                    <a href="#" class="link"><i class="fa-brands fa-java"></i> <span>ICT308</span> </a>
                    <a href="#" class="link"><i class="fa-brands fa-java"></i> <span>ICT318</span> </a>
                    <a href="#" class="link"><i class="fa-brands fa-java"></i> <span>ICT302</span> </a>
                    <a href="#" class="link"><i class="fa-brands fa-java"></i> <span>ICT300</span> </a>
                </div>
            </div>

            <div class="box tutor">
                <h3 class="title">become a tutor</h3>
                <p>As a tutor on our online class platform, you can create and manage your own courses, set prices, communicate with students, and access tools to make your courses engaging.</p>
                <a href="admin/register.php" class="inline-btn">get started</a>
            </div>
        </div>
    </section>

    <!-- quick select ends here -->

    <!-- course sectiong begins -->
    <section class="courses">
        <h1 class="heading">latest courses</h1>
        <div class="box-container">
            <?php 
            $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
            $select_courses->execute(['active']);
            if($select_courses->rowCount() > 0){
                while($fetch_courses = $select_courses->fetch(PDO::FETCH_ASSOC)){
                    $course_id = $fetch_courses['id'];
                    $count_course = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? AND status = ?");
                    $count_course->execute([$course_id, 'active']);
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
        <div style="margin-top: 2rem; text-align: center;">
            <a href="courses.php" class="inline-option-btn">view all</a>
        </div>
    </section>
    <!-- course sectiong ends -->

    <!-- footer section starts here -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends here -->
    <script src="javascript/script.js"></script>
</body>
</html>