<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $choice = $_POST['inputting'];
        
        $servername = "localhost";
        $username = "seenanfb";
        $password = "password1234";
        $dbname = "phpDB";
        
        $conn = new mysqli($servername, $username, $password);

        $db1 = "CREATE DATABASE phpDB";
        if ($conn->query($db1) === TRUE) 
            echo "";
         else 
            //echo "Error creating database: " . $conn->error;
        
        // Check connection
        if ($conn->connect_error)   
            die("Connection failed: " . $conn->connect_error);
        else 
            echo "";
        
        $conn->close();
        $conn = new mysqli($servername, $username, $password, $dbname);     
        
        $table = "CREATE TABLE phpform (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        username VARCHAR(30) NOT NULL,
        password VARCHAR(20) NOT NULL
        )";
        
        if ($conn->query($table) === TRUE) {
            echo "Table phpform created successfully";
        } else {
            //echo "Error creating table: " . $conn->error;
        }
        
        
        if (strlen($user) < 6){
        echo "Usernames must be at least 6 characters";
        return;
        }
        else
        //echo "Username Accepted";
            

        if (strlen($pass) < 8){
        echo "Password must be at least 8 characters";
        return;
        }
        else
        //echo "Password Accepted";

        $selectstmt = "SELECT username, password FROM phpform WHERE username = ? AND password = ?";
        //$selectstmtn = "SELECT username, password FROM phpform WHERE username = '".$user."' AND password = '".$pass."'";
        if($choice === "signin")
        {           
            if($stmt = $conn->prepare($selectstmt)){
                $stmt->bind_param('ss', $user, $pass);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($col1, $col2);
                while($stmt->fetch());
                    if($col1 === $user && $col2 === $pass)
                        echo "Hurray, you signed in";
                    else
                        echo "Username or password not found; please register if you haven't.";
            }
        }
        
        
        $regstmt = "INSERT INTO phpform (username, password) VALUES(?, ?)";
        $checkstmt = "SELECT username FROM phpform WHERE username = ?";
        
        if($choice === "register") 
        {
            if($cstmt = $conn->prepare($checkstmt)){
            $cstmt->bind_param('s', $user);
                if($cstmt->execute()){
                    $cstmt->store_result();
                    $user_check="";
                    $cstmt->bind_result($user_check);
                    $cstmt->fetch();
                        if($cstmt->num_rows != 0){
                            echo "Already Registered.";
                            return;
                        }
                    }
                }
            if($stmt = $conn->prepare($regstmt))
            {
                $stmt->bind_param('ss', $user, $pass);
                $stmt->execute();
                $result = $stmt->get_result();
                    if(isset($result))
                        echo "Yay, you registered";
                    else
                        echo "Registry failed";
            }
        }
        
        
        $conn->close();        
             
        
        ?>
    </body>
</html>
