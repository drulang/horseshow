<?php

include 'db_func.php';

function validateUserName($username)
{
    $errorMessage = "";
    
    if(strlen($username) > 45)
    {
        $errorMessage = "Username must be less than 45 characters";
        return $errorMessage;
    }
    elseif(strlen($username) == 0)
    {
        $errorMessage = "Username cannot be empty";
        return $errorMessage;
    }
    elseif(strlen($username) < 3)
    {
        $errorMessage = "Username must be greater than 3 characters";
        return $errorMessage;
    }
    elseif(UserExists($username))
    {
        $errorMessage = "Username is taken";
        return $errorMessage;
    }
    else 
    {
        return $errorMessage;   
    }
    
}

function validatePassword($password)
{
    $errorMessage = "";
    
    if(strlen($password) == 0)
    {
        $errorMessage = "Password cannot be empty";
        return $errorMessage;
    }
    elseif(strlen($password) < 6) 
    {
        $errorMessage = "Password must be at least 6 characters";
        return $errorMessage;
    }
    elseif(strlen($password) > 60)
    {
        $errorMessage = "Password must be less than 60 characters";
        return $errorMessage;
    }
    else
    {
        return $errorMessage;
    }
}

/*
 * Need to check for numbers and letters
 */
function validateConfirmPassword($password, $confirmPassword)
{
    $errorMessage = validatePassword($confirmPassword);
    
    if(strlen($errorMessage) > 0)
    {
        return $errorMessage;
    }
    elseif($confirmPassword != $password) 
    {
        $errorMessage = "Passwords do not match";
        return $errorMessage;
    }
    else
    {
        return $errorMessage;    
    }
    

}

function validateEmail($email)
{
    $errorMessage = "";
    
    if(strlen($email) == 0)
    {
        $errorMessage = "Email cannot be empty";
        return $errorMessage;
    }
    elseif(strlen($email) > 45)
    {
        $errorMessage = "Email cannot be greater than 45 characters";
        return $errorMessage;
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $errorMessage = "Email is not in a valid format";
        return $errorMessage;
    }
    else
    {
        return $errorMessage;
    }
}

function  validateRequiredName($name)
{
    $errorMessage = validateOptionalName($name);
    
    if(strlen($errorMessage) > 0)
    {
        return $errorMessage;
    }
    elseif(strlen($name) == 0)
    {
        $errorMessage = "Name cannot be empty";
        return $errorMessage;
    }
    else
    {
        return $errorMessage;
    }  
      
}

function validateOptionalName($name)
{
    $errorMessage = "";
    
    if(strlen($name) > 60)
    {
        $errorMessage = "Name cannot be greater than 60 characters";
        return $errorMessage;
    }
    else
    {
        return $errorMessage;
    }
}

function validateRequiredField($input, $minLength, $maxLength)
{
    $errorMessage = "";
    
    if(strlen($input) == 0)
    {
        $errorMessage = "Field cannot be empty";
        return $errorMessage;
    }
    elseif(strlen($input) < $minLength)
    {
        $errorMessage = "Field cannot be less than ".$minLength;
        return $errorMessage;
    }
    elseif(strlen($input) > $maxLength)
    {
        $errorMessage = "Field cannot be greater than ".$maxLength;
        return $errorMessage;
    }
    else
    {
        return $errorMessage;
    }
    
}

function validateModelName($input)
{
    $errorMessage = "";
    
    if(strlen($input) == 0)
    {
        $errorMessage = "Model Name cannot be empty. ";
        return $errorMessage;
    }
    elseif(strlen($input) > 45)
    {
        $errorMessage = "Model Name cannot be greater than 45 characters. ";
        return $errorMessage;
    }
    elseif(strlen($input) < 1)
    {
        $errorMessage = "ModelName must be greater than 1 character";
        return $errorMessage;
    }
    else
    {
        return $errorMessage;
    }
}

function validateModelId($input)
{
    $errorMessage = "";
    
    if(!(is_numeric($input)))
    {
        $errorMessage = "Model Id must be only numbers. ";
        return $errorMessage;
    }
    elseif(strlen($input) != 5)
    {
        $errorMessage = "Model Id must be equal to 5 numbers. ";
        return $errorMessage;
    }
    else
    {
        return $errorMessage;
    }
}

function requireUserLoggedIn()
{
  session_start();
  
  if($_SESSION['LOGIN_NAME'] == '')
  {
      header("Location: login.php");
      exit;
  }
  
  //If inactivate date has been set.  Need to send the user to the inactivate page
  if($_SESSION['INACTIVIE_DATE'] != '' || $_SESSION['ACTIVATION_KEY'] != '')
  {
      session_destroy();
      Header("Location: inactiveuser.php");
      exit;
  }   
}

function requireWebsiteAdminPrivilege()
{
   if($_SESSION['USER_ROLE_ID' == ''] || $_SESSION['LOGIN_NAME'] == '')
    {
        header("Location: login.php");
        exit;
    }
    elseif($_SESSION['USER_ROLE_ID'] != '100')
    {
        header("Location: profile.php");
        exit;
    }
}

function requireShowManager()
{
    if($_SESSION['IS_SHOW_MANAGER'] == FALSE)
    {
        header("Location: profile.php");
        exit;
    } 
}

function sanitizeIntegerOnly($input)
{
  $input = ereg_replace("[^0-9]", "", $input);
  $input = filter_var($input, FILTER_SANITIZE_STRING);
  
  return $input;
}

function sanitizeCharacterOnly($input)
{
    $input = ereg_replace("[^A-Za-z]", "", $input);
    $input = filter_var($input, FILTER_SANITIZE_STRING);
    
    return $input;
}

function sanitizeAlphaNumericOnly($input)
{
    $input = ereg_replace("[^A-Za-z0-9]", "", $input);
    $input = filter_var($input, FILTER_SANITIZE_STRING);
    
    return $input;
}

function sanitizeAlphaNumericSpaceOnly($input)
{
    $input = ereg_replace("[^A-Za-z0-9 ]", "", $input);
    $input = filter_var($input, FILTER_SANITIZE_STRING);
    
    return $input;
}

?>
