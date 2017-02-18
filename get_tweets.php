<?php
require_once('config.php');
require_once('libraries/phirehose/Phirehose.php');
require_once('libraries/phirehose/OauthPhirehose.php');

class Consumer extends OauthPhirehose {
  public function enqueueStatus($status) {
    $decoded = json_decode($status);
    if(tweetToMe($decoded)) {
      reply($decoded, getReplyText());
    }

    if(tweetByUser($decoded, "HillaryClinton")) {
      logtxt($decoded->user->screen_name . ': ' . $decoded->text);
    }

    if(tweetByUser($decoded, "realDonaldTrump")) {
      reply($decoded, getTrumpReplyText());
    }
  }
}

$stream = new Consumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
$stream->setFollow(array(
  25073877, // @realDonaldTrump
  1339835893, // @HillaryClinton
  769985823064395778 // @rstybot
));
$stream->consume();
?>