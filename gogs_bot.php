<?php
//Powered By Loli Tech
//Create at 2017/5/7 23:35
$json = file_get_contents('php://input');
$json = json_decode($json, true);
$commit_message = $json['commits'][0]['message'];
$commit_link = $json['commits'][0]['url'];
$commiter = $json['commits'][0]['committer']['name'];
$repoName = $json['repository']['name'];

$token = 'bot' . 'YOUR_BOT_TOKEN';
$chat_id = 'CHAT_ID';

$added_files = '';
$removed_files = '';
$modified_files = '';
if (count($json['commits'][0]['added']) == 0) {
	$added_files = '无';
} else {
	for ($i = 0; $i < count($json['commits'][0]['added']); $i++) {
		$added_files .= $json['commits'][0]['added'][$i] . PHP_EOL;
	}
}
if (count($json['commits'][0]['removed']) == 0) {
	$removed_files = '无';
} else {
	for ($i = 0; $i < count($json['commits'][0]['removed']); $i++) {
		$removed_files .= $json['commits'][0]['removed'][$i] . PHP_EOL;
	}
}
if (count($json['commits'][0]['modified']) == 0) {
	$modified_files = '无';
} else {
	for ($i = 0; $i < count($json['commits'][0]['modified']); $i++) {
		$modified_files .= $json['commits'][0]['modified'][$i] . PHP_EOL;
	}
}
$commit_time = $json['commits'][0]['timestamp'];

$send_api = 'https://api.telegram.org/' . $token . '/sendMessage';

$text = '<b>检测到新的Git Push</b>' . PHP_EOL . PHP_EOL .
	'项目名称:' . $repoName . PHP_EOL .
	'提交用户:' . $commiter . PHP_EOL .
	'提交时间:' . $commit_time . PHP_EOL .
	'内容:' . $commit_message . PHP_EOL .
	'增加文件:' . PHP_EOL . '<pre>' . $added_files . '</pre>' . PHP_EOL .
	'删除文件:' . PHP_EOL . '<pre>' . $removed_files . '</pre>' . PHP_EOL .
	'更改文件:' . PHP_EOL . '<pre>' . $modified_files . '</pre>' . PHP_EOL .
	'<a href="' . $commit_link . '">查看本次更改</a>';

http_post_contents(['chat_id' => $chat_id, 'text' => $text, 'parse_mode' => 'HTML', 'disable_web_page_preview' => 'true'], $send_api);

function http_post_contents($data, $address) {
	$data = http_build_query($data);
	$opts = array(
		'http' => array(
			'method' => "POST",
			'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
			"Content-length:" . strlen($data) . "\r\n" .
			"Cookie: esuwiki=god\r\n" .
			"\r\n",
			'content' => $data,
		),
	);
	$cxContext = stream_context_create($opts);
	$sFile = file_get_contents($address, false, $cxContext);
	return $sFile;
}