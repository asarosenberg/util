<?php
session_start();

# Add URL to test here
define("URL", 'https://'.rand());
define("TRIES", 10);

if ($_REQUEST["sess"]=='dest') {
    session_destroy();
}
if (!isset($_SESSION["count"] )) {
    $_SESSION["count"] = 0;
}

function localTtfbTest() {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, URL);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

	curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	return $info;
}

if ($_REQUEST["sess"] == 'start') {

	$info = localTtfbTest();
	$ttfb = round($info['starttransfer_time'],2);
	$ttfb = str_replace('.', ',', $ttfb); 

	$http_code = $info['http_code'];
	
	$_SESSION["ttfb"] = $_SESSION["ttfb"].'http_code: '.$http_code.' ttfb: '.$ttfb.'<br>';
	echo $_SESSION["ttfb"];
	$_SESSION["count"] ++;

	echo '<br/><br/>';

	echo $_SESSION["count"];
}
?>

<br/>
<br/>

<?php
if ( ($_SESSION["count"] < TRIES) && ($_REQUEST["sess"] == 'start') ) {
    echo '<script>location.reload(); </script>';
}

?>

<br/>
<br/>

<?php if ($_REQUEST["sess"]=='dest') { ?>
<a href="?sess=start">Start</a>
<?php } else { ?>
<a href="?sess=dest">Restart</a>
<?php } ?>
