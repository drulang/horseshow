<?php 
  include_once 'include/layout.php';  
  require_once 'include/validation.php';	
  session_start();
  
  requireUserLoggedIn();
  	
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

<div id="templatemo_content">

	<div id="main_column">
            
            
    	 <?php
            //MAIN CONTENT
            if($_POST['cancel'] == "Cancel")
            {
                echo '<h5> Profile Update Page</h5>
                      <p>This is where you can update your profile!  Start by clicking a link on the left.  That way <-- </p>
                     ';
            }
            elseif($_GET['link'] == "addressinfo")
            {
                echo "print Address Form";
            }
            elseif($_GET['link'] == "contactinfo")
            {
                echo "print Contact form";
            }
            elseif($_GET['link'] == "nameinfo")
            {
                printNameInfoForm();
            }
            else
            {
                echo '<h4> Profile Page </h4>
                      <p>This is where you can update your profile!  Start by clicking a link on the left.  That way <-- </p>
                     ';
            }
            
	 ?>
        <div class="cleaner"></div>
    </div> <!-- end of main column -->
    
  <div class="side_column_w200">
            	
        <div class="box">

           <?php
               printManageProfileSideBar();
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


<?php

function printNameInfoForm()
{
    $firstname = filter_var($_POST["firstname"], FILTER_SANITIZE_STRING);
    $firstname = preg_replace('#\W#', '', $firstname);
    
    $middlename = filter_var($_POST["middlename"], FILTER_SANITIZE_STRING);
    $middlename = preg_replace('#\W#', '', $middlename);
    
    $lastname = filter_var($_POST["lastname"], FILTER_SANITIZE_STRING);
    $lastname = preg_replace('#\W#', '', $lastname);
    
    $nickname = filter_var($_POST["nickname"], FILTER_SANITIZE_STRING);
    $nickname = preg_replace('#\W#', '', $nickname);
    
    //Error Flags
    $firstnameError = FALSE;
    $middlenameError = FALSE;
    $lastnameError = FALSE;
    $nicknameError = FALSE;
    
    $form = '<h4>Name Information Profile</h4>';
    
    
    $form .=    '<form method="POST" action="manage_profile.php?link=nameinfo">
                 <p>';
       
    /*
     * Validate First Name
     */
    $firstNameErrorMessage = validateRequiredName($firstname);
    if(strlen($firstNameErrorMessage) > 0 && $_POST['updateprofile'] == "Update")
    {
        $firstnameError = TRUE;
        $form .= '<label>First Name</label>
                  <input type="text" name="firstname" value="'.$firstname.'"/> '.$firstNameErrorMessage.'
                  <br/>';
    }
    elseif (strlen($firstNameErrorMessage) == 0 && $_POST['updateprofile'] == "Update") 
    {
        $firstnameError = FALSE;
        $form .= '<label>First Name</label>
                  <input type="text" name="firstname" value="'.$firstname.'"/>
                  <br/>';
    }
    else
    {
        $form .= '<label>First Name</label>
                  <input type="text" name="firstname" value="'.$_SESSION['FIRST_NAME'].'"/>
                  <br/>';
    }
    
    /*
     * Middle Name
     */
    $middlenameErrorMessage = validateOptionalName($middlename);
    if(strlen($middlenameErrorMessage) > 0 && $_POST['updateprofile'] == "Update")
    {
        $middlenameError = TRUE;
        $form .= '<label>Middle Name</label>
                  <input  type="text" name="middlename" value="'.$middlename.'"/> '.$middlenameErrorMessage.'
                  <br/>';
    }
    elseif(strlen($middlenameErrorMessage) == 0 && $_POST['updateprofile'] == "Update")
    {
        $middlenameError = FALSE;
        $form .= '<label>Middle Name</label>
                  <input  type="text" name="middlename" value="'.$middlename.'"/>
                  <br/>';
    }
    else
    {
        $form .= '<label>Middle Name</label>
                  <input  type="text" name="middlename" value="'.$_SESSION['MIDDLE_NAME'].'"/>
                  <br/>';
    }
    
    /*
     * Last Name
     */
    $lastnameErrorMessage = validateOptionalName($lastname);
    if(strlen($lastnameErrorMessage) > 0 && $_POST['updateprofile'] == "Update")
    {
        $lastnameError = TRUE;
        $form .= '<label>Last Name</label>
                  <input type="text" name="lastname" value="'.$lastname.'"/> '.$lastnameErrorMessage.'
                  <br/>';
    }
    elseif(strlen($lastnameErrorMessage) == 0 && $_POST['updateprofile'] == "Update")
    {
        $lastnameError = FALSE;
        $form .= '<label>Last Name</label>
                  <input type="text" name="lastname" value="'.$lastname.'"/>
                  <br/>';
    }
    else
    {
        $form .= '<label>Last Name</label>
                  <input type="text" name="lastname" value="'.$_SESSION['LAST_NAME'].'"/>
                  <br/>';
    }
     
    /*
     * Nick Name
     */
    $nicknameErrorMessage = validateOptionalName($nickname);       
    if(strlen($nicknameErrorMessage) > 0 && $_POST['updateprofile'] == "Update")
    {
        $nicknameError = TRUE;
    
        $form .= '<label>Nickname</label>
                  <input type="text" name="nickname" value="'.$nickname.'"/> '.$nicknameErrorMessage.'
                  <br/>1';
    }
    elseif(strlen($nicknameErrorMessage) == 0 && $_POST['updateprofile'] == "Update")
    {
        $nicknameError = FALSE;
        
        $form .= '<label>Nickname</label>
                  <input type="text" name="nickname" value="'.$nickname.'"/><br/>';
    }
    else
    {
        $form .= '<label>Nickname</label>
                  <input type="text" name="nickname" value="'.$_SESSION['NICK_NAME'].'"/>
                  <br/>';
    }
                

       $form .= '</p>
                 <input class="cancelbutton" type="submit" name="cancel" value="Cancel"/>
                 <input class="statebutton" type="submit" name="updateprofile" value="Update"/>
                 </form> ';
       
    /*
     * Evaluate errors and decide to either print form or update profile
     */
    if($firstnameError || $middlenameError || $lastnameError || $nicknameError)
    {
        echo $form;
    }
    elseif($_POST['updateprofile'] == "Update")
    {      
        UpdateUserProfile($_SESSION['USER_ID'], $firstname, $middlename, $lastname, $nickname);
        RefreshSession($_SESSION['USER_ID']);
        echo "Profile has been updated!";
        echo $form;
    }
    else
    {
        echo $form;
    }
    
}

function printManageLoginForm()
{
    
    
}

?>
