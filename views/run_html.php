<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ucfirst($title) ?></title>
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
			<?php echo $this->benchmark->elapsed_time('0', strval(count($module_result_list))) ?>s, 
			<?php echo $this->benchmark->memory_usage() ?> )
		</span>
	</h1>
		
	<?php if ($failed > 0): ?>
	<h2 class="red">
		<?php echo $failed ?> FAILURE<?php echo ($failed > 1) ? 'S' : '' ?>
		<span class="text_12 normal lite_gray">
			( <?php echo count($module_result_list) ?> test, 
			<?php echo $total_assertions ?> assertion, 
			<?php echo $passed ?> passed, 
			<?php echo $failed ?> failed )
		</span>
	</h2>
	<?php else: ?>
	<h2 class="green">
		OK 
		<span class="text_12 normal lite_gray">
			( <?php echo count($module_result_list) ?> test, 
			<?php echo $total_assertions ?> assertion )
		</span>
	</h2>
	<?php endif ?>
	
	<?php foreach ($module_result_list as $module => $data): ?>
	<h3>
		<?php echo ucfirst(implode(' ', explode('_', $data['title']))) ?>
		<span class="text_12 normal lite_gray">( <?php echo $data['elapsed_time'] ?>s )</span>
	</h3>
	<div class="test_result">
		<?php foreach ($data['result_list'] as $assertion): ?>
		<h3>
			<a> 
				<span class="bold <?php echo ($assertion['Result'] == 'Passed') ? 'green' : 'red'; ?>">
					<?php echo $assertion['Result'] ?>
				</span> -
				<?php echo $assertion['Test Name'] ?>
			</a>
		</h3>
		<div>
			<table class="table">
			     <?php foreach ($assertion as $key => $value): ?>
		         <tr>
			         <th><?php echo ($key !== 'Notes') ? $key : 'Test Data' ?></th>
			         <td><?php echo ($key !== 'Notes') ? trim($value) : '<pre>'.trim($value).'</pre>' ?></td>
		         </tr>
				 <?php endforeach ?>
			 </table>
		</div>
		<?php endforeach ?>
	</div>
	<?php endforeach ?>
</div>
</body>
</html>