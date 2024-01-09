<?php

function base_api_url() {
  return "https://world-news-api-test.shuttleapp.rs";
}

function url($path='') {
  return 'http://localhost:3000/' . $path;
}

function create_post_request($payload, $endpoint) {

  $fields_string = json_encode($payload);

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, base_api_url().$endpoint);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER,
    array(
      'Content-Type:application/json',
    )
  );

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $res = curl_exec($ch);
  $json = json_decode($res);

  return $json;
}

function create_get_request($query, $endpoint) {

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, base_api_url().$endpoint);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $res = curl_exec($ch);
  $json = json_decode($res);

  return $json;
}

function get_user_id($token) {
  
  $decoded_token = explode('.', $token);
  $decoded_token = base64_decode($decoded_token[1]);
  $json = json_decode($decoded_token);

  $id = $json->sub;
  return $id;
}

