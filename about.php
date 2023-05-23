<?php 
    include 'components/connect.php';

    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
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
    <title>About Us | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- about section starts here -->
    <section class="about">
        <div class="row">
            <div class="image">
                <img src="images/about-img.svg" alt="">
            </div>

            <div class="content">
                <h3>why this app?</h3>
                <p>The University of Yaoundé I is a public establishment of a scientific and cultural nature endowed with legal personality and financial autonomy. It is located in the city of Yaoundé, the political capital of Cameroon.</p>
                <a href="courses.php" class="inline-btn">our courses</a>
            </div>
        </div>
        
        <div class="box-container">
            <div class="box">
                <i class="fas fa-graduation-cap"></i>
                <div>
                    <h3>1K+</h3>
                    <p>online courses</p>
                </div>
            </div>

            <div class="box">
                <i class="fa-solid fa-user-graduate"></i>
                <div>
                    <h3>300+</h3>
                    <p>brilliant students</p>
                </div>
            </div>

            <div class="box">
                <i class="fa-solid fa-chalkboard-user"></i>
                <div>
                    <h3>20+</h3>
                    <p>expert teachers</p>
                </div>
            </div>

            <div class="box">
                <i class="fa-solid fa-briefcase"></i>
                <div>
                    <h3>100%</h3>
                    <p>content courses</p>
                </div>
            </div>
        </div>
    </section>
    <!-- about section ends here -->

    <!-- reviews section begins here -->
    <section class="reviews">
        <h1 class="heading">student's remarks</h1>
        <div class="box-container">
            <div class="box">
                <div class="user">
                    <img src="images/pic-4.jpg" alt="">
                    <div>
                        <h3>ojong william</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat dicta, ut voluptatum tempora distinctio nihil eaque esse at ex cupiditate, perspiciatis voluptates cumque quas? Error vel consequuntur beatae laboriosam quod?</p>
            </div>

            <div class="box">
                <div class="user">
                    <img src="images/pic-4.jpg" alt="">
                    <div>
                        <h3>ojong william</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat dicta, ut voluptatum tempora distinctio nihil eaque esse at ex cupiditate, perspiciatis voluptates cumque quas? Error vel consequuntur beatae laboriosam quod?</p>
            </div>

            <div class="box">
                <div class="user">
                    <img src="images/pic-4.jpg" alt="">
                    <div>
                        <h3>ojong william</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat dicta, ut voluptatum tempora distinctio nihil eaque esse at ex cupiditate, perspiciatis voluptates cumque quas? Error vel consequuntur beatae laboriosam quod?</p>
            </div>
        </div>
    </section>
    <!-- reviews section ends here -->

    <!-- footer section starts here -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends here -->
    <script src="javascript/script.js"></script>
</body>
</html>