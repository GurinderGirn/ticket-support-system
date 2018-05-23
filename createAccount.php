<?php
$typeIn = $firstName = $lastName  = $middleName = $eMail = $password = "";
$typeErr = $fNameErr = $lNameErr = $eMailErr = $passErr = $result =  "";

    if(isset($_POST['submit'])) {
        //Get values from form
        $typeIn = $_POST['typeIn'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $middleName = $_POST['middleName'];
        $eMail = $_POST['eMail'];
        $password = $_POST['password'];
        $pattern = "/^c\d+$/";


        //validation on fields
        if(empty($_POST['typeIn'])) {
            $typeErr = "Enter Type";
        }

        else if(empty($_POST["firstName"])) {
            $fNameErr = "Enter First Name";
        }
        else if(empty($_POST["lastName"])) {
            $lNameErr = "Enter Last Name";
        }

        else if(empty($_POST['eMail'])) {
            $eMailErr = "Enter E-mail";
        }
        else if(!filter_var($eMail, FILTER_VALIDATE_EMAIL)){
            $eMailErr = "*Enter valid E-mail";
        }

        else if(empty($_POST["password"])) {
            $passErr = "Enter Password";
        }
        else {
            //create a new dom document
            $doc = new DOMDocument();
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;

            $xml = simplexml_load_file("xml/user.xml");
            $ids = $xml->xpath("//user/@userId"); // select all ids
            $newid = max(array_map("intval", $ids)) + 1; // change objects to `int`, get `max()`, + 1

            //load xml file
            $doc->load('xml/user.xml');

            //define xpath
            $parent_path = '//user';
            $xpath = new DOMXPath($doc);
            $parent = $xpath->query($parent_path);

            //create elements and append
            $user = $doc->createElement('user');

            $userId = $doc->createAttribute('userId');
            $userId->value = $newid;
            $user->appendChild($userId);
            $type = $doc->createAttribute('type');
            $type->value = $typeIn;
            $user->appendChild($type);


            $userName = $doc->createElement('userName');
            $fName = $doc->createElement('firstName',$firstName);
            $mName = $doc->createElement('middleName',$middleName);
            $lName = $doc->createElement('lastName',$lastName);
            $userName->appendChild($fName);
            $userName->appendChild($mName);
            $userName->appendChild($lName);
            $user->appendChild($userName);

            $email = $doc->createElement('userEmail',$eMail);
            $pass = $doc->createElement('password',$password);

            $user->appendChild($email);
            $user->appendChild($pass);

            $doc->documentElement->appendChild($user);

            //save xml file with changes
            $doc->save('xml/user.xml');


            $typeIn = $firstName = $lastName  = $middleName = $eMail = $password = "";
            $typeErr = $fNameErr = $lNameErr = $eMailErr = $passErr = "";
            $result = "New Account Created. Your Account User ID is " . $newid;
        }
    }

//function for drop down list of category data
$typeDD = ['-- Choose Category --' => '', 'client' => 'client', 'staff' => 'staff'];
function droplist_type($typeDD) {

    foreach($typeDD as $key => $value) {
        echo "<option value='$value'";
        if (isset($_POST['typeIn'])) {
            $typeIn = $_POST['typeIn'];
            if ($typeIn == $value) {
                echo 'selected';
            }
        }
        echo ">" . $key . "</option>";
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
    <title>Help Desk-Create Account</title>

    <script>
        function getDate() {
            var d = new Date();
            var day = d.getDate();
            var month = d.getMonth() + 1; // The months are 0-based
            var year = d.getFullYear();
            // Set the value of the "date" field
            document.getElementById("date").value = day + "/" + month + "/" + year;
        }
    </script>
</head>
<body class="bg-danger text-white">
<?php
//header of page
include 'header.php';?>
<!-- main page content -->
<div class="container main-page containerMargin">
    <section>
        <div class="row p-5">
            <h1 class="col-md-12 text-center text-uppercase">Register</h1>
        </div>

        <form action="" method="post">

            <div class="text-white text-center"><?= $result; ?></div>
            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for="typeIn">Type: </label>
                <div class="col-md-4">
                    <select class="form-control" value="<?= $typeIn ?>"  name="typeIn">
                        <?= droplist_type($typeDD); ?>
                    </select>
                </div>
                <?= $typeErr;?>
            </div>


            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for="firstName">First Name: </label>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="firstName" value="<?= $firstName; ?>" />
                </div>
                <?= $fNameErr;?>
            </div>
            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for="middleName">Middle Name: </label>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="middleName" value="<?= $middleName; ?>" />
                </div>
            </div>
            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for="lastName">Last Name: </label>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="lastName" value="<?= $lastName; ?>" />
                </div>
                <?= $lNameErr;?>
            </div>

            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for=eMail>E-mail: </label>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="eMail" value="<?= $eMail; ?>" />
                </div>
                <?= $eMailErr;?>
            </div>
            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for="password">Password: </label>
                <div class="col-md-4">
                    <input class="form-control" type="password" name="password" value="<?= $password; ?>" />
                </div>
                <?= $passErr;?>
            </div>

            <div class="form-group row">
                <div class="offset-md-4 col-md-8">
                    <button class="btn btn-dark text-uppercase" type="submit" name="submit" onClick="getDate();">SUBMIT</button>
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