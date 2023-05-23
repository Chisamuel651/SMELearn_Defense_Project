<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        // header('location:login.php');
    }

    if(isset($_POST['submit'])){

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $profession = $_POST['profession'];
        $profession = filter_var($profession, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        $c_pass = sha1($_POST['c_pass']);
        $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        // $rename = create_unique_id().'.'.$ext;
        $rename = $name.'.'.$ext;
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_folder = '../uploaded_files/'.$rename;
        // $tmp_name = filter_var($tmp_name, FILTER_SANITIZE_STRING);

        $select_tutor_email = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? ");

        $select_tutor_email->execute([$email]);

        if($select_tutor_email->rowCount() > 0){
            $message[] = 'email is already taken!';
        }else{
            if($pass != $c_pass){
                $message[] = 'password do not match';
            }else{
                if($image_size > 2000000){
                    $message[] = 'image size is too large';
                }else{
                    $insert_tutor = $conn->prepare("INSERT INTO `tutors`( name, profession, email, password, image) VALUES (?,?,?,?,?)");
                    $insert_tutor->execute([$name, $profession, $email, $pass, $rename]);

                    move_uploaded_file($image_tmp_name, $image_folder);

                    $verify_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
                    $verify_tutor->execute([$email, $pass]);

                    $row = $verify_tutor->fetch(PDO::FETCH_ASSOC);

                    if($insert_tutor){
                        if($verify_tutor->rowCount() > 0){
                            setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
                            header('location: dashboard.php');
                        }else{
                            $message[] = 'something went wrong!';
                        }
                    }
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
    <title>Register | Admin</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body style="padding-left: 0;">
    <!-- header section -->
    <?php 
        if(isset($message)){
            foreach($message as $message){
                echo '
                    <div class="message form">
                        <span>'.$message.'</span>
                        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                    </div>
                ';
            }
        }
    ?>

    <!-- register section starts -->
    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>register new</h3>
            <div class="flex">
                <div class="col">
                    <p>your name <span>*</span></p>
                    <input type="text" class="box" name="name" maxlength="100" placeholder="enter your name" required>

                    <p>your course <span>*</span></p>
                    <select name="profession" class="box">
                        <option value="" disabled selected>-- select your profession --</option>
                        <option value="ict300">ICT300</option>
                        <option value="ict302">ict302</option>
                        <option value="ict310">ICT310</option>
                        <option value="ict306">ICT306</option>
                        <option value="ict304">ICT304</option>
                        <option value="ict308">ICT308</option>
                    </select>

                    <p>your email <span>*</span></p>
                    <input type="email" class="box" name="email" maxlength="100" placeholder="enter your email" required>
                </div>

                <div class="col">
                    <p>your password <span>*</span></p>
                    <input type="password" class="box" name="pass" maxlength="100" placeholder="enter your password" required>

                    <p>confirm your password <span>*</span></p>
                    <input type="password" class="box" name="c_pass" maxlength="100" placeholder="confirm your password" required>

                    <p>select a profile picture <span>*</span></p>
                    <input type="file" name="image" class="box" required accept="image/*">
                </div>
            </div>


            <input type="submit" value="register now" name="submit" class="btn">
            <p class="link">already have an accoupt? <a href="login.php">login now</a></p>


        </form>
    </section>
    <!-- register section ends -->

    <!-- footer section -->
    <script src="../javascript/admin-script.js"></script>
</body>
</html>