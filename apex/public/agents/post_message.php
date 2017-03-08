<?php

  require_once('../../private/initialize.php');

  if(isset($_POST['submit']) && isset($_POST['plain_text'])) {

    if(!isset($_GET['id'])) {
      redirect_to('index.php');
    }

    $id = $_GET['id'];
    $agent_result = find_agent_by_id($id);
    // No loop, only one result
    $agent = db_fetch_assoc($agent_result);

    //$sender_result = find_agent_by_id('1');

    $sender = $current_user;//db_fetch_assoc($sender_result);

    $plain_text = $_POST['plain_text'];

    $private_key = $sender['private_key'];

    $public_key = $agent['public_key'];

    $public_key = to_public_key($public_key);
    $private_key = to_private_key($private_key);


    $encrypted_text = pkey_encrypt($plain_text, $public_key);
    $signature = create_signature($encrypted_text, $private_key);

    $message = [
      'sender_id' => $sender['id'],
      'recipient_id' => $agent['id'],
      'cipher_text' => $encrypted_text,
      'signature' => $signature
    ];
    $result = insert_message($message);
    if($result === true) {
      // Just show the HTML below.
    } else {
      $errors = $result;
    }

  } else {
    redirect_to('index.php');
  }

?>

<!doctype html>

<html lang="en">
  <head>
    <title>Message Dropbox</title>
    <meta charset="utf-8">
    <meta name="description" content="">
    <link rel="stylesheet" media="all" href="/../includes/styles.css" />
    <link rel="icon" type="image/png" href="/../favicon.png">
  </head>
  <body>

    <a href="<?php echo url_for('/index.php'); ?>">Back to List</a>
    <br/>

    <h1>Message Dropbox</h1>

    <div>
      <p><strong>The message was successfully encrypted and saved.</strong></p>

      <div class="result">
        Message:<br />
        <?php echo h($encrypted_text); ?><br />
        <br />
        Signature:<br />
        <?php echo h($signature); ?>
      </div>
    </div>

  </body>
</html>
