

function getDate() {
    var d = new Date();
    var day = d.getDate();
    var month = d.getMonth() + 1; // The months are 0-based
    var year = d.getFullYear();
    // Set the value of the "date" field
    document.getElementById("date").value = year + "-" + month + "-" + day;
}
