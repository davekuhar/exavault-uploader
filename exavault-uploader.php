<?php
  /**
  * Plugin Name: Exavault Uploader
  * Plugin URI: 
  * Description: Exavault service to upload files.
  * Version: 1.0
  * Author: Magmalabs
  * Author URI:
  * License: MIT
  */
  
  require_once('V1Api.php');
  require_once('APIClient.php');

  function add_exavault_compress_api() {
    register_rest_route('exavault/v1', '/compress', array(
      'methods' => 'POST',
      'callback' => 'compress_exavault_files'
    ));
  }

  function compress_exavault_files() {
    $apiKey = 'XXXXXX';
    $apiUrl = 'https://api.exavault.com';
    $username = 'XXXXXX';
    $password = 'XXXXXX';

    
    $filesPath = $_POST['filePaths'];
    $archivePath = $_POST['archivePath'];

    // create a new instance of the ExaVault API library class
    $api = new V1Api( new APIClient($apiKey, $apiUrl) );
    $accessToken = null;

    // call the authenticateUser method, passing your username and password
    $response = $api->authenticateUser($username, $password);

    // save this result for later, we will need it to logout
    $loginSuccess = $response->success;

    // if authentication was successful, store the access token
    // obtained via the response body in the API instance.
    if ($loginSuccess) {
      $accessToken = $response->results->accessToken;
      $api->compressFiles($accessToken, $filesPath, $archivePath);

      $api->logoutUser($accessToken);
    }

    header("HTTP/1.1 200 OK");
  }

  add_action('rest_api_init', 'add_exavault_compress_api');
?>

