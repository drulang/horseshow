<?php 
  include_once 'include/layout.php';  
  require_once 'include/validation.php';
  require_once 'include/recaptchalib.php';
  
  session_start();
  
    if(isset($_SESSION['LOGIN_NAME']))
    {
        header("Location: profile.php");
    }
    
    if($_POST['cancel'])
    {
        session_destroy();
        header("Location: login.php");
    }
    
    $emailValidationSent = FALSE;
    $form = validateCreateUserForm($emailValidationSent);
    
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
            if($emailValidationSent)
            {
                echo 
                '<h4>Verification Email has been sent!</h4>
                 <p>We look forward to you being a part of <strong>ModelHorseShow.com</strong>!  To ensure you\'re a real person we\'ve sent a verification email to you.  Just click the contained link and you\'ll be good to go.  </p>
                 <p>If you have any problems just shoot us an email to admin@modelhorseshow.com !!</p>
                 <br/>
                 <br/>
                ';
            }
            else
            {
                echo $form;
            }
            
         ?>
        <div class="cleaner"></div>
    </div> <!-- end of main column -->
    
  <div class="side_column_w200">
            	
        <div class="box">

           <?php
               printSideBar();
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
function validateCreateUserForm(&$validationEmailSent)
{
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $username = preg_replace('#\W#', '', $username);
    
    #need to figure out how to sanitize passwords
    $confirmPassword = $_POST['confirmpassword'];
    $password = $_POST['password'];
    
    $primaryemail = filter_var($_POST["primaryemail"], FILTER_SANITIZE_STRING);
    
    $firstname = filter_var($_POST["firstname"], FILTER_SANITIZE_STRING);
    $firstname = preg_replace('#\W#', '', $firstname);
    
    $lastname = filter_var($_POST["lastname"], FILTER_SANITIZE_STRING);
    $lastname = preg_replace('#\W#', '', $lastname);
    
    $nickname = filter_var($_POST["nickname"], FILTER_SANITIZE_STRING);
    $nickname = preg_replace('#\W#', '', $nickname);
    
    //Error Flags
    $firstnameError = TRUE;
    $lastnameError = TRUE;
    $usernameError = TRUE;
    $passwordError = TRUE;
    $primaryemailError = TRUE;
    $confirmPasswordError = TRUE;
    $nicknameError = FALSE;
    $lastnameError = FALSE;
    $captchaError = TRUE;
    
    
    $form =  '<p>
                    <h5>Setup an account in one click!</h5>
                </p>
                <form method="POST" action="create_user.php">
                    <p> Account Information';
    
    #
    #  Check Username
    #
        $usernameErrorMessage = validateUserName($username);

        if(strlen($usernameErrorMessage) > 0 && $_POST['createuser'])
        {
            $usernameError = TRUE;
            $form .=  '<label>Username*</label>
                            <input name="username" type="text" size="30" value="'.$username.'" /> '.$usernameErrorMessage;
        }
        elseif(strlen($usernameErrorMessage) == 0 && $_POST['createuser'])
        {
            $usernameError = FALSE;
            $errorCount--;
            $form .=  '<label>Username*</label>
                            <input name="username" type="text" size="30" value="'.$username.'"/>';
        }
        else
        {
            $form .=  '<label>Username*</label>
                            <input name="username" type="text" size="30"/>'; 
        }
    
    #
    # Check Password
    #

        $passwordErrorMessage = validatePassword($password);
        if(strlen($passwordErrorMessage) > 0 && $_POST['createuser'])
        {
            $passwordError = TRUE;
            $form .= '<label>Password*</label>
                            <input name="password" type="password" size="30" /> '.$passwordErrorMessage;
        }
        elseif(strlen($passwordErrorMessage) == 0 && $_POST['createuser'])
        {
            $passwordError = FALSE;
            $form .= '<label>Password*</label>
                            <input name="password" type="password" size="30" value="'.$password.'"/>';
        }
        else
        {
            $form .= '<label>Password*</label>
                            <input name="password" type="password" size="30" />';
        }
    
    #
    # Check confirmpassword
    #
        $confirmPasswordErrorMessage = validateConfirmPassword($password, $confirmPassword);
        if(strlen($confirmPasswordErrorMessage) > 0 && $_POST['createuser'])
        {
            $confirmPasswordError = TRUE;
            $form .= '<label>Confirm Password*</label>
                            <input name="confirmpassword" type="password" size="30" /> '.$confirmPasswordErrorMessage;
        }
        elseif(strlen($confirmPasswordErrorMessage) == 0 && $_POST['createuser'])
        {
            $confirmPasswordError = FALSE;
            $errorCount--;
            $form .= '<label>Confirm Password*</label>
                            <input name="confirmpassword" type="password" size="30" value="'.$confirmPassword.'" />';
        }
        else
        {
            $form .= '<label>Confirm Password*</label>
                            <input name="confirmpassword" type="password" size="30" />';
        }
    
    #
    # Confirm primaryemail
    #
        $primaryEmailErrorMessage = validateEmail($primaryemail);
        if(strlen($primaryEmailErrorMessage) > 0 && $_POST['createuser'])
        {
            $primaryemailError = TRUE;
            $form .= '<label>Primary Email*</label>
                            <input name="primaryemail" type="text" size="40" value="'.$primaryemail.'" /> '.$primaryEmailErrorMessage.'
                        </p>
                        <br/>';
        }
        elseif(strlen($primaryEmailErrorMessage) == 0 && $_POST['createuser'])
        {
            $primaryemailError = FALSE;
            $form .= '<label>Primary Email*</label>
                            <input name="primaryemail" type="text" size="40" value="'.$primaryemail.'"/>
                        </p>
                        <br/>';
        }
        else
        {
            $form .= '<label>Primary Email*</label>
                            <input name="primaryemail" type="text" size="40"/>
                        </p>
                        <br/>';
        }
    
    #####
    #
    #  Check Profile Information
    #
    #####
        $form .= '<p> Profile Information (You can change this later so don\'t panic!)';
    
    #
    # Check firstname
    #
        $firstNameErrorMessage = validateRequiredName($firstname);
        if(strlen($firstNameErrorMessage) > 0 && $_POST['createuser'])
        {
            $firstnameError = TRUE;
            $form .= '<label>First Name*</label>
                                <input name="firstname" type="text" size="30" value="'.$firstname.'" /> '.$firstNameErrorMessage;
        }
        elseif(strlen($firstNameErrorMessage) == 0 && $_POST['createuser'])
        {
            $firstnameError = FALSE;
            $errorCount--;
            $form .= '<label>First Name*</label>
                            <input name="firstname" type="text" size="30" value="'.$firstname.'" />';
        }
        else
        {
            $form .= '<label>First Name*</label>
                            <input name="firstname" type="text" size="30" />';
        }
    
    #
    # Lastname can be blank so just sanitize it
    #
        $lastNameErrorMessage = validateOptionalName($lastname);
        if(strlen($lastNameErrorMessage) > 0 && $_POST['createuser'])
        {
            $lastnameError = TRUE;
            $form .= '<label>Last Name</label>
                            <input name="lastname" type="text" size="30" value="'.$lastname.'" /> '.$lastNameErrorMessage;
        }
        elseif(strlen($lastNameErrorMessage) == 0 && $_POST['createuser'])
        {
            $lastnameError = FALSE;
            $form .= '<label>Last Name</label>
                            <input name="lastname" type="text" size="30" value="'.$lastname.'" />';
        }
        else
        {
            $form .= '<label>Last Name</label>
                            <input name="lastname" type="text" size="30" />';
        }
        
    #
    # nickname can be blank so just sanitize it
    #
        $nickNameErrorMessage = validateOptionalName($nickname);
        
        if(strlen($nickNameErrorMessage) > 0 && $_POST['createuser'])
        {
            $nicknameError = TRUE;
            $form .= '<label>Nickname</label>
                        <input name="nickname" type="text" size="30" value="'.$nickname.'" /> '.$nickNameErrorMessage;  
        }
        elseif(strlen($nickNameErrorMessage) == 0 && $_POST['createuser'])
        {
            $nicknameError = FALSE;
            $form .= '<label>Nickname</label>
                        <input name="nickname" type="text" size="30" value="'.$nickname.'" /> '; 
        }
        else
        {
            $form .= '<label>Nickname</label>
                        <input name="nickname" type="text" size="30" /> '; 
        }
        
       
        if ($_POST["recaptcha_response_field"]) {
                $resp = recaptcha_check_answer ($GLOBALS['privatekey'],
                                                $_SERVER["REMOTE_ADDR"],
                                                $_POST["recaptcha_challenge_field"],
                                                $_POST["recaptcha_response_field"]);

                if ($resp->is_valid)
                {
                        $form .= recaptcha_get_html($GLOBALS['publickey'], $error);
                        $captchaError = FALSE;
                }
                else 
                {
                        # set the error code so that we can display it
                        $captchaErrorMessage = $resp->error;
                        $form .= recaptcha_get_html($GLOBALS['publickey'], $captchaErrorMessage);
                        $captchaError = TRUE;
                }
        }
        else
        {
            $form .= recaptcha_get_html($GLOBALS['publickey'], $error);
            $captchaError = TRUE;
        }
        
    
    #
    #  Attach remainder of form
    #
        $form .= '
                            <br/>
                            <br/>
                            <i>By creating an account you accept our <a href="terms_of_service.php">Terms of Service</a></i>
                        </p>
                        <input class="cancelbutton" type="submit" name="cancel" value="Cancel"/>
                        <input class="statebutton" type="submit" name="createuser" value="Create"/>
                    </form>
                    <br />';
    
    //if any errors flagged then echo the form
    if($usernameError || $passwordError || $confirmPasswordError || $firstnameError || $lastnameError || $nicknameError || $primaryemailError || $captchaError)
    {
       //echo $form;
        $validationEmailSent = FALSE;
        
    }
    else
    {
        #Add user to database
        CreateMinimalUser($username, $password, $primaryEmail, $firstname);
        
        $validationEmailSent = TRUE;

    }
    
    return $form;
}
?>