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

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);

        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_STRING);

        $msg = $_POST['msg'];
        $msg = filter_var($msg, FILTER_SANITIZE_STRING);

        $verify_contact = $conn->prepare("SELECT * FROM `contact` WHERE name = ? AND email = ? AND number = ? AND message = ?");
        $verify_contact->execute([$name, $email, $number, $msg]);

        if($verify_contact->rowCount() > 0){
            $message[] = 'message sent already!';
        }else{
            $send_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
            $send_message->execute([$name, $email, $number, $msg]);
            $message[] = 'message sent successfully!';
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
    <title>Contact Us | SMELEARN</title>
</head>
<body>
    <!-- header section starts here -->
    <?php include 'components/user_header.php'; ?>
    <!-- header section ends here -->

    <!-- contact page begins -->
    <section class="contact">
        <div class="row">
            <div class="image">
                <img src="images/contact-img.svg" alt="">
            </div>
            <form action="" method="post">
                <h3>get in touch</h3>
                <input type="text" name="name" placeholder="please enter your name" class="box" maxlength="50" required>
                <input type="email" name="email" placeholder="please enter your email" class="box" maxlength="100" required>
                <input type="number" name="number" placeholder="please enter your number" min="0" max="999999999" class="box" maxlength="100" required>
                <textarea name="msg" class="box" maxlength="1000" required placeholder="enter your message" cols="30" rows="10"></textarea>
                <input type="submit" value="send message" class="inline-btn" name="submit">
            </form>
        </div>

        <div class="box-container">
            <div class="box">
                <i class="fas fa-phone"></i>
                <h3>phone number</h3>
                <a href="tel:676808381">676-808-381</a>
                <a href="tel:695013325">695-013-325</a>
            </div>

            <div class="box">
                <i class="fas fa-envelope"></i>
                <h3>email address</h3>
                <a href="mailto:smelearn@gmail.com">smelearn@gmail.com</a>
                <a href="mailto:smelearncenter@gmail.com">smelearncenter@gmail.com</a>
            </div>

            <div class="box">
                <i class="fas fa-map-marker-alt"></i>
                <h3>office address</h3>
                <a href="#">Chateau, Yaoundé</a>
            </div>
        </div>
    </section>
    <!-- contact page ends -->

    <!-- footer section starts here -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends here -->
    <script src="javascript/script.js"></script>
</body>
</html>