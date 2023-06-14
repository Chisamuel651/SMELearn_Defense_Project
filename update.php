<?php 
    include 'components/connect.php';

    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
        header('location: home.php');
    }

    if(isset($_POST['submit'])){
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_user->execute([$user_id]);
        $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $username = $_POST['username'];
        $username = filter_var($username, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        if(!empty($name)){
            $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
            $update_name->execute([$name, $user_id]);
            $message[] = "name updated successfully";
        }

        if(!empty($username)){
            $update_username = $conn->prepare("UPDATE `users` SET username = ? WHERE id = ?");
            $update_username->execute([$username, $user_id]);
            $message[] = "username updated successfully";
        }

        if(!empty($email)){
            $select_user_email = $conn->prepare("SELECT * FROM `users` WHERE email = ? ");
            $select_user_email->execute([$email]);

            if($select_user_email->rowCount() > 0){
                $message[] = 'Email is already taken';
            }else{
                $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
                $update_email->execute([$email, $user_id]);
                $message[] = "email updated successfully";
            }
            
        }

        $prev_image = $fetch_user['image'];
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        // $rename = create_unique_id().'.'.$ext;
        $rename = create_unique_id().'.'.$ext;
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_folder = 'uploaded_files/'.$rename;
        
        // $tmp_name = filter_var($tmp_name, FILTER_SANITIZE_STRING);

        if(!empty($image)){
            if($image_size > 2000000){
                $message[] = 'image size is too large';
            }else{
                $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
                $update_image->execute([$rename, $user_id]);   
                move_uploaded_file($image_tmp_name, $image_folder);
                if($prev_image != '' AND $prev_image != $rename){
                    unlink('uploaded_files/'.$prev_image);
                }
                $message[] = "image updated successfully";
            }
        }

        $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
        $prev_pass = $fetch_user['password'];

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
                    $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
                    $update_pass->execute([$c_pass, $user_id]);
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
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="css/style.css">
    <title>Update | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- update section starts here -->
    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>update profile</h3>
            <div class="flex">
                <div class="col">
                    <p>your name</p>
                    <input type="text" class="box" name="name" maxlength="100" placeholder="<?= $fetch_profile['name']; ?>">

                    <p>your username</p>
                    <input type="text" class="box" name="username" maxlength="100" placeholder="<?= $fetch_profile['username']; ?>">

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

    <!-- footer section starts here -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends here -->
    <script src="javascript/script.js"></script>
</body>
</html>