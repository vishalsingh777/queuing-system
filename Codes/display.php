<style>
	.left-side{
		background: #ffffffc7;
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
<?php
$tname = $conn->query("SELECT * FROM transactions where id =" . $_GET['id'])->fetch_array() ['name'];
function nserving()
{
    include "admin/db_connect.php";

    $query = $conn->query("SELECT q.*,t.name as wname FROM queue_list q inner join transaction_windows t on t.id = q.window_id where date(q.date_created) = '" . date('Y-m-d') . "' and q.transaction_id = '" . $_GET['id'] . "' and q.status = 1 order by q.id desc limit 1  ");
    if ($query->num_rows > 0)
    {
        foreach ($query->fetch_array() as $key => $value)
        {
            if (!is_numeric($key)) $data[$key] = $value;
        }
        return json_encode(array(
            'status' => 1,
            "data" => $data
        ));
    }
    else
    {
        return json_encode(array(
            'status' => 0
        ));

    }
    $conn->close();
}
?>
<a href="index.php" class="btn btn-sm btn-success"><i class="fa fa-home"></i> Home</a>
<div class="left-side">
	<div class="col-md-10 offset-md-1">
		<div class="card">
			<div class="card-body">
				<div class="container-fluid">
					<div class="card">
							<div class="card-body bg-primary"><h4 class="text-center text-white"><b><?php echo strtoupper($tname) ?></b></h4></div>
						</div>
						<br>
					<div class="card">
						<div class="card-header bg-primary text-white"><h3 class="text-center"><b>Now Serving</b></h3></div>
						<div class="card-body">
							<h4 class="text-center" id="sname"></h4>
							<hr class="divider">
							<h3 class="text-center" id="squeue"></h3>
							<hr class="divider">
							<h5 class="text-center" id="window"></h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		var queue = '';
		var renderServe = setInterval(function(){
			$.ajax({
				url:'admin/ajax.php?action=get_queue',
				method:"POST",
				data:{id:'<?php echo $_GET['id'] ?>'},
				success:function(resp){
					resp = JSON.parse(resp)
					$('#sname').html(resp.data.name)
					$('#squeue').html(resp.data.queue_no)
					$('#window').html(resp.data.wname)
				}
			})
			
		},1500)
	})

</script>
