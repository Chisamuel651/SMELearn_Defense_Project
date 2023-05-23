<?php 
    include '../components/connect.php';
    if(isset($_COOKIE['tutor_id'])){
        $tutor_id = $_COOKIE['tutor_id'];
    }else{
        $tutor_id = '';
        header('location:login.php');
    }

    if(isset($_GET['get_id'])){
        $get_id = $_GET['get_id'];
    }else{
        $get_id = '';
        header('location: playlists.php');
    }

    if(isset($_POST['update'])){
        $status = $_POST['status'];
        $status = filter_var($status, FILTER_SANITIZE_STRING);

        $title = $_POST['title'];
        $title = filter_var($title, FILTER_SANITIZE_STRING);

        $description = $_POST['description'];
        $description = filter_var($description, FILTER_SANITIZE_STRING);

        $playlist_id = $_POST['playlist'];
        $playlist_id = filter_var($playlist_id, FILTER_SANITIZE_STRING);

        $update_content = $conn->prepare("UPDATE `content` SET title = ?, description = ?, status = ? WHERE id = ?");
        $update_content->execute([$title, $description, $status, $get_id]);
        

        if(!empty($playlist_id)){
            $update_playlist = $conn->prepare("UPDATE `content` SET playlist_id = ? WHERE id = ?");
            $update_playlist->execute([$playlist_id, $get_id]);
        }

        $old_video = $_POST['old_video'];
        $old_video = filter_var($old_video, FILTER_SANITIZE_STRING);
        

        $video = $_FILES['video']['name'];
        $video = filter_var($video, FILTER_SANITIZE_STRING);
        $video_ext = pathinfo($video, PATHINFO_EXTENSION);
        // $rename = create_unique_id().'.'.$ext;
        $rename_video = $title.'.'.$video_ext;
        $video_tmp_name = $_FILES['video']['tmp_name'];
        $video_size = $_FILES['video']['size'];
        $video_folder = '../course_content_files/'.$rename_video;

        if(!empty($video)){
            
            $update_video = $conn->prepare("UPDATE `content` SET thumb = ? WHERE id = ?");
            $update_video->execute([$rename_video, $get_id]);
            move_uploaded_file($video_tmp_name, $video_folder);
            if($old_video != '' AND $old_video != $rename_video){
                unlink('../course_content_files/'.$old_video);
            }
        }

        $old_thumb = $_POST['old_thumb'];
        $old_thumb = filter_var($old_thumb, FILTER_SANITIZE_STRING);

        $thumb = $_FILES['thumb']['name'];
        $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
        $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
        // $rename = create_unique_id().'.'.$ext;
        $rename_thumb = $title.'.'.$thumb_ext;
        $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
        $thumb_size = $_FILES['thumb']['size'];
        $thumb_folder = '../course_content_files/'.$rename_thumb;

        if(!empty($thumb)){
            if($thumb_size > 2000000){
                $message[] = 'image is too large!';
            }else{
                $update_thumb = $conn->prepare("UPDATE `content` SET thumb = ? WHERE id = ?");
                $update_thumb->execute([$rename_thumb, $get_id]);
                move_uploaded_file($thumb_tmp_name, $thumb_folder);
                if($old_thumb != ''){
                    unlink('../course_content_files/'.$old_thumb);
                }
            }
        }

        $message[] = 'content updated!';
    }

    if(isset($_POST['delete_content'])){
        $delete_id = $_POST['content_id'];
        $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

        $verify_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
        $verify_content->execute([$delete_id]);

        if($verify_content->rowCount() > 0){
            $fetch_content = $verify_content->fetch(PDO::FETCH_ASSOC);
            unlink('../course_content_files/'.$fetch_content['thumb']);
            unlink('../course_content_files/'.$fetch_content['video']);

            $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
            $delete_comment->execute([$delete_id]);

            $delete_like = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
            $delete_like->execute([$delete_id]);

            $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
            $delete_content->execute([$delete_id]);

            header('location: contents.php');
        }else{
            $message[] = 'content already deleted!';
        }
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Content</title>
    <!-- font awesome cdnjs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <!-- header section -->
    <?php include '../components/admin_header.php'; ?>
    <!-- update content starts here -->
    <section class="crud-form">
        <h1 class="heading">update content</h1>

        <?php 
            $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
            $select_content->execute([$get_id]);

            if($select_content->rowCount() > 0){
                while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){  
        ?>
        <form action="" method="post" enctype="multipart/form-data" >
            <input type="hidden" name="content_id" value="<?= $fetch_content['id']; ?>">
            <input type="hidden" name="old_video" value="<?= $fetch_content['video']; ?>">
            <input type="hidden" name="old_thumb" value="<?= $fetch_content['thumb']; ?>" >
            <p>content status</p>
            <select class="box" required name="status">
                <option value="<?= $fetch_content['status']; ?>" selected><?= $fetch_content['status']; ?></option>
                <option value="active">Active</option>
                <option value="deactive">Deactive</option>
            </select>

            <p>content title</p>
            <input type="text" required name="title" class="box" maxlength="100" value="<?= $fetch_content['title']; ?>" placeholder="enter course title">

            <p>content description</p>
            <textarea name="description" required class="box" placeholder="enter course description" maxlength="1000" cols="30" rows="10"><?= $fetch_content['description']; ?></textarea>

            <select name="playlist" class="box">
                <option value="<?= $fetch_content['playlist_id']; ?>" selected>-- select playlist --</option>
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

            <p>update thumbnail</p>
            <img src="../course_content_files/<?= $fetch_content['thumb']; ?>" class="media" alt="">
            <input type="file" name="thumb" class="box" accept="image/*">

            <p>update video</p>
            <video src="../course_content_files/<?= $fetch_content['video']; ?>" controls autoplay class="media"></video>
            <input type="file" name="video" class="box" accept="video/*">

            <input type="submit" value="update content" name="update" class="btn">
            <div class="flex-btn">
                <a href="view_content.php?get_id=<?= $get_id; ?>" class="option-btn">view content</a>
                <input type="submit" value="delete content" onclick="return confirm('do you want to delete this content?')" name="delete_content" class="delete-btn">
            </div>
        </form>

        <?php 
                }
            }else{
                echo '<p class="empty">content was not found!</p>';
            }
        ?>
    </section>

    <!-- update content ends here -->

    <!-- footer section -->
    <?php include '../components/footer.php'; ?>
    <!-- custome js -->
    <script src="../javascript/admin-script.js"></script>
</body>
</html>