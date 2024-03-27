<?php
@include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $user_type = $_POST['user_type'];

   // Check if admin already exists
   $admin_check_query = "SELECT * FROM user_form WHERE user_type = 'admin' LIMIT 1";
   $admin_result = mysqli_query($conn, $admin_check_query);
   $admin_user = mysqli_fetch_assoc($admin_result);

   if ($admin_user && $user_type === 'admin') {
       $error[] = 'Admin user already exists!';
   } else {

       if($pass != $cpass){
           $error[] = 'Passwords do not match!';
       } else {
           $insert = "INSERT INTO user_form (id, name, email, password, user_type) VALUES (NULL, '$name', '$email', '$pass', '$user_type')";

           mysqli_query($conn, $insert);
           header('location:login.php');
       }
   }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="style1.css">
</head>
<body>
<div class="form-container">
<form action="" method="post">
      
      <h3>register now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="text" name="name" required placeholder="enter your name">
      <input type="email" name="email" required placeholder="enter your email">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="password" name="cpassword" required placeholder="confirm your password">
      <select name="user_type">
         <option value="user">user</option>
         <option value="admin">admin</option>
      </select>
      <input type="submit" name="submit" value="register now" class="form-btn">
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>
   
</body>
</html>