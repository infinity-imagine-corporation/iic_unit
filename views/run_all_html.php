<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Integrated test for <?php echo ucfirst($title) ?></title>
<?php $this->load->view('asset') ?>
<script type="text/javascript">
$(function() 
{
	$(".test_result").accordion({ active: false, collapsible: true, autoHeight: false });
});
</script>
</head>
<body>
<div id="content">
	<h1>
		<?php echo $title ?> 
		<span class="text_12 normal">
			( 
			<?php echo $this->benchmark->elapsed_time('start_integrate', 'end_integrate') ?>s, 
			<?php echo $this->benchmark->memory_usage() ?> )
		</span>
	</h1>
		
	<?php if ($failed > 0): ?>
	<h2 class="red">
		<?php echo $failed ?> FAILURE<?php echo ($failed > 1) ? 'S' : '' ?>
		<span class="text_12 normal lite_gray">
			( <?php echo count($total_test) ?> tests, 
			<?php echo $total_assertions ?> assertions, 
			<?php echo $passed ?> passed, 
			<?php echo $failed ?> failed )
		</span>
	</h2>
	<?php else: ?>
	<h2 class="green">
		OK 
		<span class="text_12 normal lite_gray">
			( <?php echo $total_test ?> tests, 
			<?php echo $total_assertions ?> assertions )
		</span>
	</h2>
	<?php endif ?>
	
	<div class="test_result">
	<?php foreach ($module_result_list as $module => $data): ?>
		<h3>
			<a>
				<span class="bold <?php echo ($data['failed'] == 0) ? 'green' : 'red'; ?>">
					<?php echo ($data['failed'] == 0) ? 'Passed' : 'Failed'; ?>
				</span> -
				<?php echo $data['title'] ?>
				<span class="text_12 normal lite_gray">
					( <?php echo $data['elapsed_time'] ?>s )
				</span>
			</a>
		</h3>
		<div></div>
	<?php endforeach ?>
	</div>
</div>
</body>
</html>