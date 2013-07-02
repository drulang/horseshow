<?php


/*function printCreateUserForm()
{
    
    echo
            '
                <p>
                    <b>Setup an account in one click!</b>
                </p>
                <form method="POST" action="create_user.php">
                    <p> Account Information
                        <label>Username*</label>
                        <input name="username" type="text" size="30" />
                        
                        <label>Password*</label>
                        <input name="password" type="password" size="30" /> 
                        
                        <label>Confirm Password*</label>
                        <input name="confirmpassword" type="password" size="30" />

                        <label>Primary Email*</label>
                        <input name="primaryemail" type="text" size="55" />

                    </p>
                    <br/>
                    <p> Profile Information (You can change this later so don\'t panic!)
                        <label>First Name*</label>
                        <input name="firstname" type="text" size="30" />
                        
                        <label>Last Name</label>
                        <input name="lastname" type="text" size="30" />
                        
                        <label>Nickname</label>
                        <input name="nickname" type="text" size="30" />
                        
                        <br/>
                        <br/>
                        <i>By creating an account you accept our <a href="terms_of_service.php">Terms of Service</a></i>
                    </p>
                    <input class="cancelbutton" type="submit" name="cancel" value="Cancel"/>
                    <input class="statebutton" type="submit" name="createuser" value="Create"/>
                </form>
                <br />
            ';
}
 
 */

function validateCreateUserForm()
{
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $username = preg_replace('#\W#', '', $username);
    
    #need to figure out how to sanitize passwords
    $confirmpassword = $_POST['confirmpassword'];
    $password = $_POST['password'];
    
    $primaryemail = filter_var($_POST["primaryemail"], FILTER_SANITIZE_STRING);
    
    $firstname = filter_var($_POST["firstname"], FILTER_SANITIZE_STRING);
    $firstname = preg_replace('#\W#', '', $firstname);
    
    $lastname = filter_var($_POST["lastname"], FILTER_SANITIZE_STRING);
    $lastname = preg_replace('#\W#', '', $lastname);
    
    $nickname = filter_var($_POST["nickname"], FILTER_SANITIZE_STRING);
    $nickname = preg_replace('#\W#', '', $nickname);
    
    $errorCount = 5;
    
    $form =  '<p>
                    <b>Setup an account in one click!</b>
                </p>
                <form method="POST" action="create_user.php">
                    <p> Account Information';
    
    #
    #  Check Username
    #
    if($_POST['username'] == "" && $_POST['createuser'])
    {
      
        $form .=  '<label>Username*</label>
                        <input name="username" type="text" size="30" value="'.$username.'" /> Please enter a username';
    }
    elseif(strlen($username) > 45 && $_POST['createuser'])
    {
        $form .=  '<label>Username*</label>
                        <input name="username" type="text" size="30" value="'.$username.'" /> Username must be less than 45 characters';
    }
    elseif(UserExists($username) && $_POST['createuser'])
    {
        $form .=  '<label>Username*</label>
                        <input name="username" type="text" size="30" value="'.$username.'" /> Username is taken';
    }
    elseif($_POST['createuser'])
    {
       $errorCount--;
       $form .=  '<label>Username*</label>
                        <input name="username" type="text" size="30" value="'.$username.'"/>'; 
    }
    else
    {
        $form .=  '<label>Username*</label>
                        <input name="username" type="text" size="30" value="'.$username.'"/>'; 
    }
    
    #
    # Check Password
    #
    if($_POST['password'] == "" && $_POST['createuser'])
    {
        
        $form .= '<label>Password*</label>
                        <input name="password" type="password" size="30" /> Please enter a password';
    }
    elseif($_POST['createuser'])
    {
        $errorCount--;
        $form .= '<label>Password*</label>
                        <input name="password" type="password" size="30" />';
    }
    else
    {
        $form .= '<label>Password*</label>
                        <input name="password" type="password" size="30" />';
    }
    
    #
    # Check confirmpassword
    #
    if($_POST['confirmpassword'] == "" && $_POST['createuser'])
    {
       
        $form .= '<label>Confirm Password*</label>
                        <input name="confirmpassword" type="password" size="30"/> Please confirm your password';
    }
    elseif($_POST['createuser'])
    {
        $errorCount--;
        $form .= '<label>Confirm Password*</label>
                        <input name="confirmpassword" type="password" size="30" />';
    }
    else
    {
        $form .= '<label>Confirm Password*</label>
                        <input name="confirmpassword" type="password" size="30" />';
    }
    
    #
    # Confirm primaryemail
    #
    if($_POST['primaryemail'] == "" && $_POST['createuser'])
    {
        
        $form .= '<label>Primary Email*</label>
                        <input name="primaryemail" type="text" size="55" value="'.$primaryemail.'" /> Please enter a valid email address
                    </p>
                    <br/>';
    }
    elseif($_POST['createuser'])
    {
        $errorCount--;
        $form .= '<label>Primary Email*</label>
                        <input name="primaryemail" type="text" size="55" value="'.$primaryemail.'"/>
                    </p>
                    <br/>';
    }
    else
    {
        $form .= '<label>Primary Email*</label>
                        <input name="primaryemail" type="text" size="55" value="'.$primaryemail.'"/>
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
    if($_POST['firstname'] == "" && $_POST['createuser'])
    {
       
       $form .= '<label>First Name*</label>
                        <input name="firstname" type="text" size="30" value="'.$firstname.'" /> Please enter a firstname';
    }
    elseif($_POST['createuser'])
    {
        $errorCount--;
        $form .= '<label>First Name*</label>
                        <input name="firstname" type="text" size="30" value="'.$firstname.'" />';
    }
    else
    {
        $form .= '<label>First Name*</label>
                        <input name="firstname" type="text" size="30" value="'.$firstname.'" />';
    }
    
    #
    # Lastname can be blank so just sanitize it
    #
        $form .= '<label>Last Name</label>
                        <input name="lastname" type="text" size="30" value="'.$lastname.'" />';
        
    #
    # nickname can be blank so just sanitize it
    #
        $form .= '<label>Nickname</label>
                        <input name="nickname" type="text" size="30" value="'.$lastname.'" />
                        ';    
    
        $form .= '
                            <br/>
                            <br/>
                            <i>By creating an account you accept our <a href="terms_of_service.php">Terms of Service</a></i>
                        </p>
                        <input class="cancelbutton" type="submit" name="cancel" value="Cancel"/>
                        <input class="statebutton" type="submit" name="createuser" value="Create"/>
                    </form>
                    <br />';
    if($errorCount != 0)
    {
       
       echo $form;
    }
    else
    {
   
         #reprint form displaying error messages
        echo "blah blah";
        
    }
    
}
?>
