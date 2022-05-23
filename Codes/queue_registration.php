<style>
	.left-side{
		display: flex;
		justify-content: center;
		align-items: center;
	}
	a.btn.btn-sm.btn-success {
    z-index: 99999;
    position: fixed;
    left: 1rem;
}
</style>
<?php include "admin/db_connect.php" ?>
<a href="index.php" class="btn btn-sm btn-success"><i class="fa fa-home"></i> Home</a>
<div class="left-side">
	<div class="col-md-10 offset-md-1">
		<div class="card">
			<div class="card-body">
				<div class="container-fluid">
					<form action="" id="new_queue">
						<div class="form-group">
							<label for="name" class="control-label">Name</label>
							<input type="text" id="name" name="name" class="form-control">
						</div>
						<div class="form-group">
							<label for="phone_number" class="control-label">Mobile Number</label>
							<input type="number" id="phone_number" name="phone_number" class="form-control">
						</div>
						<div class="form-group">
							<label for="transaction_id" class="control-label">Transaction</label>
							<select name="transaction_id" id="transaction_id" class="custom-select browser-default select2" require>
									<option></option>
									<?php
$trans = $conn->query("SELECT * FROM transactions where status = 1 order by name asc");
while ($row = $trans->fetch_assoc()):
?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
								<?php
endwhile; ?>
							</select>
						</div>
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-md-3 float-right">Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('.select2').select2({
		placeholder:"Please Select Here",
		width:"100%"
	})
	$('#new_queue').submit(function(e){
		e.preventDefault()
		start_load()
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
						$('#transaction_id').val('').select2({
							placeholder:"Please Select Here",
							width:"100%"
						})
/*						var nw = window.open("queue_print.php?id="+resp,"_blank","height=500,width=800")
						nw.print()
						setTimeout(function(){
							nw.close()
						},500)*/
						end_load()
						alert("Queue Registed Successfully,Your Token Number is "+resp,'success');
					    history.back();
					
					}
				}
			})
		
	})

</script>
