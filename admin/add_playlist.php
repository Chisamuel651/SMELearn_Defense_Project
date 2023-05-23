<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        header('location:login.php');
    }

    if(isset($_POST['submit'])){

        $desired_length = 10;
        $value =  base64_encode(openssl_random_pseudo_bytes($desired_length));

        $status = $_POST['status'];
        $status = filter_var($status, FILTER_SANITIZE_STRING);

        $title = $_POST['title'];
        $title = filter_var($title, FILTER_SANITIZE_STRING);

        $description = $_POST['description'];
        $description = filter_var($description, FILTER_SANITIZE_STRING);

        $thumb = $_FILES['thumb']['name'];
        $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
        $ext = pathinfo($thumb, PATHINFO_EXTENSION);
        // $rename = create_unique_id().'.'.$ext;
        $rename = $value.'.'.$ext;
        $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
        $thumb_size = $_FILES['thumb']['size'];
        $thumb_folder = '../thumb_files/'.$rename;

        $verify_course = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? AND title = ? AND description = ?");
        $verify_course->execute([$tutor_id, $title, $description]);

        if($verify_course->rowCount() > 0){
            $message[] = 'playlist already exist';
        }else{
            $add_course = $conn->prepare("INSERT INTO  `playlist`(tutor_id, title, description, thumb, status) VALUES (?,?,?,?,?)");
            $add_course->execute([$tutor_id, $title, $description, $rename, $status]);
            move_uploaded_file($thumb_tmp_name, $thumb_folder);
            $message[] = 'new course has been created';
        }       
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Playlist | Admin</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>

    <!-- add playlist starts here -->
    <section class="crud-form">
        <h1 class="heading">add playlist</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <p>course status <span>*</span></p>
            <select class="box" name="status" required>
                <option value="active" selected>Active</option>
                <option value="deactive">Deactive</option>
            </select>
            <p>course title <span>*</span></p>
            <input type="text" name="title" class="box" maxlength="100" placeholder="enter course title" required>
            <p>course description <span>*</span></p>
            <textarea name="description" class="box" required placeholder="enter course description" maxlength="1000" cols="30" rows="10"></textarea>
            <p>course thumbnail <span>*</span></p>
            <input type="file" name="thumb" required class="box" required accept="image/*">

            <input type="submit" value="create course" name="submit" class="btn">
        </form>
    </section>

    <!-- add playlist ends here -->

    <!-- footer section -->
    <?php include '../components/footer.php'; ?>
    <!-- custome js -->
    <script src="../javascript/admin-script.js"></script>
</body>
</html>