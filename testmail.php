<?php

   $to = 'toto@gmail.com';
   $subject = 'Salutation';
   $message = 'Bonjour comment tu vas?';
   $headers = "From: info@vintedclone.com\r\n";

   if (mail($to, $subject, $message, $headers)) {
       echo 'SUCCESS';
   } else {
       echo 'ERROR';
   }
