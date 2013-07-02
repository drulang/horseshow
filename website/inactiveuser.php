<?php 
  include_once 'include/layout.php';  
  include_once 'include/db_func.php';
  session_destroy();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php printTitle(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="images/Orange.css" type="text/css" />
</head>
<body>
<div id="wrap">
  <div id="header">
		<?php printHeader() ?>
  </div>
  <div id="menu">
  	 <?php printMenu(); ?>
  </div>
  <div id="content-wrap">
    <div id="sidebar">
     	 <?php printLoginSideBar(); ?>
    </div>
    <div id="main"> <a name="TemplateInfo"></a>
    	 <?php
            echo "<p>Your account has beeen inactivated for some reason.  Please send us an email at admin@modelhorseshow.com to start the reactivation process.</p>";
	    echo "<p>Your privacy is our absolute priority.  Please be patient as the reactivation process may take time.</p>";
            echo "<p>Thank you for being a part of modelhorseshow.com and we really do understand your frustration!</p>";
            echo '<p><a href="activateaccount.php">Do you have an activation key by chance?</a></p>';
            echo "<br/>";
        ?>
    </div>
    <div id="rightbar">
  	   <?php #printRightAdBar(); ?>
    </div>
  </div>
</div>
<div id="footer">
	<?php printFooter(); ?>
</div>
</body>
</html>
