
function updateStatus(button, ticketId) {
	var ticketId = $(button).prevAll(".ticketId").val();

	$.post("includes.php", { status: status, ticketId: ticketId })
	.done(function(error) {

		if(error != "") {
			alert(error);
			return;
		}

		//do something when ajax returns
		openPage("dashboard2.php");
	});
}
