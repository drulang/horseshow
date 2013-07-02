<?php 
  include 'include/layout.php';  
  require 'include/validation.php';	
  session_start();
  
  requireUserLoggedIn();
  
  printHtmlHead();
  
  $userArray = SelectUserInformation($_SESSION['USER_ID']);
  	
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
            $name = $userArray['FirstName'].' '.$userArray['LastName'];
         
            $form .= '<div id="profile_wrapper">';

                $form .= '<div id="profile_info_user">';

                    $form .= '<ul class="side_menu">';
                    
                        $form .= '<li>Name: '.$name.'</li>';
                        $form .= '<li>Primary Email: '.$userArray['EmailPri'].'</li>';
                
                    $form .= "</ul>";
                
                $form .= '</div>'; #end profile_info



                $form .= '<div id="profile_image_user">

                            <img class="imgUser" src="images/user_icon_200.png"/>

                            <ul class="side_menu">
                            <li><a href="#">Change Profile Pic</a></li>
                            <a href="manage_profile.php">Edit Profile</a>
                            <li><a href="manage_login.php">Update Login</a></li>
                            </ul>';

                $form .= '</div>'; #end profile_image

                $form .= '<div id="profile_footer"></div>'; #footer

            $form .= "</div>"; #end profile_wrapper

             echo $form;
        
	 ?>
        <div class="cleaner"></div>
    </div> <!-- end of main column -->
    
  <div class="side_column_w200">
            	
        <div class="box">

          <?php 
           if($_SESSION['USER_ROLE_ID'] == 100)
           {
               printAdminProfileSideBar();
           }
           else if($_SESSION['USER_ROLE_ID'] == 500)
           {
               printUserProfileSideBar();
           }
           else
           {
               printSideBar();
           }  
         ?>

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






