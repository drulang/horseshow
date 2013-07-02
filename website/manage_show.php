<?php 
  include_once 'include/layout.php';  
  require_once 'include/validation.php';	
  session_start();
  
  requireUserLoggedIn();
  
  #require that User is registerd for
  requireShowManager();
  
  if(isset($_GET['link']))
  {
     $link = sanitizeCharacterOnly($_GET['link']);
  }
  elseif(isset($_POST['link']))
  {
      $link = sanitizeCharacterOnly($_POST['link']);
  }
  
  if(isset($_GET['showid']))
  {
      $showid = sanitizeIntegerOnly($_GET['showid']);
      
      $showArray = ShowStats($showid);
  }
  elseif(isset($_POST['showid']))
  {
      $showid = sanitizeIntegerOnly($_POST['showid']);
      
      $showArray = ShowStats($showid);
  }
  
  $arrayHorseShowId = SelectUserHorseShows($_SESSION['USER_ID']);
  
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
         <div id="templatemo_submenu">       
            <?php
                //Submenu
              printShowSubmenu($showid);
            ?>

        </div>
          <?php
            //MAIN CONTENT
            if($link == "uploadform" && isset($showid))
            {
                printUploadForm($showid);
            }
            elseif($link == "people" && isset($showid))
            {
                printRegisteredPeopleForm($showid);
            }
            elseif($link == "bfchangeform" && isset($showid))
            {
                printBfChangeForm($showid);
            }
            else
            {
                printDashboard($showArray);
            }
          
	  ?>
        <div class="cleaner"></div>
    </div> <!-- end of main column -->
    
  <div class="side_column_w200">
            	
        <div class="box">

         <?php 
             //SIDEBAR
             printShowManagerSideBar($arrayHorseShowId);
             
             $arrayHorseShowId = SelectUserHorseShows($_SESSION['USER_ID']);
             
             while ($row = mysqli_fetch_array($arrayHorseShowId, MYSQL_ASSOC))
             {
                printShowManagerActiveShowsSideBar(SelectActiveShows($_SESSION['USER_ID'], $row['HORSESHOW_ID']));
             }
             
         ?>
            
        </div> 
                
                
        <?php printSearch(); ?>
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

function printUploadForm($showid)
{
    $formUploadDirectory = "uploaded_forms/";
    $uniqueFileID = strtoupper(uniqid().uniqid());
    
    $fileName = $formUploadDirectory.$uniqueFileID.".mef";
    
    if($_POST['uploaddocument'] == "Upload")
    {
        if(move_uploaded_file($_FILES['document']['tmp_name'], "$fileName"))
        { 

        //Tells you if its all ok 
        echo "The file has been uploaded, and your information has been added to the directory"; 
        } 
        else { 
            echo $_FILES['document'];
        //Gives and error if its not 
        echo "Sorry, there was a problem uploading your file."; 
        } 
    }
    else
    {
        $form =  '<h5>Upload a Person Registration Form</h5>';

        $form .= ' <form enctype="multipart/form-data" action="manage_show.php?link=uploadform&showid='.$showid.'" method="POST">
                    <p>
                        <label>Please choose a file:</label>

                        <input class="file" name="document" type="file" /><br />
                    </p>
                    <input class="statebutton" type="submit" name="uploaddocument" value="Upload"/>
                    </form> ';

        echo $form;
    }
    
}


 function printRegisteredPeopleForm($showid)
 {
     $registeredPeople = SelectRegisteredPeople($showid);
     
     if(!$registeredPeople)
     {
         echo("Error Selecting People for Show".$showid);
     }
     else
     {
            $table = '<table id="table">
            <tr>
                <th>Exhibitor ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Comment</th>
                <th>Status</th>
            </tr>';

            $count = 1;    
            while ($row = mysqli_fetch_array($registeredPeople, MYSQL_ASSOC))
            {
                if($count % 2)
                {
                    $table .= '<tr>
                                <td><a href="show_person.php?personid='.$row['ID'].'&showid='.$showid.'">'.$row["EX_ID"].' </a></td>
                                <td>'.$row["FIRST_NAME"].' </td>
                                <td>'.$row["LAST_NAME"].'</td>
                                <td>'.$row["COMMENT"].'</td>
                                <td>'.$row["STATUS"].'</td>
                                </tr>
                                ';
                }
                else
                {
                    $table .= '<tr class="alt">
                                <td><a href="show_person.php?personid='.$row['ID'].'&showid='.$showid.'">'.$row["EX_ID"].' </a></td>
                                <td>'.$row["FIRST_NAME"].' </td>
                                <td>'.$row["LAST_NAME"].'</td>
                                <td>'.$row["COMMENT"].'</td>
                                <td>'.$row["STATUS"].'</td>
                                </tr>
                                ';
                }

                $count++;
            }

            $table .= "</table>";

            echo $table;
     }
 }
 
 function printDashboard($siteArray)
 {
     $id = $siteArray["ID"];
     $showTitle = $siteArray["Title"];
     $maxRegister = $siteArray["MaxRegister"];
     $addDate = $siteArray["AddDate"];
     $closeDate = $siteArray["CloseDate"];
     $openDate = $siteArray["OpenDate"];
     $comment = $siteArray["Comment"];
     $statusName = FormatStringSplitPascalCase($siteArray["StatusName"], ' ');
     $horseShowName = FormatStringSplitPascalCase($siteArray["HorseshowName"], " ");
     //Counts
     $peopleCount = $siteArray["PeopleCount"];
     $modelCount = $siteArray["ModelCount"];
     //Names
     $firstLongestName = $siteArray["FirstLongestName"];
     $secondLongestName = $siteArray["SecondLongestName"];
     $thirdLongestName = $siteArray["ThirdLongestName"];
     $firstShortestName = $siteArray["FirstShortestName"];
     $secondShortestName = $siteArray["SecondShortestName"];
     $thirdShortestName = $siteArray["ThirdShortestName"];
     //States 
     $firstState = $siteArray["FirstState"];
     $secondState = $siteArray["SecondState"];
     $thirdState = $siteArray["ThirdState"];
     
     $form = "<p><h5>$horseShowName -- $showTitle</h5></p>";
     
     //
     // General Information
     //
     $form .= "<form><p><b>General Information</b>";
     
     $form .= "<ul>";
     
     $form .= "<li>Show ID: $id</li>";
     $form .= "<li>Show Title: $showTitle</li>";
     $form .= "<li>Max Registered: $maxRegister</li>";
     $form .= "<li>Show Staus: $statusName</li>";
     
     $form .= "</ul></p>";
     
     //
     // Date Information
     //
     $form .= "<p><b>Dates</b>";
     
     $form .= "<ul>";
     
     $form .= "<li>Creation Date: $addDate</li>";
     $form .= "<li>Open Date: $openDate</li>";
     $form .= "<li>Close Date: $closeDate</li>";
     
     $form .= "</ul></p>";
     
     //
     // Numbers
     //
     $form .= "<p><b>Numbers & Statsa</b>";
     
     $form .= "<ul>";
     
     $form .= "<li>Total People Registered: $peopleCount</li>";
     $form .= "<li>Total Horses Registered: $modelCount</li>";
     
     $form .= "<br/>";
     
     $form .= "<li>Longest Model Name</li><ol>";
        $form .= '<li><a href = "show_horse.php?horseid='.$firstLongestName.'">'.ModelNameLookup($firstLongestName).'</a></li>';
        $form .= '<li><a href = "show_horse.php?horseid='.$secondLongestName.';">'.ModelNameLookup($secondLongestName).'</a></li>';
        $form .= '<li><a href = "show_horse.php?horseid='.$thirdLongestName.'">'.ModelNameLookup($thirdLongestName).'</a></li>';
     $form .= "</ol>";
     
     $form .= "<br/>";
     
     
     $form .= "<li>Shortest Model Name</li><ol>";
        $form .= '<li><a href ="show_horse.php?horseid='.$firstShortestName.'"> '.ModelNameLookup($firstShortestName).'</a></li>';
        $form .= '<li><a href = "show_horse.php?horseid='.$secondShortestName.'">'.ModelNameLookup($secondShortestName).'</a></li>';
        $form .= '<li><a href = "show_horse.php?horseid='.$thirdShortestName.'">'.ModelNameLookup($thirdShortestName).'</a></li>';
     $form .= "</ol>";
     
     $form .= "<br/>";
     
     $form .= "<li>Most Represented States</li><ol>";
        $form .= "<li>$firstState</li>";
        $form .= "<li>$secondState</li>";
        $form .= "<li>$thirdState</li>";
     $form .= "</ol>";
        
     $form .= "</ul></p>";
     
     $form .= "</form>";
     
     echo $form;
 }
 
 function printBfChangeForm($showid)
 {
     //
     // Header
     //
     $form .= "<p><h5>Change Request Form</h5></p>";
                
     $form .= '<form method="POST" action="manage_show.php">';

     $form .= "<label>&nbsp&nbsp&nbspID&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspModel Name&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspBreed(s)&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspGender&nbsp&nbsp&nbsp&nbsp&nbspDivision</label>";

     /////////////////////////////
     //  One
     ////////////////////////////
     $modelid1 = sanitizeIntegerOnly($_POST['id1']);
     $personid1 = substr($modelid1, 0, 3);
     $name1 = sanitizeAlphaNumericSpaceOnly($_POST['name1']);
     $breed1 = sanitizeAlphaNumericSpaceOnly($_POST['breed1']);
     $gender1 = sanitizeAlphaNumericSpaceOnly($_POST['gender1']);
     $division1 = sanitizeAlphaNumericSpaceOnly($_POST['division1']);
     
     $action1 = sanitizeCharacterOnly($_POST['action1']);

     $err1Flag = true;
     
     $err1 = "";
     $err1 .= validateModelId($modelid1);
     $err1 .= validateModelName($name1);
     
     if($action1 == "new")
     {
         if(!BfPersonExists($personid1))
         {
             $err1 = "Exhibitor Id does not exist.";
         }
         elseif(BfModelExists($modelid1))
         {
             $err1 = "Model Id Exists already.";
         }
         
     }
     elseif($action1 == "update")
     {
         if(!BfPersonExists($personid1))
         {
             $err1 = "Exhibitor Id does not exist.";
         }
         elseif(!BfModelExists($modelid1))
         {
             $err1 = "Model Id does not exist.";
         }
     }
     
     
     // One Form
     if(strlen($err1) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('1',$modelid1, $name1, $breed1, $gender1, $division1, $action1, $err1);
         $err1Flag = true;
     }
     elseif(strlen($err1) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('1', $modelid1, $name1, $breed1, $gender1, $division1, $action1);
         $err1Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('1');
         $err1Flag = true;
     }    
     
     /////////////////////////////
     //  Two
     ////////////////////////////
     $modelid2 = sanitizeIntegerOnly($_POST['id2']);
     $personid2 = substr($modelid2, 0, 3);
     $name2 = sanitizeAlphaNumericSpaceOnly($_POST['name2']);
     $breed2 = sanitizeAlphaNumericSpaceOnly($_POST['breed2']);
     $gender2 = sanitizeAlphaNumericSpaceOnly($_POST['gender2']);
     $division2 = sanitizeAlphaNumericSpaceOnly($_POST['division2']);
     
     $action2 = sanitizeCharacterOnly($_POST['action2']);

     $err2Flag = true;
     
     $err2 = "";
     $err2 .= validateModelId($modelid2);
     $err2 .= validateModelName($name2);
     
     if($action2 == "new")
     {
         if(!BfPersonExists($personid2))
         {
             $err2 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid2))
         {
             $err2 = "Model Id Exists already.";
         }
         
     }
     elseif($action2 == "update")
     {
         if(!BfPersonExists($personid2))
         {
             $err2 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid2))
         {
             $err2 = "Model Id does not exist.";
         }
     }
     
     
     // Two Form
     if($modelid2 == "" && $name2 == "")
     {
         $form .= buildUpdateLine('2');
         $err2Flag = false;
     }
     elseif(strlen($err2) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('2',$modelid2, $name2, $breed2, $gender2, $division2, $action2, $err2);
         $err2Flag = true;
     }
     elseif(strlen($err2) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('2', $modelid2, $name2, $breed2, $gender2, $division2, $action2);
         $err2Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('2');
         $err2Flag = true;
     }   
     
     /////////////////////////////
     //  Three
     ////////////////////////////
     $modelid3 = sanitizeIntegerOnly($_POST['id3']);
     $personid3 = substr($modelid3, 0, 3);
     $name3 = sanitizeAlphaNumericSpaceOnly($_POST['name3']);
     $breed3 = sanitizeAlphaNumericSpaceOnly($_POST['breed3']);
     $gender3 = sanitizeAlphaNumericSpaceOnly($_POST['gender3']);
     $division3 = sanitizeAlphaNumericSpaceOnly($_POST['division3']);
     
     $action3 = sanitizeCharacterOnly($_POST['action3']);

     $err3Flag = true;
     
     $err3 = "";
     $err3 .= validateModelId($modelid3);
     $err3 .= validateModelName($name3);
     
     if($action3 == "new")
     {
         if(!BfPersonExists($personid3))
         {
             $err3 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid3))
         {
             $err3 = "Model Id Exists already.";
         }
         
     }
     elseif($action3 == "update")
     {
         if(!BfPersonExists($personid3))
         {
             $err3 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid3))
         {
             $err3 = "Model Id does not exist.";
         }
     }
     
     
     // Three Form
     if($modelid3 == "" && $name3 == "")
     {
         $form .= buildUpdateLine('3');
         $err3Flag = false;
     }
     elseif(strlen($err3) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('3',$modelid3, $name3, $breed3, $gender3, $division3, $action3, $err3);
         $err3Flag = true;
     }
     elseif(strlen($err3) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('3', $modelid3, $name3, $breed3, $gender3, $division3, $action3);
         $err3Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('3');
         $err3Flag = true;
     }    
     
     
     /////////////////////////////
     //  Four
     ////////////////////////////
     $modelid4 = sanitizeIntegerOnly($_POST['id4']);
     $personid4 = substr($modelid4, 0, 3);
     $name4 = sanitizeAlphaNumericSpaceOnly($_POST['name4']);
     $breed4 = sanitizeAlphaNumericSpaceOnly($_POST['breed4']);
     $gender4 = sanitizeAlphaNumericSpaceOnly($_POST['gender4']);
     $division4 = sanitizeAlphaNumericSpaceOnly($_POST['division4']);
     
     $action4 = sanitizeCharacterOnly($_POST['action4']);

     $err4Flag = true;
     
     $err4 = "";
     $err4 .= validateModelId($modelid4);
     $err4 .= validateModelName($name4);
     
     if($action4 == "new")
     {
         if(!BfPersonExists($personid4))
         {
             $err4 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid4))
         {
             $err4 = "Model Id Exists already.";
         }
         
     }
     elseif($action4 == "update")
     {
         if(!BfPersonExists($personid4))
         {
             $err4 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid4))
         {
             $err4 = "Model Id does not exist.";
         }
     }
     
     
     // Four Form
     if($modelid4 == "" && $name4 == "")
     {
         $form .= buildUpdateLine('4');
         $err4Flag = false;
     }
     elseif(strlen($err4) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('4',$modelid4, $name4, $breed4, $gender4, $division4, $action4, $err4);
         $err4Flag = true;
     }
     elseif(strlen($err4) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('4', $modelid4, $name4, $breed4, $gender4, $division4, $action4);
         $err4Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('4');
         $err4Flag = true;
     }  
     
     /////////////////////////////
     //  Five
     ////////////////////////////
     $modelid5 = sanitizeIntegerOnly($_POST['id5']);
     $personid5 = substr($modelid5, 0, 3);
     $name5 = sanitizeAlphaNumericSpaceOnly($_POST['name5']);
     $breed5 = sanitizeAlphaNumericSpaceOnly($_POST['breed5']);
     $gender5 = sanitizeAlphaNumericSpaceOnly($_POST['gender5']);
     $division5 = sanitizeAlphaNumericSpaceOnly($_POST['division5']);
     
     $action5 = sanitizeCharacterOnly($_POST['action5']);

     $err5Flag = true;
     
     $err5 = "";
     $err5 .= validateModelId($modelid5);
     $err5 .= validateModelName($name5);
     
     if($action5 == "new")
     {
         if(!BfPersonExists($personid5))
         {
             $err5 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid5))
         {
             $err5 = "Model Id Exists already.";
         }
         
     }
     elseif($action5 == "update")
     {
         if(!BfPersonExists($personid5))
         {
             $err5 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid5))
         {
             $err5 = "Model Id does not exist.";
         }
     }
     
     
     // Five Form
     if($modelid5 == "" && $name5 == "")
     {
         $form .= buildUpdateLine('5');
         $err5Flag = false;
     }
     elseif(strlen($err5) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('5',$modelid5, $name5, $breed5, $gender5, $division5, $action5, $err5);
         $err5Flag = true;
     }
     elseif(strlen($err5) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('5', $modelid5, $name5, $breed5, $gender5, $division5, $action5);
         $err5Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('5');
         $err5Flag = true;
     }    

     /////////////////////////////
     //  Six
     ////////////////////////////
     $modelid6 = sanitizeIntegerOnly($_POST['id6']);
     $personid6 = substr($modelid6, 0, 3);
     $name6 = sanitizeAlphaNumericSpaceOnly($_POST['name6']);
     $breed6 = sanitizeAlphaNumericSpaceOnly($_POST['breed6']);
     $gender6 = sanitizeAlphaNumericSpaceOnly($_POST['gender6']);
     $division6 = sanitizeAlphaNumericSpaceOnly($_POST['division6']);
     
     $action6 = sanitizeCharacterOnly($_POST['action6']);

     $err6Flag = true;
     
     $err6 = "";
     $err6 .= validateModelId($modelid6);
     $err6 .= validateModelName($name6);
     
     if($action6 == "new")
     {
         if(!BfPersonExists($personid6))
         {
             $err6 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid6))
         {
             $err6 = "Model Id Exists already.";
         }
         
     }
     elseif($action6 == "update")
     {
         if(!BfPersonExists($personid6))
         {
             $err6 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid6))
         {
             $err6 = "Model Id does not exist.";
         }
     }
     
     
     // Six Form
     if($modelid6 == "" && $name6 == "")
     {
         $form .= buildUpdateLine('6');
         $err6Flag = false;
     }
     elseif(strlen($err6) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('6',$modelid6, $name6, $breed6, $gender6, $division6, $action6, $err6);
         $err6Flag = true;
     }
     elseif(strlen($err6) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('6', $modelid6, $name6, $breed6, $gender6, $division6, $action6);
         $err6Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('6');
         $err6Flag = true;
     }  
     
     /////////////////////////////
     //  Seven
     ////////////////////////////
     $modelid7 = sanitizeIntegerOnly($_POST['id7']);
     $personid7 = substr($modelid7, 0, 3);
     $name7 = sanitizeAlphaNumericSpaceOnly($_POST['name7']);
     $breed7 = sanitizeAlphaNumericSpaceOnly($_POST['breed7']);
     $gender7 = sanitizeAlphaNumericSpaceOnly($_POST['gender7']);
     $division7 = sanitizeAlphaNumericSpaceOnly($_POST['division7']);
     
     $action7 = sanitizeCharacterOnly($_POST['action7']);

     $err7Flag = true;
     
     $err7 = "";
     $err7 .= validateModelId($modelid7);
     $err7 .= validateModelName($name7);
     
     if($action7 == "new")
     {
         if(!BfPersonExists($personid7))
         {
             $err7 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid7))
         {
             $err7 = "Model Id Exists already.";
         }
         
     }
     elseif($action7 == "update")
     {
         if(!BfPersonExists($personid7))
         {
             $err7 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid7))
         {
             $err7 = "Model Id does not exist.";
         }
     }
     
     
     // Seven Form
     if($modelid7 == "" && $name7 == "")
     {
         $form .= buildUpdateLine('7');
         $err7Flag = false;
     }
     elseif(strlen($err7) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('7',$modelid7, $name7, $breed7, $gender7, $division7, $action7, $err7);
         $err7Flag = true;
     }
     elseif(strlen($err7) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('7', $modelid7, $name7, $breed7, $gender7, $division7, $action7);
         $err7Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('7');
         $err7Flag = true;
     }    

     /////////////////////////////
     //  Eight
     ////////////////////////////
     $modelid8 = sanitizeIntegerOnly($_POST['id8']);
     $personid8 = substr($modelid8, 0, 3);
     $name8 = sanitizeAlphaNumericSpaceOnly($_POST['name8']);
     $breed8 = sanitizeAlphaNumericSpaceOnly($_POST['breed8']);
     $gender8 = sanitizeAlphaNumericSpaceOnly($_POST['gender8']);
     $division8 = sanitizeAlphaNumericSpaceOnly($_POST['division8']);
     
     $action8 = sanitizeCharacterOnly($_POST['action8']);

     $err8Flag = true;
     
     $err8 = "";
     $err8 .= validateModelId($modelid8);
     $err8 .= validateModelName($name8);
     
     if($action8 == "new")
     {
         if(!BfPersonExists($personid8))
         {
             $err8 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid8))
         {
             $err8 = "Model Id Exists already.";
         }
         
     }
     elseif($action8 == "update")
     {
         if(!BfPersonExists($personid8))
         {
             $err8 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid8))
         {
             $err8 = "Model Id does not exist.";
         }
     }
     
     
     // Eight Form
     if($modelid8 == "" && $name8 == "")
     {
         $form .= buildUpdateLine('8');
         $err8Flag = false;
     }
     elseif(strlen($err8) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('8',$modelid8, $name8, $breed8, $gender8, $division8, $action8, $err8);
         $err8Flag = true;
     }
     elseif(strlen($err8) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('8', $modelid8, $name8, $breed8, $gender8, $division8, $action8);
         $err8Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('8');
         $err8Flag = true;
     } 
     
     
     /////////////////////////////
     //  Nine
     ////////////////////////////
     $modelid9 = sanitizeIntegerOnly($_POST['id9']);
     $personid9 = substr($modelid9, 0, 3);
     $name9 = sanitizeAlphaNumericSpaceOnly($_POST['name9']);
     $breed9 = sanitizeAlphaNumericSpaceOnly($_POST['breed9']);
     $gender9 = sanitizeAlphaNumericSpaceOnly($_POST['gender9']);
     $division9 = sanitizeAlphaNumericSpaceOnly($_POST['division9']);
     
     $action9 = sanitizeCharacterOnly($_POST['action9']);

     $err9Flag = true;
     
     $err9 = "";
     $err9 .= validateModelId($modelid9);
     $err9 .= validateModelName($name9);
     
     if($action9 == "new")
     {
         if(!BfPersonExists($personid9))
         {
             $err9 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid9))
         {
             $err9 = "Model Id Exists already.";
         }
         
     }
     elseif($action9 == "update")
     {
         if(!BfPersonExists($personid9))
         {
             $err9 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid9))
         {
             $err9 = "Model Id does not exist.";
         }
     }
     
     
     // Nine Form
     if($modelid9 == "" && $name9 == "")
     {
         $form .= buildUpdateLine('9');
         $err9Flag = false;
     }
     elseif(strlen($err9) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('9',$modelid9, $name9, $breed9, $gender9, $division9, $action9, $err9);
         $err9Flag = true;
     }
     elseif(strlen($err9) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('9', $modelid9, $name9, $breed9, $gender9, $division9, $action9);
         $err9Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('9');
         $err9Flag = true;
     }  
     
          /////////////////////////////
     //  Ten
     ////////////////////////////
     $modelid10 = sanitizeIntegerOnly($_POST['id10']);
     $personid10 = substr($modelid10, 0, 3);
     $name10 = sanitizeAlphaNumericSpaceOnly($_POST['name10']);
     $breed10 = sanitizeAlphaNumericSpaceOnly($_POST['breed10']);
     $gender10 = sanitizeAlphaNumericSpaceOnly($_POST['gender10']);
     $division10 = sanitizeAlphaNumericSpaceOnly($_POST['division10']);
     
     $action10 = sanitizeCharacterOnly($_POST['action10']);

     $err10Flag = true;
     
     $err10 = "";
     $err10 .= validateModelId($modelid10);
     $err10 .= validateModelName($name10);
     
     if($action10 == "new")
     {
         if(!BfPersonExists($personid10))
         {
             $err10 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid10))
         {
             $err10 = "Model Id Exists already.";
         }
         
     }
     elseif($action10 == "update")
     {
         if(!BfPersonExists($personid10))
         {
             $err10 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid10))
         {
             $err10 = "Model Id does not exist.";
         }
     }
     
     
     // Ten Form
     if($modelid10 == "" && $name10 == "")
     {
         $form .= buildUpdateLine('10');
         $err10Flag = false;
     }
     elseif(strlen($err10) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('10',$modelid10, $name10, $breed10, $gender10, $division10, $action10, $err10);
         $err10Flag = true;
     }
     elseif(strlen($err10) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('10', $modelid10, $name10, $breed10, $gender10, $division10, $action10);
         $err10Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('10');
         $err10Flag = true;
     }   
     
     
     /////////////////////////////
     //  Eleven
     ////////////////////////////
     $modelid11 = sanitizeIntegerOnly($_POST['id11']);
     $personid11 = substr($modelid11, 0, 3);
     $name11 = sanitizeAlphaNumericSpaceOnly($_POST['name11']);
     $breed11 = sanitizeAlphaNumericSpaceOnly($_POST['breed11']);
     $gender11 = sanitizeAlphaNumericSpaceOnly($_POST['gender11']);
     $division11 = sanitizeAlphaNumericSpaceOnly($_POST['division11']);
     
     $action11 = sanitizeCharacterOnly($_POST['action11']);

     $err11Flag = true;
     
     $err11 = "";
     $err11 .= validateModelId($modelid11);
     $err11 .= validateModelName($name11);
     
     if($action11 == "new")
     {
         if(!BfPersonExists($personid11))
         {
             $err11 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid11))
         {
             $err11 = "Model Id Exists already.";
         }
         
     }
     elseif($action11 == "update")
     {
         if(!BfPersonExists($personid11))
         {
             $err11 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid11))
         {
             $err11 = "Model Id does not exist.";
         }
     }
     
     
     // Eleven Form
     if($modelid11 == "" && $name11 == "")
     {
         $form .= buildUpdateLine('11');
         $err11Flag = false;
     }
     elseif(strlen($err11) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('11',$modelid11, $name11, $breed11, $gender11, $division11, $action11, $err11);
         $err11Flag = true;
     }
     elseif(strlen($err11) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('11', $modelid11, $name11, $breed11, $gender11, $division11, $action11);
         $err11Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('11');
         $err11Flag = true;
     }  
     
          /////////////////////////////
     //  Twelve
     ////////////////////////////
     $modelid12 = sanitizeIntegerOnly($_POST['id12']);
     $personid12 = substr($modelid12, 0, 3);
     $name12 = sanitizeAlphaNumericSpaceOnly($_POST['name12']);
     $breed12 = sanitizeAlphaNumericSpaceOnly($_POST['breed12']);
     $gender12 = sanitizeAlphaNumericSpaceOnly($_POST['gender12']);
     $division12 = sanitizeAlphaNumericSpaceOnly($_POST['division12']);
     
     $action12 = sanitizeCharacterOnly($_POST['action12']);

     $err12Flag = true;
     
     $err12 = "";
     $err12 .= validateModelId($modelid12);
     $err12 .= validateModelName($name12);
     
     if($action12 == "new")
     {
         if(!BfPersonExists($personid12))
         {
             $err12 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid12))
         {
             $err12 = "Model Id Exists already.";
         }
         
     }
     elseif($action12 == "update")
     {
         if(!BfPersonExists($personid12))
         {
             $err12 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid12))
         {
             $err12 = "Model Id does not exist.";
         }
     }
     
     
     // Twelve Form
     if($modelid12 == "" && $name12 == "")
     {
         $form .= buildUpdateLine('12');
         $err12Flag = false;
     }
     elseif(strlen($err12) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('12',$modelid12, $name12, $breed12, $gender12, $division12, $action12, $err12);
         $err12Flag = true;
     }
     elseif(strlen($err12) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('12', $modelid12, $name12, $breed12, $gender12, $division12, $action12);
         $err12Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('12');
         $err12Flag = true;
     }   
          /////////////////////////////
     //  Thirteen
     ////////////////////////////
     $modelid13 = sanitizeIntegerOnly($_POST['id13']);
     $personid13 = substr($modelid13, 0, 3);
     $name13 = sanitizeAlphaNumericSpaceOnly($_POST['name13']);
     $breed13 = sanitizeAlphaNumericSpaceOnly($_POST['breed13']);
     $gender13 = sanitizeAlphaNumericSpaceOnly($_POST['gender13']);
     $division13 = sanitizeAlphaNumericSpaceOnly($_POST['division13']);
     
     $action13 = sanitizeCharacterOnly($_POST['action13']);

     $err13Flag = true;
     
     $err13 = "";
     $err13 .= validateModelId($modelid13);
     $err13 .= validateModelName($name13);
     
     if($action13 == "new")
     {
         if(!BfPersonExists($personid13))
         {
             $err13 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid13))
         {
             $err13 = "Model Id Exists already.";
         }
         
     }
     elseif($action13 == "update")
     {
         if(!BfPersonExists($personid13))
         {
             $err13 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid13))
         {
             $err13 = "Model Id does not exist.";
         }
     }
     
     
     // Thirteen Form
     if($modelid13 == "" && $name13 == "")
     {
         $form .= buildUpdateLine('13');
         $err13Flag = false;
     }
     elseif(strlen($err13) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('13',$modelid13, $name13, $breed13, $gender13, $division13, $action13, $err13);
         $err13Flag = true;
     }
     elseif(strlen($err13) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('13', $modelid13, $name13, $breed13, $gender13, $division13, $action13);
         $err13Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('13');
         $err13Flag = true;
     } 
     
          /////////////////////////////
     //  Fourteen
     ////////////////////////////
     $modelid14 = sanitizeIntegerOnly($_POST['id14']);
     $personid14 = substr($modelid14, 0, 3);
     $name14 = sanitizeAlphaNumericSpaceOnly($_POST['name14']);
     $breed14 = sanitizeAlphaNumericSpaceOnly($_POST['breed14']);
     $gender14 = sanitizeAlphaNumericSpaceOnly($_POST['gender14']);
     $division14 = sanitizeAlphaNumericSpaceOnly($_POST['division14']);
     
     $action14 = sanitizeCharacterOnly($_POST['action14']);

     $err14Flag = true;
     
     $err14 = "";
     $err14 .= validateModelId($modelid14);
     $err14 .= validateModelName($name14);
     
     if($action14 == "new")
     {
         if(!BfPersonExists($personid14))
         {
             $err14 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid14))
         {
             $err14 = "Model Id Exists already.";
         }
         
     }
     elseif($action14 == "update")
     {
         if(!BfPersonExists($personid14))
         {
             $err14 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid14))
         {
             $err14 = "Model Id does not exist.";
         }
     }
     
     
     // Fourteen Form
     if($modelid14 == "" && $name14 == "")
     {
         $form .= buildUpdateLine('14');
         $err14Flag = false;
     }
     elseif(strlen($err14) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('14',$modelid14, $name14, $breed14, $gender14, $division14, $action14, $err14);
         $err14Flag = true;
     }
     elseif(strlen($err14) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('14', $modelid14, $name14, $breed14, $gender14, $division14, $action14);
         $err14Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('14');
         $err14Flag = true;
     }    

     /////////////////////////////
     //  Fifteen
     ////////////////////////////
     $modelid15 = sanitizeIntegerOnly($_POST['id15']);
     $personid15 = substr($modelid15, 0, 3);
     $name15 = sanitizeAlphaNumericSpaceOnly($_POST['name15']);
     $breed15 = sanitizeAlphaNumericSpaceOnly($_POST['breed15']);
     $gender15 = sanitizeAlphaNumericSpaceOnly($_POST['gender15']);
     $division15 = sanitizeAlphaNumericSpaceOnly($_POST['division15']);
     
     $action15 = sanitizeCharacterOnly($_POST['action15']);

     $err15Flag = true;
     
     $err15 = "";
     $err15 .= validateModelId($modelid15);
     $err15 .= validateModelName($name15);
     
     if($action15 == "new")
     {
         if(!BfPersonExists($personid15))
         {
             $err15 = "Exhibitor Id does not exist.";
         }
         else if(BfModelExists($modelid15))
         {
             $err15 = "Model Id Exists already.";
         }
         
     }
     elseif($action15 == "update")
     {
         if(!BfPersonExists($personid15))
         {
             $err15 = "Exhibitor Id does not exist.";
         }
         else if(!BfModelExists($modelid15))
         {
             $err15 = "Model Id does not exist.";
         }
     }
     
     
     // Fifteen Form
     if($modelid15 == "" && $name15 == "")
     {
         $form .= buildUpdateLine('15');
         $err15Flag = false;
     }
     elseif(strlen($err15) > 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Invalid('15',$modelid15, $name15, $breed15, $gender15, $division15, $action15, $err15);
         $err15Flag = true;
     }
     elseif(strlen($err15) == 0 && $_POST['updatemodel'] == "Update")
     {
         $form .= buildUpdateLine_Valid('15', $modelid15, $name15, $breed15, $gender15, $division15, $action15);
         $err15Flag = false;
     }
     else
     {
         $form .= buildUpdateLine('15');
         $err15Flag = true;
     }    


     #########################
     ##########################
     ##########################
     //
     // Tail Form
     //

    $form .= '<input type="hidden" name="link" value="bfchangeform"/>
              <input type="hidden" name="showid" value="'.$showid.'"/>';
    $form .= '<input type="submit" class="statebutton" name="updatemodel" value="Update"/>';
    $form .= "</form>";
    
    if($err1Flag || $err2Flag || $err3Flag || $err4Flag || $err5Flag || $err6Flag || $err7Flag || $err8Flag || $err9Flag || $err10Flag || $err11Flag || $err12Flag || $err13Flag || $err14Flag || $err15Flag)
    {
        echo $form;
    }
    else 
    {
        $endForm = "<form><p>";
        
        //
        // One
        //
        if($action1 == "update")
        {
           if(Bf_ModelUpdate($personid1, $modelid1, $name1, $breed1, $gender1, $division1))
           {
               $endForm .= "Update of $modelid1 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid1 failed<br/>";
           }
        }
        elseif($action1 == "new")
        {
            if(Bf_ModelAdd($personid1, $modelid1, $name1, $breed1, $gender1, $division1))
            {
                $endForm .= "Addition of $modelid1 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid1))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }

        
        //
        // Two
        //
        if($action2 == "update" && $modelid2 != "")
        {
           if(Bf_ModelUpdate($personid2, $modelid2, $name2, $breed2, $gender2, $division2))
           {
               $endForm .= "Update of $modelid2 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid2 failed<br/>";
           }
        }
        elseif($action2 == "new" && $modelid2 != "")
        {
            
            if(Bf_ModelAdd($personid2, $modelid2, $name2, $breed2, $gender2, $division2))
            {
                $endForm .= "Addition of $modelid2 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid2))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Three
        //
        if($action3 == "update" && $modelid3 != "")
        {
           if(Bf_ModelUpdate($personid3, $modelid3, $name3, $breed3, $gender3, $division3))
           {
               $endForm .= "Update of $modelid3 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid3 failed<br/>";
           }
        }
        elseif($action3 == "new" && $modelid3 != "")
        {
            if(Bf_ModelAdd($personid3, $modelid3, $name3, $breed3, $gender3, $division3))
            {
                $endForm .= "Addition of $modelid3 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid3))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Four
        //
        if($action4 == "update" && $modelid4 != "")
        {
           if(Bf_ModelUpdate($personid4, $modelid4, $name4, $breed4, $gender4, $division4))
           {
               $endForm .= "Update of $modelid4 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid4 failed<br/>";
           }
        }
        elseif($action4 == "new" && $modelid4 != "")
        {
            if(Bf_ModelAdd($personid4, $modelid4, $name4, $breed4, $gender4, $division4))
            {
                $endForm .= "Addition of $modelid4 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid4))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Five
        //
        if($action5 == "update" && $modelid5 != "")
        {
           if(Bf_ModelUpdate($personid5, $modelid5, $name5, $breed5, $gender5, $division5))
           {
               $endForm .= "Update of $modelid5 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid5 failed<br/>";
           }
        }
        elseif($action5 == "new" && $modelid5 != "")
        {
            if(Bf_ModelAdd($personid5, $modelid5, $name5, $breed5, $gender5, $division5))
            {
                $endForm .= "Addition of $modelid5 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid5))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Six
        //
        if($action6 == "update" && $modelid6 != "")
        {
           if(Bf_ModelUpdate($personid6, $modelid6, $name6, $breed6, $gender6, $division6))
           {
               $endForm .= "Update of $modelid6 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid6 failed<br/>";
           }
        }
        elseif($action6 == "new" && $modelid6 != "")
        {
            if(Bf_ModelAdd($personid6, $modelid6, $name6, $breed6, $gender6, $division6))
            {
                $endForm .= "Addition of $modelid6 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid6))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Seven
        //
        if($action7 == "update" && $modelid7 != "")
        {
           if(Bf_ModelUpdate($personid7, $modelid7, $name7, $breed7, $gender7, $division7))
           {
               $endForm .= "Update of $modelid7 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid7 failed<br/>";
           }
        }
        elseif($action7 == "new" && $modelid7 != "")
        {
            if(Bf_ModelAdd($personid7, $modelid7, $name7, $breed7, $gender7, $division7))
            {
                $endForm .= "Addition of $modelid7 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid7))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Eight
        //
        if($action8 == "update" && $modelid8 != "")
        {
           if(Bf_ModelUpdate($personid8, $modelid8, $name8, $breed8, $gender8, $division8))
           {
               $endForm .= "Update of $modelid8 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid8 failed<br/>";
           }
        }
        elseif($action8 == "new" && $modelid8 != "")
        {
            if(Bf_ModelAdd($personid8, $modelid8, $name8, $breed8, $gender8, $division8))
            {
                $endForm .= "Addition of $modelid8 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid8))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Nine
        //
        if($action9 == "update" && $modelid9 != "")
        {
           if(Bf_ModelUpdate($personid9, $modelid9, $name9, $breed9, $gender9, $division9))
           {
               $endForm .= "Update of $modelid9 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid9 failed<br/>";
           }
        }
        elseif($action9 == "new" && $modelid9 != "")
        {
            if(Bf_ModelAdd($personid9, $modelid9, $name9, $breed9, $gender9, $division9))
            {
                $endForm .= "Addition of $modelid9 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid9))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
                //
        // Ten
        //
        if($action10 == "update" && $modelid10 != "")
        {
           if(Bf_ModelUpdate($personid10, $modelid10, $name10, $breed10, $gender10, $division10))
           {
               $endForm .= "Update of $modelid10 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid10 failed<br/>";
           }
        }
        elseif($action10 == "new" && $modelid10 != "")
        {
            if(Bf_ModelAdd($personid10, $modelid10, $name10, $breed10, $gender10, $division10))
            {
                $endForm .= "Addition of $modelid10 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid10))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        
        //
        // Eleven
        //
        if($action11 == "update" && $modelid11 != "")
        {
           if(Bf_ModelUpdate($personid11, $modelid11, $name11, $breed11, $gender11, $division11))
           {
               $endForm .= "Update of $modelid11 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid11 failed<br/>";
           }
        }
        elseif($action11 == "new" && $modelid11 != "")
        {
            if(Bf_ModelAdd($personid11, $modelid11, $name11, $breed11, $gender11, $division11))
            {
                $endForm .= "Addition of $modelid11 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid11))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Twelve
        //
        if($action12 == "update" && $modelid12 != "")
        {
           if(Bf_ModelUpdate($personid12, $modelid12, $name12, $breed12, $gender12, $division12))
           {
               $endForm .= "Update of $modelid12 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid12 failed<br/>";
           }
        }
        elseif($action12 == "new" && $modelid12 != "")
        {
            if(Bf_ModelAdd($personid12, $modelid12, $name12, $breed12, $gender12, $division12))
            {
                $endForm .= "Addition of $modelid12 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid12))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Thirteen
        //
        if($action13 == "update" && $modelid13 != "")
        {
           if(Bf_ModelUpdate($personid13, $modelid13, $name13, $breed13, $gender13, $division13))
           {
               $endForm .= "Update of $modelid13 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid13 failed<br/>";
           }
        }
        elseif($action13 == "new" && $modelid13 != "")
        {
            if(Bf_ModelAdd($personid13, $modelid13, $name13, $breed13, $gender13, $division13))
            {
                $endForm .= "Addition of $modelid13 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid13))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Fourteen
        //
        if($action14 == "update" && $modelid14 != "")
        {
           if(Bf_ModelUpdate($personid14, $modelid14, $name14, $breed14, $gender14, $division14))
           {
               $endForm .= "Update of $modelid14 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid14 failed<br/>";
           }
        }
        elseif($action14 == "new" && $modelid14 != "")
        {
            if(Bf_ModelAdd($personid14, $modelid14, $name14, $breed14, $gender14, $division14))
            {
                $endForm .= "Addition of $modelid14 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid14))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }
        
        //
        // Fifteen
        //
        if($action15 == "update" && $modelid15 != "")
        {
           if(Bf_ModelUpdate($personid15, $modelid15, $name15, $breed15, $gender15, $division15))
           {
               $endForm .= "Update of $modelid15 was successful<br/>";
           }
           else
           {
               $endForm .= "Update of $modelid15 failed<br/>";
           }
        }
        elseif($action15 == "new" && $modelid15 != "")
        {
            if(Bf_ModelAdd($personid15, $modelid15, $name15, $breed15, $gender15, $division15))
            {
                $endForm .= "Addition of $modelid15 was successful</br>";
            }
            else
            {
                if(BfModelExists($modelid15))
                {
                    $endForm .= "BF Model ID already exists: $id</br>";
                }
                else
                {
                    $endForm .= "Model was not added. </br>";
                }
            }
        }

        
        $endForm .= "</p></form>";
        
        echo $endForm;
    }
     
 }
 
 function buildUpdateLine($number)
 {
     $number = sanitizeIntegerOnly($number);

     
     $form .= '<input type="text" size="4"  name="id'.$number.'"/> 
                            <input type="text" size="30" name="name'.$number.'"/> 
                            <input type="text" size="11" name="breed'.$number.'"/> 
                            <input type="text" size ="3" name="gender'.$number.'"/>
                            <input type="text" size="8"  name="division'.$number.'"/>
                            <select name="action'.$number.'">
                                <option value="update">Update</option>
                                <option value="new">New</option>
                            </select>';
     
     return $form;
 }
 
 function buildUpdateLine_Valid($number, $id, $name, $breed, $gender, $division, $action)
 {
     $number = sanitizeIntegerOnly($number);
     
     $form .= ' <input type="text" size="4"  name="id'.$number.'"       value="'.$id.'"   /> 
                <input type="text" size="30" name="name'.$number.'"     value="'.$name.'"   /> 
                <input type="text" size="11" name="breed'.$number.'"    value="'.$breed.'"   /> 
                <input type="text" size ="3" name="gender'.$number.'"   value="'.$gender.'"   />
                <input type="text" size="8"  name="division'.$number.'" value="'.$division.'"   />
                <select name="action'.$number.'">';
    
     if($action == "new")
     {
        $form .=  ' <option value="update">Update</option>
        <option selected value="new">New</option>
        </select>';
     }
     else 
     {
        $form .=  ' <option value="update">Update</option>
        <option value="new">New</option>
        </select>';     
     }

     
     return $form;
 }
 
 function buildUpdateLine_Invalid($number, $id, $name, $breed, $gender, $division, $action, $errMsg)
 {
     $number = sanitizeIntegerOnly($number);
     
     $form .= "$errMsg</br>";
     $form .= ' <input type="text" size="4"  name="id'.$number.'"       value="'.$id.'"   /> 
                <input type="text" size="30" name="name'.$number.'"     value="'.$name.'"   /> 
                <input type="text" size="11" name="breed'.$number.'"    value="'.$breed.'"   /> 
                <input type="text" size ="3" name="gender'.$number.'"   value="'.$gender.'"   />
                <input type="text" size="8"  name="division'.$number.'" value="'.$division.'"   />
                <select name="action'.$number.'">';
     
     if($action == "new")
     {
        $form .=  ' <option value="update">Update</option>
        <option selected value="new">New</option>
        </select>';
     }
     else 
     {
        $form .=  ' <option value="update">Update</option>
        <option value="new">New</option>
        </select>';     
     }

     return $form;
 }
 
?>