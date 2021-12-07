<?php
session_start();
$username = $_SESSION['username'];
$activepage = "Login";
$activeurl = 'login';
include '.env.php';
include 'header.php';
if (isset($_SESSION['username']))
    header('location: userpage')
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta content="ZyalpNET Login Page" property="og:title" />
        <meta content="Log in to ZyalpNET" property="og:description" />
        <meta content="http://www.zyalp.com/main/login.php" property="og:url" />
    </head>
    <body>
        <div class="PageContent">
            <!-- Top PageContent -->
            <h1>Login</h1>
            <div>
                <form method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" placeholder="Username"/>
                <label for="password">Password:</label>
                <input type="password" name="password" placeholder="Password">

                <input class="submit" type="submit" value="Logg inn" name="submit" />
                <p>Or click <a class="register" href="registration.php">here</a> to register a new user </p>
            </div>
        </form>    
            <!-- Bottom PageContent -->
        </div>
    </body>
</html>

<!-- Example Comment -->
    <?php
        if(isset($_POST['submit'])){
            //Taking the POST method and defining Variables with it
            $username = $_POST['username'];
            $password = $_POST['password'];

            
            //Connect to the database
            $dbc = mysqli_connect('localhost', "$dbuser", "$dbpwd", "$dbname")
              or die('Error connecting to MySQL server.');
            
            $Salt = mysqli_query($dbc, "SELECT `Salt` from `Logins` where `Username`='$username'");

            $row = $Salt->fetch_assoc();
            $salt = $row['Salt'];           
            $hashedpass=hash('sha256', $password.$salt);

            $query = "SELECT `Username`, `Password` from `Logins` where `Username`='$username' and `Password`='$hashedpass'";
            
            //Query the database
            $result = mysqli_query($dbc, $query)
              or die('Error querying database.');
            
            //Close the connection
            mysqli_close($dbc);

            //A check that verifies the credentials given
            if($result->num_rows > 0){
                //Execute this code if credentials are valid
                session_start();
                $_SESSION['username'] = $username;

                header('location: userpage');
            }else{
                //Execute this code if credentials are invalid
                echo 'Invalid Login';
            }
        }
    ?>
</html>