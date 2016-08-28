<?php
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

$fh = fopen('repertoire.txt', 'r');
while($line = fgets($fh)){
  $repertoire[] = $line;
}
fclose($fh);

function getTweetText() {
  global $repertoire;
  return $repertoire[rand(0, count($repertoire) - 1)];
}

function echon($s) {
  echo $s . "\n";
}

?>