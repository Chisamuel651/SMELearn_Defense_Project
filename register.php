<?php 
    include 'components/connect.php';

    if(isset($_COOKIE['user_id'])){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }
    if(isset($_POST['submit'])){

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $username = $_POST['username'];
        $username = filter_var($username, FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        $c_pass = sha1($_POST['c_pass']);
        $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = create_unique_id().'.'.$ext;
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_folder = 'uploaded_files/'.$rename;

        $select_user_email = $conn->prepare("SELECT * FROM `users` WHERE email = ? ");

        $select_user_email->execute([$email]);

        if($select_user_email->rowCount() > 0){
            $message[] = 'email is already taken!';
        }else{
            if($pass != $c_pass){
                $message[] = 'password do not match';
            }else{
                if($image_size > 2000000){
                    $message[] = 'image size is too large';
                }else{
                    $insert_user = $conn->prepare("INSERT INTO `users`( name, username, email, password, image) VALUES (?,?,?,?,?)");
                    $insert_user->execute([$name, $username, $email, $pass, $rename]);

                    move_uploaded_file($image_tmp_name, $image_folder);

                    $verify_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
                    $verify_user->execute([$email, $pass]);

                    $row = $verify_user->fetch(PDO::FETCH_ASSOC);

                    if($insert_user){
                        if($verify_user->rowCount() > 0){
                            setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
                            header('location: home.php');
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
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="css/style.css">
    <title>Register | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- register section starts -->
    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>register as student</h3>
            <div class="flex">
                <div class="col">
                    <p>your name <span>*</span></p>
                    <input type="text" class="box" name="name" maxlength="100" placeholder="enter your name" required>

                    <p>your user-name <span>*</span></p>
                    <input type="text" class="box" name="username" maxlength="100" placeholder="enter your name" required>

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


        </form>
    </section>
    <!-- register section ends -->

    <!-- footer section starts here -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends here -->
    <script src="javascript/script.js"></script>
</body>
</html>