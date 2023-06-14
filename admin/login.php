<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        // header('location:login.php');
    }

    if(isset($_POST['submit'])){

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);


        $verify_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
        $verify_tutor->execute([$email, $pass]);

        $row = $verify_tutor->fetch(PDO::FETCH_ASSOC);

        if($verify_tutor->rowCount() > 0){
            setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
            header('location: dashboard.php');
        }else{
            $message[] = 'incorrect email or password!';
        }
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page | Admin</title>
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

    <!-- login section starts -->
    <section class="form-container">
        <form class="login" action="" method="post" enctype="multipart/form-data">
            <h3>welcome back!</h3>
            <div class="flex">
                <div class="col">
                    <p>your email <span>*</span></p>
                    <input type="email" class="box" name="email" maxlength="100" placeholder="enter your email" required>

                    <p>your password <span>*</span></p>
                    <input type="password" class="box" name="pass" maxlength="100" placeholder="enter your password" required>
                </div>
            </div>

            <input type="submit" value="sign in" name="submit" class="btn">
            <p class="link">are you new here? <a href="register.php">register now</a></p>


        </form>
    </section>
    <!-- login section ends -->

    <!-- footer section -->
    <script src="../javascript/admin-script.js"></script>
</body>
</html>