<?php
session_start();

if(isset($_SESSION['userId'])){
    $userId = $_SESSION['userId'];
    $clientId = $userId;
    $ticketNumErr = $messageIn = $messageErr = "";
    if(isset($_POST['submit'])) {
        //Get values from form
        $messageIn = $_POST['message'];
        $ticketNum = $_POST['ticketNum'];
        $issueDate = $_POST['issueDate'];
        //validation on fields
        if(empty($_POST["ticketNum"])) {
            $ticketNumErr = "Enter Ticket Number";
        }
        else if(empty($_POST["message"])) {
            $messageErr = "Enter Message";
        }
        else {
            //create a new dom document
            $doc = new DOMDocument();
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;

            //load xml file
            $doc->load('xml/ticket.xml');

            //define xpath
            $xpath = new DOMXPath($doc);
            $parent = $xpath->query("//ticket[@ticketNumber = $ticketNum]/supportMessages");

            //create elements and append
            $message = $doc->createElement('message',$messageIn);
            $msgDate = $doc->createAttribute('msgDate');
            $msgDate->value = $issueDate;
            $userId = $doc->createAttribute('userId');
            $userId->value = $clientId;
            $message->appendChild($msgDate);
            $message->appendChild($userId);


            //append
            $parent->item(0)->appendChild($message);




            //save xml file with changes
            $doc->save('xml/ticket.xml');
            header('Location:clientTicketInfo.php');
        }






    }
}
else {
    header('Location:login.php');
}//session else



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
    <title>Help Desk-Add a Message</title>

</head>
<body class="bg-danger text-white">
<?php
include 'header.php';?>
<div class="container main-page">
    <section>
        <div class="row p-5">
            <h1 class="col-md-5 text-center text-uppercase">Add Message</h1>
            <button class="btn btn-dark mr-1"><p class="col-md-2 text-center">Welcome, User #<span id="client"><?= $_SESSION['userId']; ?></span></p></button>
            <a class="col-md-1 btn btn-dark mr-1 text-white" href="logout.php" title="log out">LogOut</a>
            <a class="col-md-2 btn btn-dark text-white" href="clientTicketInfo.php" title="display ticket">View Tickets</a>
        </div>

        <form action="" method="post">
            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for="ticketNum">Ticket Number: </label>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="ticketNum" value="<?= $_POST['ticketNum']; ?>" />
                </div>
                <?= $ticketNumErr;?>
            </div>
            <div>
                <input type="hidden" name="issueDate" id="date" />
            </div>
            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for="message">Message: </label>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="message" value="<?= $messageIn; ?>" />
                </div>
                <?= $messageErr;?>
            </div>

            <div class="form-group row">
                <div class="offset-md-4 col-md-8">
                    <button class="btn btn-dark text-uppercase" type="submit" name="submit" onClick="getDate();">ADD</button>
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
<script src="js/script.js"></script>
</body>
</html>