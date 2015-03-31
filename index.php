<?php

$ip = isset($argv[1])?$argv[1]:"";
$dir='../logfiles';
$bandwidth=0;
$files = scandir($dir);

foreach ($files as $file)
{
	if(!is_dir($file))
	{
		$handle = gzopen('../logfiles/'.$file, "rb");
		while (!feof($handle)) 
		{
			$line= str_replace('\"','', fgets($handle));
			$parts = explode('"', $line);
			if(!empty($ip)&&substr($parts[0], 0, strlen($ip))!==$ip) continue;
			else
			{
				$response = explode(' ', $parts[2], 3);
				$bandwidth+=$response[2];
				$ipstat[$response[1]]=empty($ipstat[$response[1]])?$response[2]:$ipstat[$response[1]]+$response[2];
			}
		
		}
	}
}

echo "total bandwidth: ".$bandwidth."\n";
foreach ($ipstat as $response=>$responseBandwidth)
{
	echo "response: " . $response. " bandwidth: ".$responseBandwidth . " percentage: " .$responseBandwidth*100/$bandwidth."%\n";
}
