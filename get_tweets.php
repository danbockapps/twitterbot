<?php
require_once('config.php');
require_once('libraries/phirehose/Phirehose.php');
require_once('libraries/phirehose/OauthPhirehose.php');

class Consumer extends OauthPhirehose {
  public function enqueueStatus($status) {
    echon('');
    $decoded = json_decode($status);
    echon($decoded->id);
    echon($decoded->user->screen_name);
    echon($decoded->text);
  }
}

$stream = new Consumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
$stream->setFollow(array(25073877));
$stream->consume();
?>