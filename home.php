<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_tag'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $select_tags = $conn->prepare("SELECT * FROM `tags` WHERE name = ?");
   $select_tags->execute([$name]);

   if($select_tags->rowCount() > 0){
      $message[] = 'tag name already exist!';
   }else{

      $insert_tags = $conn->prepare("INSERT INTO `tags`(name,user_id) VALUES(?,?)");
      $insert_tags->execute([$name,$user_id]);
   }

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_tags = $conn->prepare("DELETE FROM `tags` WHERE id = ? and user_id=?");
   $delete_tags->execute([$delete_id,$user_id]);
   header('location:home.php');


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>tags</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="home.css">

</head>
<body>
   

<section class="add-tags">

   <h1 class="title">add new tags</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <input type="text" name="name" class="box" required placeholder="enter tag name">
         </div>
         
      </div>
      <input type="submit" class="btn" value="add tag" name="add_tag">
   </form>

</section>

<section class="show-tags">

   <h1 class="title">tags added</h1>

   <div class="box-container">

   <?php
      $show_tags = $conn->prepare("SELECT * FROM `tags` where user_id=?");
      $show_tags->execute([$user_id]);
      if($show_tags->rowCount() > 0){
         while($fetch_tags = $show_tags->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="name"><?= $fetch_tags['name']; ?></div>
      <div class="flex-btn">
         <a href="home.php?delete=<?= $fetch_tags['id']; ?>" class="delete-btn" onclick="return confirm('delete this tag?');">delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">now tags added yet!</p>';
   }
   ?>
   
   <a href="logout.php" class="btn">logout</a>
   </div>

</section>


</body>
</html>