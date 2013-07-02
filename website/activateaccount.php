<?php 
  include 'include/layout.php';  
  include 'include/db_func.php';
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
         
            if(isset($_GET['key']) && $_GET['key'] != '' && isset($_GET['username']) && $_GET['username'] != '')
            {
                $key = strtoupper(filter_var($_GET["key"], FILTER_SANITIZE_STRING));
                $key = preg_replace('#\W#', '', $key);
                
                $username = strtoupper(filter_var($_POST["username"], FILTER_SANITIZE_STRING));
                $username = preg_replace('#\W#', '', $username);

                if(CheckActivationKey($username, $key))
                {
                    $_SESSION['activationkey']= $key;
                    
                    header(("Location: create_user.php"));
                    #Need to activate account at the end of the create user session
                    #ActivateAccount($username, $key);
                }
                else
                {
                   echo 
                    '
                        Activation Code was not accepeted.
                        <form method="GET" action="activateaccount.php">
                                <p>
                                    <label>Username</label>
                                    <input name="username" type="text" size="30" />
                                    <br/>
                                    <label>Activtion Key</label>
                                    <input name="key" type="text" size="30" />
                                    <br/>
                                    <br/>
                                    <input class="button" type="submit" />
                                </p>
                        </form>
                        <br />
                        '; 
                }
              
            }
            else
            {
                echo 
                    '
                        <form method="GET" action="activateaccount.php">
                                <p>
                                    <label>Username</label>
                                    <input name="username" type="text" size="30" />
                                    <br/>
                                    <label>Activtion Key</label>
                                    <input name="key" type="text" size="30" />
                                    <br/>
                                    <br/>
                                    <input class="button" type="submit" />
                                </p>
                        </form>
                        <br />
                        ';
            }
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
