<?php
session_start();
if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
}
else {
    header('Location:login.php');
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
    <title>View Tickets</title>
</head>
<body class="bg-danger text-white" id="main-page">
<?php include 'header.php';?>
<div class="container main-page containerMargin">
    <section>
        <div class="row p-5">
            <h1 class="col-md-4 text-center text-uppercase">Support Ticket</h1>
            <button class="btn btn-dark mr-1"><p class="col-md-2  text-center">Welcome, User #<span id="client"><?= $_SESSION['userId']; ?></span></p></button>
            <a class="col-md-2 btn btn-dark mr-1 text-white" href="logout.php" title="log out">LogOut</a>
            <a class="col-md-2 btn btn-dark text-white" href="submitTicket.php" title="submit ticket">Submit New Ticket</a>
        </div>

        <table border="4" class="p-1">
            <thead>
            <tr>
                <th>Ticket Number</th>
                <th>Category</th>
                <th>Status</th>
                <th>Date Of Issue</th>
                <th>clientID</th>
                <th>StaffID</th>
                <th>Options </th>
            </tr>
            </thead>
            <tbody id="result">
            </tbody>
        </table>

    </section>
    <div id="output" class="mt-3 containerMargin"></div>
</div>


<?php include 'footer.php';?>
<!-- script files -->
<script src="js/jquery-3.3.1.slim.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/clientTicketInfo.js"></script>

</body>
</html>
