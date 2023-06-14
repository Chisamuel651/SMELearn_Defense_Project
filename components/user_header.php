<?php 
    if(isset($message)){
        foreach($message as $message){
            echo '
                <div class="message">
                    <span>'.$message.'</span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>
            ';
        }
    }
?>

<!-- header section begins here -->
<header class="header">
    <section class="flex">
        <a href="home.php" class="logo"> <img class="logo_img" src="images/logo1.png"> <span>UY1 / SMELearn</span></a>

        <form action="search_course.php" method="post" class="search-form">
            <input type="text" placeholder="search here..." name="search_box" maxlength="100" required>
            <button type="submit" class="fas fa-search" name="search_btn"></button>
        </form>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
            <?php 
                $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                $select_profile->execute([$user_id]);

                if($select_profile->rowCount() > 0){
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                
            ?>
            <img src="uploaded_files/<?= $fetch_profile['image'] ?>" alt="">
            <h3><?= $fetch_profile['name']; ?></h3>
            <span>Student</span>
            <a href="profile.php" class="btn">view profile</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div>
            <a href="components/user_logout.php" onclick="return confirm('logout from this web app?')" class="delete-btn">Logout</a>
            <?php
                }else{
            ?>
            <h3>please login first</h3>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div>
            <?php } ?>
        </div>
    </section>
</header>
<!-- header section ends here -->

<!-- sidebar section begins here -->
<div class="side-bar">
    <div id="close-bar">
        <i class="fas fa-times"></i>
    </div>
    <div class="profile">
        <?php 
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);

            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            
        ?>
        <img src="uploaded_files/<?= $fetch_profile['image'] ?>" alt="">
        <h3><?= $fetch_profile['name']; ?></h3>
        <span>Student</span>
        <a href="profile.php" class="btn">view profile</a>
        
        <!-- <a href="../components/admin_logout.php" onclick="return confirm('logout from this web app?')" class="delete-btn">Logout</a> -->
        <?php
            }else{
        ?>
        <h3>please login first</h3>
        <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
        </div>
        <?php } ?>
    </div>

    <nav class="navbar">
        <a href="home.php"><i class="fas fa-home"></i> <span>home</span> </a>
        <a href="about.php"><i class="fa-solid fa-question"></i> <span>about</span> </a>
        <a href="courses.php"><i class="fas fa-graduation-cap"></i> <span>courses</span> </a>
        <a href="teachers.php"><i class="fa-solid fa-chalkboard-user"></i> <span>teachers</span> </a>
        <a href="contact.php"><i class="fa-solid fa-headset"></i> <span>contact</span> </a>
        <!-- <a href="../components/admin_logout.php" onclick="return confirm('logout from this web app?')"><i class="fas fa-right-from-bracket"></i><span>Logout</span></a> -->
    </nav>
</div>
<!-- sidebar section ends here -->