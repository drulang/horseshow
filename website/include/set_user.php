<?php

 include_once 'db_connect.php';
 
 $query = "SELECT ID, EMAIL from PERSON";
 
 $result = mysql_query($query);
 
 if($result)
 {
     while($row = mysql_fetch_array($result))
     {
         $ID = $row['ID'];
         $EMAIL = $row['EMAIL'];
         $LOGIN = $ID;
         $PASSWORD = sha1($ID);
         $GUID1 = uniqid();
         $GUID2 = uniqid();
         $GUID = "$GUID1$GUID2";
         $GUID = sha1($GUID);
         $GUID = strtoupper($GUID);
         
         $insertUserQuery = "insert ignore into USER values (0,'$LOGIN','$PASSWORD','$EMAIL',null,NOW(),NOW(),NOW(),0,0,'Y',null,'$GUID',500, null)";

         echo $insertUserQuery."\n";
         
         mysql_query($insertUserQuery);
         
         ##
         ##Get Newly created user
         ##
         $getUserQuery = "SELECT ID from USER where LOGIN_USERNAME = '$LOGIN' and LOGIN_PASSWORD = '$PASSWORD'";
         
         $userResults = mysql_query($getUserQuery);
         
         $row = mysql_fetch_row($userResults);
         $USERID = $row[0];
         
         ##Create link between person and user
         $createLink = "INSERT ignore into USER_has_PERSON values($USERID,$ID,NOW())";
         mysql_query($createLink);
         
     }
     
 }
 else
 {
     
 }
 

     $query = "SELECT ID, USER_ROLE_ID, PROFILE_ID from "
?>
