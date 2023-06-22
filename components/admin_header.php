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
        <a href="dashboard.php" class="logo"> <img class="logo_img" src="../images/logo1.png"> <span>UY1 / Tutor</span></a>

        <form action="search_page.php" method="post" class="search-form">
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
                $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
                $select_profile->execute([$tutor_id]);

                if($select_profile->rowCount() > 0){
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                
            ?>
            <img src="../uploaded_files/<?= $fetch_profile['image'] ?>" alt="">
            <h3><?= $fetch_profile['name']; ?> <span><i id="verify" class="fa-solid fa-square-check"></i></span></h3>
            <span><?= $fetch_profile['profession']; ?></span>
            <a href="profile.php" class="btn">view profile</a>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div>
            <a href="../components/admin_logout.php" onclick="return confirm('logout from this web app?')" class="delete-btn">Logout</a>
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
            $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_profile->execute([$tutor_id]);

            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            
        ?>
        <img src="../uploaded_files/<?= $fetch_profile['image'] ?>" alt="">
        <h3><?= $fetch_profile['name']; ?> <span><i id="verify" class="fa-solid fa-square-check"></i></span></h3>
        <span><?= $fetch_profile['profession']; ?></span>
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
        <a href="dashboard.php"><i class="fas fa-home"></i> <span>home</span> </a>
        <a href="playlists.php"><i class="fas fa-bars-staggered"></i> <span>courses</span> </a>
        <a href="../admin/admin-video-conf/lobby.php"><i class="fa-solid fa-video"></i> <span>video room</span> </a>
        <a href="contents.php"><i class="fas fa-graduation-cap"></i> <span>contents</span> </a>
        <a href="comments.php"><i class="fas fa-comment"></i> <span>comments</span> </a>
        <a href="../components/admin_logout.php" onclick="return confirm('logout from this web app?')"><i class="fas fa-right-from-bracket"></i><span>Logout</span></a>
    </nav>
</div>
<!-- sidebar section ends here -->