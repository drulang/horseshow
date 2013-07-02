<?php  
  include_once 'include/db_func.php';
  require_once 'include/recaptchalib.php';
  require_once 'include/layout.php';
  session_start();
  
    if(isset($_SESSION['LOGIN_NAME']))
    {
            header("Location: profile.php");
    }
	
    if(isset($_SESSION['INVALID_LOGIN_ATTEMPT']))
    {
        $_SESSION['INVALID_LOGIN_ATTEMPT']++;        
    }
    else 
    {
        $_SESSION['INVALID_LOGIN_ATTEMPT'] = 0;
    }
    
  $username = strtoupper(filter_var($_POST["username"], FILTER_SANITIZE_STRING));
  $username = preg_replace('#\W#', '', $username);
  
  $password = sha1($_POST["password"]);

  $invalidLogin = FALSE;
  
  if($username != '' and $password != null)
  {
      $loginResult = CheckLogin($username, $password);
      
      if($loginResult)
      {
	  header("Location: profile.php");
      }
      else 
      {
          $invalidLogin = TRUE;
      }
  }
	
?>
    
<?php 
  printHtmlHead();
?>
<body>

<?php 
    //DIV TEMPLATEMO_TOP_BAR
    printTopBar();
?>

<div id="templatemo_banner_bar_wrapper">

    <div id="templatemo_banner_bar">
        <?php
            printLogo();
        ?>
    </div> <!-- end of banner -->
    
</div> <!-- end of banner wrapper -->

<div id="templatemo_menu_wrapper">

    <div id="templatemo_menu">
    	<?php
            printMenu();
        ?>
    </div> <!-- end of menu -->
    
</div> <!-- end of menu wrapper -->

<div id="templatemo_content_small">

    <div id="main_column_small">
        
      	 <?php
            if($invalidLogin)
            {
                printFailedLogin($_SESSION['INVALID_LOGIN_ATTEMPT']);
            }
            else
            {
                printLogin();
            } 
	 ?>
        <div class="cleaner"></div>
    </div> <!-- end of main column -->
    
  <div class="side_column_w200">
            	
        <div class="box">

           <?php
         printLoginSideBar();
           ?>
        </div> 
                
                
        <div class="box">

            <h3>Partners</h3>
            <ul class="side_menu">
                <li><a href="http://www.templatemo.com" target="_parent">CSS Templates</a></li>

            </ul>
        </div>
  </div> 
    <!-- end of side column 1 -->
    <?php
        //Print Ad bar
        printRightAdBar();
    ?>

	<div class="cleaner"></div>
</div> <!-- end of content -->
    <?php
        printFooter();
    ?>
</body>
</html>