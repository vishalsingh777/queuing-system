<?php
include 'db_connect.php';
$msg ='';
$qry = $conn->query("SELECT * from msg_temp limit 1");
if($qry->num_rows > 0){
	foreach($qry->fetch_array() as $k => $val){
		$msg = $val; 
	}
}
 ?>
<div class="container-fluid">
	
	<div class="card col-lg-12">
		<div class="card-body">
			<form action="" id="save-msg">
				<div class="form-group">
					<label for="msg" class="control-label">Message</label>
					<input type="text" class="form-control" id="msg" name="msg" value = "<?php echo $msg ?>"  required>
					<p>*Use {{QUEUE_NUMBER}} in the msg to send the Queue number in the msg </p>
				</div>
				<center>
					<button class="btn btn-info btn-primary btn-block col-md-2">Save</button>
				</center>
			</form>
		</div>
	</div>

<script>

	$('#save-msg').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_msg',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			error:err=>{
				console.log(err)
			},
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.','success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})

	})
</script>

</div>