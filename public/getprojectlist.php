<?php
//require_once dirname(__DIR__).'/vendor/autoload.php';

//$gitlab = new \Gitlab\Client('http://mobisphere.asu.edu/api/v3/');
//$gitlab->authenticate('P2dybpLnNqsc4apDEie3', \Gitlab\Client::AUTH_URL_TOKEN);

//$project = $gitlab->api('projects')->all();
//echo "Project List:";
//var_dump (json_decode($project));

$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => 'http://mobisphere.asu.edu/api/v3/projects?private_token=P2dybpLnNqsc4apDEie3'
));

$response = curl_exec($curl);

curl_close($curl);

echo "Project List:";
$json_result = json_decode($response, true);

//echo "<pre>" . var_dump($result) . "</pre>";

foreach ($json_result as $proj) {
	echo "ID: ". $proj['id'] . "  project name: " . $proj['name'] . " Url: " . $proj['web_url'] . "<br>";
}

?>
~

