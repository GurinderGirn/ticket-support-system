<?php
session_start();
if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    //echo "<h1>Welcome " . $userId . "</h1>" ;
    $categoryIn = $message = "";
    $categoryErr = $issueDateErr = $messageErr = "";


    if(isset($_POST['submit'])) {
        //Get values from form
        $categoryIn = $_POST['categoryIn'];
        $issueDate = $_POST['issueDate'];
        $clientTId = $userId;
        $message = $_POST['message'];

        //validation on fields
        if(empty($_POST["categoryIn"])) {
            $categoryErr = "<li>enter category</li>";
        }
        else if(empty($_POST["issueDate"])) {
            $issueDateErr = "<li>enter date of issue</li>";
        }
        else if(empty($_POST["message"])) {
            $messageErr = "<li>enter message</li>";
        }
        else {
            if($categoryIn == 'technical' || $categoryIn == 'maintenance')
            {
                $staffTId = "101";
            }
            else if($categoryIn == 'sales' || $categoryIn == 'administration') {
                $staffTId = "103";
            }
            else {
                $staffTId = "";
            }
            //create a new dom document
            $doc = new DOMDocument();
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;

            //load xml file
            $doc->load('xml/ticket.xml');

            //define xpath
            $parent_path = '//ticket';
            $xpath = new DOMXPath($doc);
            $parent = $xpath->query($parent_path);

            $xml = simplexml_load_file("xml/ticket.xml");
            $ids = $xml->xpath("//ticket/@ticketNumber"); // select all ids
            $newid = max(array_map("intval", $ids)) + 1; // change objects to `int`, get `max()`, + 1
            //create elements and append
            $ticket = $doc->createElement('ticket');

            $ticketNumber = $doc->createAttribute('ticketNumber');
            $ticketNumber->value = $newid;
            $ticket->appendChild($ticketNumber);

            $category = $doc->createAttribute('category');
            $category->value = $categoryIn;
            $ticket->appendChild($category);

            $status = $doc->createAttribute('status');
            $status->value = "on-going";
            $ticket->appendChild($status);

            $dateOfIssue = $doc->createElement('dateOfIssue',$issueDate);
            $clientId = $doc->createElement('clientId',$clientTId);
            $staffId = $doc->createElement('staffId',$staffTId);
            $ticket->appendChild($dateOfIssue);
            $ticket->appendChild($clientId);
            $ticket->appendChild($staffId);

            $supportMessages = $doc->createElement('supportMessages');
            $message = $doc->createElement('message',$message);
            $msgDate = $doc->createAttribute('msgDate');
            $msgDate->value = $issueDate;
            $userId = $doc->createAttribute('userId');
            $userId->value = $clientTId;
            $message->appendChild($msgDate);
            $message->appendChild($userId);
            $supportMessages->appendChild($message);
            $ticket->appendChild($supportMessages);

            $doc->documentElement->appendChild($ticket);

            //save xml file with changes
            $doc->save('xml/ticket.xml');
        }
        header("Location:clientTicketInfo.php");
    }

}
else {
    header("Location:login.php");
}


//function for drop down list of category data
$categoryDD = ['-- Choose Category --' => '', 'Technical' => 'technical', 'Sales' => 'sales' , 'Maintenance' => 'maintenance', 'Administration' => 'administration'];
function droplist_category($categoryDD) {

    foreach($categoryDD as $key => $value) {
        echo "<option value='$value'";
        if (isset($_POST['categoryIn'])) {
            $categoryIn = $_POST['categoryIn'];
            if ($categoryIn == $value) {
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
    <title>Help Desk-Submit Ticket</title>

</head>
<body class="bg-danger text-white">
<?php
include 'header.php';?>
<div class="container main-page">
    <section>
        <div class="row p-5">
            <h1 class="col-md-4 text-center text-uppercase">Support Ticket</h1>
            <button class="btn btn-dark mr-1"><p class="col-md-2 text-center">Welcome, User #<span id="client"><?= $_SESSION['userId']; ?></span></p></button>
            <a class="col-md-2 btn btn-dark mr-1 text-white" href="logout.php" title="log out">LogOut</a>
            <a class="col-md-2 btn btn-dark text-white" href="clientTicketInfo.php" title="display ticket">View Tickets</a>
        </div>

        <form action="" method="post">
            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for="categoryIn">Category: </label>
                <div class="col-md-4">
                    <select class="form-control" value="<?= $categoryIn ?>"  name="categoryIn">
                        <?= droplist_category($categoryDD); ?>
                    </select>
                </div>
                <?= $categoryErr;?>
            </div>
                <div>
                    <input type="hidden" name="issueDate" id="date" />
                    <?= $issueDateErr;?>
                </div>
                <div class="form-group row">
                    <label class="form-control-label text-md-right col-md-4 col-form-label" for="message">Message: </label>
                    <div class="col-md-4">
                        <input class="form-control" type="text" name="message" value="<?= $message; ?>" />
                    </div>
                    <?= $messageErr;?>
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
<script src="js/script.js"></script>
</body>
</html>