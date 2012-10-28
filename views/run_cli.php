<?php 

echo PHP_EOL.$title.PHP_EOL;

$test_result = ($failed > 0) ? "\033[30;41m".$failed.' FAILURES!' : "\033[30;42m".'OK';

if($failed > 0)
{
	echo PHP_EOL;
	echo ($failed > 1) 
			? 'There were '.$failed.' failures'
			: 'There was '.$failed.' failure';
	echo PHP_EOL.PHP_EOL;

	$loop = 1;

	foreach ($module_result_list as $module => $data)
	{
		foreach ($data['failed_list'] as $key => $failur) 
		{
			echo PHP_EOL.($loop).') '.$failur['Test Name'].PHP_EOL.PHP_EOL;
			echo 'Expected: '.$failur['Expected Datatype'].PHP_EOL.PHP_EOL;
			echo 'Actual: '.$failur['Test Datatype'].PHP_EOL.PHP_EOL;
			echo 'Test Data: '.$failur['Notes'].PHP_EOL.PHP_EOL;
			echo $failur['File Name'].':'.$failur['Line Number'].PHP_EOL.PHP_EOL;
		}
		
		$loop++;
	}
}

echo 	PHP_EOL.
		'Time: '.$this->benchmark->elapsed_time('0', strval(count($module_result_list))).' seconds, '.
		'Memory: '.$this->benchmark->memory_usage().
		PHP_EOL.
		PHP_EOL;

echo $test_result.' ('.count($module_result_list).' ';
echo (count($module_result_list) > 1) ?	'tests, ' : 'test, ';
echo $total_assertions.' ';
echo ($total_assertions > 1) ? 'assertions' : 'assertion';
echo ')';
echo "\033[K\033[0m".PHP_EOL;
?>