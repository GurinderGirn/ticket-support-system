<?php
session_start();
    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
        $ticketNumErr = $statusIn = $statusErr = "";
        if(isset($_POST['edit'])) {
            //Get values from form
            $statusIn = $_POST['statusIn'];
            $ticketNum = $_POST['ticketNum'];
            //validation on fields
            if(empty($_POST["ticketNum"])) {
                $ticketNumErr = "Enter Ticket Number";
            }
            else if(empty($_POST["statusIn"])) {
                $statusErr = "Enter Status";
            }
            else {
                //create a new dom document
                $doc = new DOMDocument();
                $doc->preserveWhiteSpace = false;
                $doc->formatOutput = true;

                //load xml file
                $doc->load('xml/ticket.xml');

                //define XPath

                $xpath = new DOMXPath($doc);
                $elements = $xpath->query("//ticket[@ticketNumber = $ticketNum]");
                $elements->item(0)->setAttribute("status", $statusIn);

                //save xml file with changes
                $doc->save('xml/ticket.xml');
                header('Location:staffTicketInfo.php');
            }
        }

    }
    else {
        header("Location:login.php");
    }

//function for drop down list of category data
$statusDD = ['-- Choose status --' => '', 'on-going' => 'on-going', 'resolved' => 'resolved'];
function droplist_status($statusDD) {

    foreach($statusDD as $key => $value) {
        echo "<option value='$value'";
        if (isset($_POST['statusIn'])) {
            $statusIn = $_POST['statusIn'];
            if ($statusIn == $value) {
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
    <title>Help Desk-Edit Status</title>

</head>
<body class="bg-danger text-white">
<?php
include 'header.php';?>
<div class="container main-page">
    <section>
        <div class="row p-5">
            <h1 class="col-md-4 text-center text-uppercase">Edit Status</h1>
            <button class="btn btn-dark mr-1"><p class="col-md-2 text-center">Welcome, Staff #<span id="staff"><?= $_SESSION['userId']; ?></span></p></button>
            <a class="col-md-2 btn btn-dark mr-1 text-white" href="logout.php" title="log out">LogOut</a>
            <a class="col-md-2 btn btn-dark text-white" href="staffTicketInfo.php" title="display ticket">View Tickets</a>
        </div>

        <form action="" method="post">
            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for="ticketNum">Ticket Number: </label>
                <div class="col-md-4">
                    <input class="form-control" type="text" name="ticketNum" value="<?= $_POST['ticketNum']; ?>" />
                </div>
                <?= $ticketNumErr;?>
            </div>
            <div class="form-group row">
                <label class="form-control-label text-md-right col-md-4 col-form-label" for="statusIn">Status: </label>
                <div class="col-md-4">
                    <select class="form-control" value="<?= $statusIn ?>"  name="statusIn">
                        <?= droplist_status($statusDD); ?>
                    </select>
                </div>
                <?= $statusErr;?>
            </div>

            <div class="form-group row">
                <div class="offset-md-4 col-md-8">
                    <button class="btn btn-dark text-uppercase" type="submit" name="edit">UPDATE</button>
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