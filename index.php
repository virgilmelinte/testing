<?php

$ip = $argv[1];
$dir='logfiles';
$bandwidth=0;
$timestart = microtime(true);

$files = scandir($dir);
foreach ($files as $file)
{
	if(!is_dir($file))
	{
		$handle = gzopen('logfiles/'.$file, "rb");
		$contents = fread($handle, filesize('logfiles/'.$file));
		$tempfile = fopen("logfiles/temp.gz", "w");
		fwrite($tempfile, $contents);
		fclose($tempfile);

		$handle = gzopen("logfiles/temp.gz", "rb");
		while (!feof($handle)) {
			$line = fgets($handle);
			$parts = explode('"', $line);
			if(substr($parts[0], 0, strlen($ip))==$ip){
			$response = explode(' ', $parts[2], 3);
			//echo $cnt." ". substr($parts[0], 0, strlen($ip))." ". $response[1]."->".$response[2]."<br/>";
			$bandwidth+=$response[2];
			}
		}
	}
}
$timeEnd=  microtime(true);
$time = $timeEnd-$timestart;

echo "bandwidth: ".$bandwidth." " .$time;
