<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style>
* {
  box-sizing: border-box;
}

input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  resize: vertical;
}


label {
  padding: 12px 12px 12px 0;
  display: inline-block;
}

input[type=submit] {
  background-color: #fbb800;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  float: right;
}

.btn-home {
	background-color: #fbb800;
}

input[type=submit]:hover {
  background-color: #fbb800;
}

.container {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 100px;
}

.col-25 {
  float: left;
  width: 25%;
  margin-top: 6px;
}

.col-75 {
  float: left;
  width: 75%;
  margin-top: 6px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  .col-25, .col-75, input[type=submit] {
    width: 100%;
    margin-top: 0;
  }
}
.submit {
    z-index: 99999;
   
    right: 1rem;
}
.page-title {
    font-size: 150% !important;
    color: #444;
    font-weight: 400;
    text-transform: uppercase;
    position: relative;
}
</style>
</head>
<?php include "admin/db_connect.php" ?>

<div class="container">
	<h1><span class="base" data-ui-id="page-title-wrapper">Queue Registration</span></h1>
	<hr>
  <form action="" id="new_queue">
  	
  	<div class="row">
      <div class="col-75">
        <a href="index.php" class="btn btn-sm btn-home"><i class="fa fa-home"></i> Home</a>
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="fname">Name</label>
      </div>
      <div class="col-75">
        <input type="text" id="name" name="name" placeholder="Your name.." required>
        <input type="hidden" id="transaction_id" name="transaction_id" value="1">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="lname">Mobile Number</label>
      </div>
      <div class="col-75">
        <input type="text" id="phone_number" name="phone_number" placeholder="Your Mobile Number.." value="+65" required>
      </div>
    </div> <hr>
    <div class="submit">
      <input type="submit" value="Submit">
    </div>
  </form>
</div>


<!-- Modal HTML -->
    <div id="myModal" class="modal fade" tabindex="-1"  style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p id="dynamic-content"></p>
                </div>
            </div>
        </div>
    </div>


<script>
	$('#new_queue').submit(function(e){
		e.preventDefault()
		start_load()
			$('#dynamic-content').html(''); // leave it blank before ajax call
			$('#modal-loader').show();      // load ajax loader
			$.ajax({
				url:'admin/ajax.php?action=save_queue',
				method:'POST',
				data:$(this).serialize(),
				error:function(err){
					console.log(err)
					alert_toast("An error occured",'danger');
					alert_toast("Queue Registed Successfully",'success');
					end_load()
				},
				success:function(resp){
					if(resp > 0){
						$('#name').val('')
						$('#phone_number').val('')
						$('#transaction_id').val('');
						end_load()
						var msg = "Your queue number is"+resp +". We will send you a reminder when it is nearing your turn.";
						$('#dynamic-content').html('');    
						$('#dynamic-content').html(msg); // load response 
						$("#myModal").modal('show');
						//confirm(msg,'success');
						window.setTimeout(function () {
						  history.back();
						}, 4000 );
					    
					
					}
				}
			})
		
	})

</script>


