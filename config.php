<?php
require_once('libraries/codebird.php');
$cb = \Codebird\Codebird::getInstance();

foreach(parse_ini_file("auth.ini") as $key => $value) {
  define($key, $value);
}

$requiredConstants = array(
  'TWITTER_CONSUMER_KEY',
  'TWITTER_CONSUMER_SECRET',
  'OAUTH_TOKEN',
  'OAUTH_SECRET',
);

foreach($requiredConstants as $rc) {
  if(!defined($rc)) {
    throw new Exception('Required constant not defined: ' + $rc);
  }
}

\Codebird\Codebird::setConsumerKey(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
$cb->setToken(OAUTH_TOKEN, OAUTH_SECRET);

$fh = fopen('repertoire.txt', 'r');
while($line = fgets($fh)){
  if(strlen($line) > 1) {
    $repertoire[] = $line;
  }
}
fclose($fh);

function getReplyText() {
  global $repertoire;
  return $repertoire[rand(0, count($repertoire) - 1)];
}

function echon($s) {
  echo $s . "\n";
}

function logtxt($s) {
  file_put_contents(
    'log.txt',
    date("Y-m-d G:i:s") . "\n" . $s . "\n\n",
    FILE_APPEND
  );
}

function logtxtshort($s) {
  file_put_contents(
    'logshort.txt',
    date("Y-m-d G:i:s") . ' ' . $s . "\n",
    FILE_APPEND
  );
}

function replyable($decoded) {
  if(in_array($decoded->user->screen_name, array('realDonaldTrump', 'HillaryClinton'))) {
    return true;
  }

  else if(
    strlen($decoded->text) >= 8 &&
    substr($decoded->text, 1, 7) == 'rstybot' &&
    $decoded->user->screen_name != 'rstybot'
  ) {
    return true;
  }

  else {
    return false;
  }
}

function tweetToMe($decoded) {
  if(
    strlen($decoded->text) >= 8 &&
    substr($decoded->text, 1, 7) == 'rstybot' &&
    $decoded->user->screen_name != 'rstybot'
  ) {
    return true;
  }
}

function tweetByUser($decoded, $userHandle) {
  return $decoded->user->screen_name == $userHandle;
}

function reply($decoded, $tweetText) {
  global $cb;
  $params = [
    'status' => '@' . $decoded->user->screen_name . ' ' . $tweetText,
    'in_reply_to_status_id' => $decoded->id
  ];
  $reply = $cb->statuses_update($params);
  if($reply->httpStatus != 200) {
    logtxt(print_r($reply, true));
  }
}

?>