<?php 
  include_once 'include/layout.php';  
  require_once 'include/validation.php';	
  session_start();
  
  requireUserLoggedIn();
  	
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
     	 <?php 
           
             printManageProfileSideBar();
             
         ?>
    </div>
    <div id="main"> <a name="TemplateInfo"></a>
    	 <?php
         
            if($_GET['link'] == "myshows")
            {
                echo "Print MyShows Form";
            }
            elseif($_GET['link'] == "inactivateaccount")
            {
                printInactivateAccountForm();
            }
            else
            {
                printChangePasswordForm();
            }
            
	 ?>
        
    </div>
    <div id="rightbar">
  	   <?php printRightAdBar(); ?>
    </div>
  </div>
</div>
<div id="footer">
	<?php printFooter(); ?>
</div>
</body>
</html>




<?php

function printChangePasswordForm()
{
    
    echo "<h1>Change Password</h1>";
                echo 
                    '<form method="POST" action="mange_login?link=changepassword">
                        <p>
                          <label>Current Password</label>
                            <input type="text" name="currentpassword"  size="30"/>
                         <br/>
                         <br/>
                            <label>New Password</label>
                             <input name="password" type="password" size="30" /> 
                         <br/>
                         <label>Confirm New Password</label>
                             <input name="confirmpassword" type="password" size="30" /> 
                         <br/>
                        </p>
                        <input class="cancelbutton" type="submit" name="cancelupdatelogin" value="Cancel"/>
                        <input class="statebutton" type="submit" name="updatelogin" value="Update"/>
                     </form>    
                    ';
}

function printInactivateAccountForm()
{
    echo "Print Inactivate Account Form";
}

?>

