<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        header('location:login.php');
    }

    if(isset($_POST['submit'])){
        $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
        $select_tutor->execute([$tutor_id]);
        $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $profession = $_POST['profession'];
        $profession = filter_var($profession, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        if(!empty($name)){
            $update_name = $conn->prepare("UPDATE `tutors` SET name = ? WHERE id = ?");
            $update_name->execute([$name, $tutor_id]);
            $message[] = "name updated successfully";
        }

        if(!empty($profession)){
            $update_profession = $conn->prepare("UPDATE `tutors` SET profession = ? WHERE id = ?");
            $update_profession->execute([$profession, $tutor_id]);
            $message[] = "profession updated successfully";
        }

        if(!empty($email)){
            $select_tutor_email = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? ");
            $select_tutor_email->execute([$email]);

            if($select_tutor_email->rowCount() > 0){
                $message[] = 'Email is already taken';
            }else{
                $update_email = $conn->prepare("UPDATE `tutors` SET email = ? WHERE id = ?");
                $update_email->execute([$email, $tutor_id]);
                $message[] = "email updated successfully";
            }
            
        }

        $prev_image = $fetch_tutor['image'];
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        // $rename = create_unique_id().'.'.$ext;
        $rename = create_unique_id().'.'.$ext;
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_folder = '../uploaded_files/'.$rename;
        
        // $tmp_name = filter_var($tmp_name, FILTER_SANITIZE_STRING);

        if(!empty($image)){
            if($image_size > 2000000){
                $message[] = 'image size is too large';
            }else{
                $update_image = $conn->prepare("UPDATE `tutors` SET image = ? WHERE id = ?");
                $update_image->execute([$rename, $tutor_id]);   
                move_uploaded_file($image_tmp_name, $image_folder);
                if($prev_image != '' AND $prev_image != $rename){
                    unlink('../uploaded_files/'.$prev_image);
                }
                $message[] = "image updated successfully";
            }
        }

        $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
        $prev_pass = $fetch_tutor['password'];
        $old_pass = sha1($_POST['old_pass']);
        $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

        $new_pass = sha1($_POST['new_pass']);
        $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);

        $c_pass = sha1($_POST['c_pass']);
        $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

        if($old_pass != $empty_pass){
            if($old_pass != $prev_pass){
                $message[] = 'old password does not match';
            }
            elseif($new_pass != $c_pass){
                $message[] = 'new and confirm password do not match';
            }
            else{
                if($new_pass != $empty_pass){
                    $update_pass = $conn->prepare("UPDATE `tutors` SET password = ? WHERE id = ?");
                    $update_pass->execute([$c_pass, $tutor_id]);
                    $message[] = "password updated successfully";
                }else{
                    $message[] = 'please enter new password';
                }
            }
        }
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Page</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>

    <!-- update section begins here -->
    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>update profile</h3>
            <div class="flex">
                <div class="col">
                    <p>your name</p>
                    <input type="text" class="box" name="name" maxlength="100" placeholder="<?= $fetch_profile['name']; ?>">

                    <p>your course</p>
                    <select name="profession" class="box">
                        <option value="" selected><?= $fetch_profile['profession']; ?></option>
                        <option value="ict300">ICT300</option>
                        <option value="ict302">ict302</option>
                        <option value="ict310">ICT310</option>
                        <option value="ict306">ICT306</option>
                        <option value="ict304">ICT304</option>
                        <option value="ict308">ICT308</option>
                    </select>

                    <p>your email</p>
                    <input type="email" class="box" name="email" maxlength="100" placeholder="<?= $fetch_profile['email']; ?>">
                </div>

                <div class="col">

                    <p>old password</p>
                    <input type="password" class="box" name="old_pass" maxlength="100" placeholder="enter your old password">

                    <p>your password</p>
                    <input type="password" class="box" name="new_pass" maxlength="100" placeholder="enter your new password">

                    <p>confirm your password</p>
                    <input type="password" class="box" name="c_pass" maxlength="100" placeholder="confirm your new password">

                    
                </div>
            </div>
            <p>select a profile picture</p>
            <input type="file" name="image" class="box" accept="image/*">


            <input type="submit" value="update now" name="submit" class="btn">
        </form>
    </section>

    <!-- update section ends here -->
    <!-- footer section -->
    <?php include '../components/footer.php'; ?>
    <!-- custome js -->
    <script src="../javascript/admin-script.js"></script>
</body>
</html>