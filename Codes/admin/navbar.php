
<style>
</style>
<nav id="sidebar" class='mx-lt-5 bg-dark' >
		
		<div class="sidebar-list">
				<?php if($_SESSION['login_type'] == 1): ?>

				<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
				<a href="index.php?page=transactions" class="nav-item nav-transactions"><span class='icon-field'><i class="fa fa-list"></i></span> Store List</a>	
				<a href="index.php?page=windows" class="nav-item nav-windows"><span class='icon-field'><i class="fa fa-square-full"></i></span> Window List</a>	
				<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
				<a href="index.php?page=msg_temp" class="nav-item nav-msg_temp"><span class='icon-field'><i class="fa fa-cogs"></i></span> Message Template</a>
				
			<?php else: ?>
				<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
			<?php endif; ?>
		</div>

</nav>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
