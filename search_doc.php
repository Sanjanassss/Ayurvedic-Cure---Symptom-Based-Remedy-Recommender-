<?php
require 'connect.inc.php';

if (isset($_POST['search_doc'])) {
   $search_doc = $_POST['search_doc'];
   $query = "SELECT * FROM `doctor` 
              WHERE `Doc_name` LIKE '%" . mysqli_real_escape_string($GLOBALS['conn'], $search_doc) . "%'
                 OR `Doc_email` LIKE '%" . mysqli_real_escape_string($GLOBALS['conn'], $search_doc) . "%'
                 OR `id` LIKE '%" . mysqli_real_escape_string($GLOBALS['conn'], $search_doc) . "%'
                 OR `type` LIKE '%" . mysqli_real_escape_string($GLOBALS['conn'], $search_doc) . "%'
                 OR `tags` LIKE '%" . mysqli_real_escape_string($GLOBALS['conn'], $search_doc) . "%'
                 OR `info` LIKE '%" . mysqli_real_escape_string($GLOBALS['conn'], $search_doc) . "%'
                 OR `city` LIKE '%" . mysqli_real_escape_string($GLOBALS['conn'], $search_doc) . "%'";

   if ($query_run = mysqli_query($GLOBALS['conn'], $query)) {
      $mysqli_num_rows = mysqli_num_rows($query_run);

      if ($mysqli_num_rows >= 1) {
         while ($query_result = mysqli_fetch_assoc($query_run)) {
            echo '<b>ID:</b> ' . $query_result['id'] . '<br>
                      <b>Name:</b> ' . $query_result['Doc_name'] . '<br>
                      <b>Type:</b> ' . $query_result['type'] . '<br>
                      <b>Email:</b> ' . $query_result['Doc_email'] . '<br>
                      <b>Contact:</b> ' . $query_result['doc_contact'] . '<br>
                      <b>Info:</b> ' . $query_result['info'] . '<br>
                      <b>Tags:</b> ' . $query_result['tags'] . '<br>
                      <b>City:</b> ' . $query_result['city'] . '<hr><br>';
         }
      } else {
         echo 'No result found';
      }
   } else {
      echo 'Query is not running';
   }
}
?>