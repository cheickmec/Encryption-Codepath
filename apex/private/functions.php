<?php

  function h($string="") {
    return htmlspecialchars($string);
  }

  function u($string="") {
    return urlencode($string);
  }

  function raw_u($string="") {
    return rawurlencode($string);
  }

  function redirect_to($location) {
    header("Location: " . $location);
    exit;
  }

  function url_for($script_path) {
    return DOC_ROOT . $script_path;
  }

  function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }

  function is_get_request() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
  }

  function display_errors($errors=array()) {
    $output = '';
    if (!empty($errors)) {
      $output .= "<div class=\"errors\">";
      $output .= "Please fix the following errors:";
      $output .= "<ul>";
      foreach ($errors as $error) {
        $output .= "<li>{$error}</li>";
      }
      $output .= "</ul>";
      $output .= "</div>";
    }
    return $output;
  }

  function stringInsert($str,$pos,$insertstr)
{
    if (!is_array($pos))
        $pos=array($pos);

    $offset=0;
    asort($pos);
        foreach($pos as $p)
        {

    $str = substr($str, 0, $p+$offset) . $insertstr . substr($str, $p+$offset);
            $offset = $offset + strlen($insertstr);
        }
    return $str;
}
/*Format public string for usage OpenSSL functions*/
function to_public_key($key_str){
  return stringInsert($key_str, [26,91 - 1,156 - 2,221 - 3,286 - 4,351 - 5,416 - 6,425 - 7,450 - 8],"\n");
}
/*Format private string for OpenSSL functions*/
function to_private_key($key_str){

  return stringInsert($key_str, [27, 92-1, 157-2, 222-3,287-4, 352-5,417-6,482-7,547-8,612-9,677-10,742-11,807-12,872-13,937-14,1002-15,1067-16,1132-17,1197-18,1262-19,1327-20,1392-21,1457-22,1522-23,1587-24,1652-25,1677-26,1703-27],"\n");
}
/*Return array of indexes of all occurences of given needle*/
function strpos_all($haystack, $needle) {
    $offset = 0;
    $allpos = array();
    while (($pos = strpos($haystack, $needle, $offset)) !== FALSE) {
        $offset   = $pos + 1;
        $allpos[] = $pos;
    }
    return $allpos;
}
?>
