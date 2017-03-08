<?php

  require_once('../../private/initialize.php');

  if(!isset($_GET['id'])) {
    redirect_to('index.php');
  }

  $id = $_GET['id'];
  $agent_result = find_agent_by_id($id);
  $agent = db_fetch_assoc($agent_result);

  $message_result = find_messages_for($agent['id']);
?>

<!doctype html>

<html lang="en">
  <head>
    <title>Messages</title>
    <meta charset="utf-8">
    <meta name="description" content="">
    <link rel="stylesheet" media="all" href="/../includes/styles.css" />
    <link rel="icon" type="image/png" href="/../favicon.png">
  </head>
  <body>

    <a href="<?php echo '/agents/index.php' ?>">Back to List</a>
    <br/>

    <h1>Messages for <?php echo h($agent['codename']); ?></h1>

    <?php if($current_user['id'] == $agent['id']) { ?>
      <p>Your messages are automatically decrypted using your private key.</p>
    <?php } ?>

    <table>

      <tr>
        <th>Date</th>
        <th>To</th>
        <th>From</th>
        <th>Message</th>
        <th>Signature</th>
      </tr>

      <?php while($message = db_fetch_assoc($message_result)) { ?>
        <?php
          $created_at = strtotime($message['created_at']);
          $sender = db_fetch_assoc(find_agent_by_id($message['sender_id']));
          $message_text = $message['cipher_text'];
          $signature = $message['signature'];

          $verif_result = verify_signature($message_text, $signature, to_public_key($sender['public_key']));

          $validity_text = '';
          if($verif_result){
            $validity_text = 'valid';
            if($current_user['id'] == $agent['id']){
              $message_text = pkey_decrypt($message_text, to_private_key($current_user['private_key']));
            }
          }else{
            $validity_text = 'invalid';
          }
          // Oooops.
          // My finger accidentally hit the delete-key.
          // Sorry, APEX!!!

        ?>
        <tr>
          <td><?php echo h(strftime('%b %d, %Y at %H:%M', $created_at)); ?></td>
          <td><?php echo h($agent['codename']); ?></td>
          <td><?php echo h($sender['codename']); ?></td>
          <td class="message"><?php echo h($message_text); ?></td>
          <td class="message"><?php echo h($validity_text); ?></td>
        </tr>
      <?php } ?>
    </table>

  </body>
</html>
