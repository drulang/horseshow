<?php
	
	$dbh_horseshow = mysqli_connect("nyx", "frithi_webuser", "fartercartercc22@#", "frithi_HORSESHOW");

	 if(!$dbh_horseshow)
         {
             die("Error Connecting to MainLink");
         }

        
        $dbh_website = mysqli_connect("nyx", "frithi_webuser", "fartercartercc22@#", "frithi_HORSESHOW_WEBSITE");
        
        if(!$dbh_website)
        {
            die("Error connecting to WebLink");
        }
          
?>