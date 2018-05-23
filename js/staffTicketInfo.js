var xml = new XMLHttpRequest();
var xmldoc;
xml.onload = function() {
    xmldoc = xml.responseXML;
    generateTable();
}
xml.onerror = function() {
    console.log("Error getting XML file..");
}
xml.open("POST","xml/ticket.xml");
xml.responseType = "document";
xml.send();

function generateTable() {
    var table = document.getElementById('result');
    var dateIssued = xmldoc.getElementsByTagName('dateOfIssue');
    var clientId = xmldoc.getElementsByTagName('clientId');
    var getId = document.getElementById('staff').textContent;
    var staffId = xmldoc.getElementsByTagName('staffId');
    var tickets = xmldoc.getElementsByTagName('ticket');

    table.innerHTML = '';
    for(var i=0; i < tickets.length; i++)
    {
        if (staffId[i].textContent == getId) {
            table.innerHTML += '<tr><td><button type="submit" class="viewbtn btn btn-dark m-1">' + tickets[i].getAttribute('ticketNumber') + '</button></td><td>'
                + tickets[i].getAttribute('category') + '</td><td>'
                +
                '<form action="editStatus.php" method="post">' +
                tickets[i].getAttribute('status')+
                '<input type="hidden" name="ticketNum" value="' + tickets[i].getAttribute('ticketNumber') + '" />' +
                '<button type="submit" name="editStatus" class="editbtn btn btn-dark m-1"><a href="editStatus.php">Edit</a></button></form>' + '</td><td>'
                + dateIssued[i].textContent + '</td><td>'
                + clientId[i].textContent + '</td><td>'
                + staffId[i].textContent + '</td>'
                + '<td>' +'<form action="addMessage.php" method="post"><input type="hidden" name="ticketNum" value="' + tickets[i].getAttribute('ticketNumber') + '" />'
                +'<button type="submit" name="addMessage" class="addbtn btn btn-dark m-1"><a href="addMessage.php">Add Message</a></button></form>'  + '</td>'
                + '</tr>';
        }
    }
    //display ticket information
    var viewbtns = document.getElementsByClassName('viewbtn');
    for (var i = 0; i < viewbtns.length; i++) {
        //add button click handler
        viewbtns[i].onclick = function(){
            viewTicket(this);
        }
    }
}
function viewTicket(btn) {
    var getContent = btn.textContent;
    var getDiv = document.getElementById('output');
    var tickets = xmldoc.getElementsByTagName('ticket');

    var supportMessages = xmldoc.getElementsByTagName('supportMessages');
    var messages = xmldoc.getElementsByTagName('message');
    var clientId = xmldoc.getElementsByTagName('clientId');
    var staffId = xmldoc.getElementsByTagName('staffId');

    for(var i=0; i < tickets.length; i++) {
        if (tickets[i].getAttribute('ticketNumber') == getContent) {
            getDiv.innerHTML = "<h2 class='text-center'>Ticket Details</h2>"
                + "<div><strong>Ticket Number: </strong>" + tickets[i].getAttribute('ticketNumber') + "</div>"
                + "<h3>Support Messages:</h3>";
            for (var j = 0; j < supportMessages[i].childElementCount; j++) {
                getDiv.innerHTML += "<div>User ID: " + supportMessages[i].children[j].getAttribute('userId') + "</div>" +
                    "<div>Date: " + supportMessages[i].children[j].getAttribute('msgDate') + "</div>" +
                    "<div class='borderBottom'> Message: " + supportMessages[i].children[j].textContent + "</div>";
            }
        }
    }

}
