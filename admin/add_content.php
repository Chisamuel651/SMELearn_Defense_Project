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
        $desired_length1 = 12;
        $value =  base64_encode(openssl_random_pseudo_bytes($desired_length));
        $value1 = base64_encode(openssl_random_pseudo_bytes($desired_length1));

        // var_dump($value);

        $status = $_POST['status'];
        $status = filter_var($status, FILTER_SANITIZE_STRING);

        $title = $_POST['title'];
        $title = filter_var($title, FILTER_SANITIZE_STRING);

        $description = $_POST['description'];
        $description = filter_var($description, FILTER_SANITIZE_STRING);

        $playlist_id = $_POST['playlist'];
        $playlist_id = filter_var($playlist_id, FILTER_SANITIZE_STRING);

        $video = $_FILES['video']['name'];
        $video = filter_var($video, FILTER_SANITIZE_STRING);
        $video_ext = pathinfo($video, PATHINFO_EXTENSION);
        // $rename = create_unique_id().'.'.$ext;
        $rename_video = $title.'.'.$video_ext;
        $video_tmp_name = $_FILES['video']['tmp_name'];
        // $video_size = $_FILES['video']['size'];
        $video_folder = '../course_content_files/'.$rename_video;

        $thumb = $_FILES['thumb']['name'];
        $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
        $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
        // $rename = create_unique_id().'.'.$ext;
        $rename_thumb = $title.'.'.$thumb_ext;
        $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
        $thumb_size = $_FILES['thumb']['size'];
        $thumb_folder = '../course_content_files/'.$rename_thumb;

        $verify_content = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? AND title = ? AND description = ?");
        $verify_content->execute([$tutor_id, $title, $description]);

        if($verify_content->rowCount() > 0){
            $message[] = 'course content already exist';
        }else{
            if($thumb_size > 2000000){
                $message[] = 'image is too large';
            }else{
                $add_content = $conn->prepare("INSERT INTO  `content`(tutor_id, playlist_id, title, description, video, thumb, status) VALUES (?,?,?,?,?,?,?)");
                $add_content->execute([$tutor_id, $playlist_id, $title, $description, $rename_video, $rename_thumb, $status]);
                move_uploaded_file($thumb_tmp_name, $thumb_folder);
                move_uploaded_file($video_tmp_name, $video_folder);
                $message[] = 'new content has been created';
            }
        }       
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Content | Admin</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>
    <!-- add content starts here -->
    <section class="crud-form">
        <h1 class="heading">add content</h1>

        <form action="" method="post" enctype="multipart/form-data" >
            <p>content status <span>*</span></p>
            <select class="box" name="status" required>
                <option value="active" selected>Active</option>
                <option value="deactive">Deactive</option>
            </select>

            <p>content title <span>*</span></p>
            <input type="text" name="title" class="box" maxlength="100" placeholder="enter course title" required>

            <p>content description <span>*</span></p>
            <textarea name="description" class="box" required placeholder="enter course description" maxlength="1000" cols="30" rows="10"></textarea>

            <select name="playlist" class="box" required>
                <option value="" disabled selected>-- select playlist --</option>
                <?php 
                    $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
                    $select_playlist->execute([$tutor_id]);

                    if($select_playlist->rowCount() > 0){
                        while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){                
                ?>
                <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
                <?php 
                        }
                    }else{
                        echo '<option value="" disabled>no playlist available</option>';
                    }
                ?>
                
            </select>

            <p>select thumbnail <span>*</span></p>
            <input type="file" name="thumb" required class="box" required accept="image/*">

            <p>select video <span>*</span></p>
            <input type="file" name="video" required class="box" required accept="video/*">

            <input type="submit" value="add content" name="submit" class="btn">
        </form>
    </section>

    <!-- add content ends here -->
    <!-- footer section -->
    <?php include '../components/footer.php'; ?>
    <!-- custome js -->
    <script src="../javascript/admin-script.js"></script>
</body>
</html>