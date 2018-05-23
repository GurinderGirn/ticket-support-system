<?php
session_start();
$loginErr = $loginName = $passErr = $password = $error = "";
//on clicking login button
if(isset($_POST['login'])) {
    $loginName = $_POST['loginName'];
    $password = $_POST['password'];
    //validate input fields
    if(empty($loginName)) {
        $loginErr = "*Enter Login ID";
    }
    else if(empty($password)) {
        $passErr = "*Enter password";
    }
    else {

        $xml = simplexml_load_file("xml/user.xml");
        $xml->saveXML("xml/user.xml");
        for($i=0;$i<count($xml->user);$i++){
            if(($xml->user[$i]['userId'] == $loginName)&& ($xml->user[$i]->password == $password) && ($xml->user[$i]['type'] == 'client')) {
                $_SESSION['userId'] = $loginName;
                header('Location:submitTicket.php');
                break;
            }
            else if(($xml->user[$i]['userId'] == $loginName)&& ($xml->user[$i]->password == $password) && ($xml->user[$i]['type'] == 'staff')) {
                $_SESSION['userId'] = $loginName;
                header('Location:staffTicketInfo.php');
                break;
            }
            else {
                $error = "username or password does not match" ;
            }
        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Help Desk - Log in</title>
</head>
<body class="bg-danger text-white">
<?php include 'header.php';?>
<div class="container main-page">
    <section>
        <h1 class="text-uppercase p-5 text-center">Log In</h1>
        <form action="" method="post">
            <div class="form-group row">
                <label for="loginName" class="form-control-label text-md-right col-md-4 col-form-label">User Id: </label>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="loginName" value="<?= $loginName; ?>" />
                </div>
                <?= $loginErr;?>

            </div>

            <div class="form-group row">
                <label for="password" class="form-control-label text-md-right col-md-4 col-form-label">Password: </label>
                <div class="col-md-4">
                    <input type="password" class="form-control" name="password" value="<?= $password; ?>" />
                </div>
                <?= $passErr;?>
            </div>
            <div class="form-group row">
                <div class="offset-md-4 col-md-8">
                    <button type="submit" name="login" class="btn btn-dark">LOG IN</button>
                </div>
                <div class="text-center m-auto">
                <?= $error; ?>
                </div>
            </div>
        </form>
    </section>
</div>

<?php include 'footer.php';?>

<!-- script files -->
<script src="js/jquery-3.3.1.slim.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>