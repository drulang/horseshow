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
  
  if(isset($_GET['showid']))
  {
      $showid = sanitizeIntegerOnly($_GET['showid']);
  }
  
  if(isset($_GET['personid']))
  {
      $personid = sanitizeIntegerOnly($_GET['personid']);
      
      $personArray = PersonStats($personid);
      
      $personFirstName = $personArray["FirstName"];
      $personLastName = $personArray['LastName'];
      $nickName = $personArray["NickName"];
      
      $personExId = $personArray["ShowExhibitorId"];
      $personComment = $personArray["Comment"];
      
      $addr1 = $personArray["Addr1"];
      $addr2 = $personArray["Addr2"];
      $addr3 = $personArray["Addr3"];
      $city = $personArray["City"];
      $state = $personArray["State"];
      $zip = $personArray["Zip"];
      
      $email = $personArray["Email"];
      $phoneCell = $personArray["PhoneCell"];
      
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
                printShowPersonSubmenu($showid, $personid);
            ?>

        </div>
          <?php
            //MAIN CONTENT
            if($link == "editshowpers" && isset($personid) && isset($showid))
            {
                printEditShowPersonForm($personid);
            }
            elseif($link == "showpershorselist" && isset($personid) && isset($showid))
            {
                printPersonHorseListForm($personid);
            }
            elseif($link == "showpersdocuments" && isset($personid) && isset($showid))
            {
                echo "Print person documents form";
            }
            else
            {
                printDashboard($personArray);
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

 function printEditShowPersonForm($personid)
 {
        echo "<h5>Edit Person</h5>";
        echo 
            '<form method="POST" action="mange_login?link=changepassword">
                <p>
                    <label>First Name</label>
                        <input type="text" name="persfirstname" size="30" value="'.$GLOBALS['personFirstName'].'"/>
                    <br/>
                    
                    <label>Last Name</label>
                        <input name="perslastname" type="text" size="30" value = "'.$GLOBALS['personLastName'].'"/> 
                    <br/>
                    
                    <label>Exhibitor Number</label>
                        <input name="persexnum" type="text" size="30" value = "'.$GLOBALS['personExId'].'"/> 
                    <br/>
                    
                    <label>Comment</label>
                        <input name="perscomment" type="text" size="45" value = "'.$GLOBALS['personComment'].'"/>
                    <br/>
                    
                </p>
                <input class="cancelbutton" type="submit" name="cancelupdatelogin" value="Cancel"/>
                <input class="statebutton" type="submit" name="updatelogin" value="Update"/>
                </form>    
            ';
 }

 function printPersonHorseListForm($personid)
 {
     $horseList = SelectShowPersonHorses($personid);
     
     if(!$horseList)
     {
         echo("Error Selecting Horses for Person".$personid);
     }
     else
     {
            $table = '<table id="table">
            <tr>
                <th>Show Model ID</th>
                <th>Show Model Name</th>
                <th>Show Model Breed</th>
            </tr>';

            $count = 1;    
            while ($row = mysqli_fetch_array($horseList, MYSQL_ASSOC))
            {
                if($count % 2)
                {
                    $table .= '<tr>
                                <td><a href="show_horse.php?horseid='.$row["ID"].'">'.$row["SHOW_MODEL_ID"].'</a></td>
                                <td>'.$row["SHOW_MODEL_NAME"].'</td>
                                <td>'.$row["SHOW_MODEL_BREED"].'</td>
                                </tr>
                                ';
                }
                else
                {
                    $table .= '<tr class="alt">
                                <td><a href="show_horse.php?horseid='.$row["ID"].'">'.$row["SHOW_MODEL_ID"].'</a></td>
                                <td>'.$row["SHOW_MODEL_NAME"].'</td>
                                <td>'.$row["SHOW_MODEL_BREED"].'</td>
                                </tr>
                                ';
                }

                $count++;
            }

            $table .= "</table>";

            echo $table;
     }
 }
 
 function printDashboard($personArray)
 {
     //This is bad way to do this, will fix later
     
      $personFirstName = FormatStringPascalCase($personArray["FirstName"]);
      $personLastName = $personArray['LastName'];
      $nickName = $personArray["NickName"];
      
      $personId = $personArray["ID"];
      $personExId = $personArray["ShowExhibitorId"];
      $personComment = $personArray["Comment"];
      
      $addr1 = $personArray["Addr1"];
      $addr2 = $personArray["Addr2"];
      $addr3 = $personArray["Addr3"];
      $city = $personArray["City"];
      $state = $personArray["State"];
      $zip = $personArray["Zip"];
      
      $email = $personArray["Email"];
      $phoneCell = $personArray["PhoneCell"];
      
      $modelCount = $personArray["ModelCount"];
      
      //Build Form
      
     $form .= "<p><h5>$personFirstName $personLastName</h5></p>";
     
     $form .= '<div id="profile_wrapper">';
     
         $form .= '<div id="profile_info_show">';

                $form .= '<ul class="side_menu">';

                $form .= "<li>Website Person ID:  $personId</li>";
                $form .= "<li>Show Exhibitor ID:  $personExId</li>";
                $form .= "<li>Nick Name:  $nickName</li>";
                
                $form .= "<br/>";

                $form .= "<li>Address Line 1:  $addr1 </li>";
                $form .= "<li>Address Line 2:  $addr2</li>";
                $form .= "<li>Address Line 3:  $addr3</li>";
                $form .= "<li>City/State/Zip:  $city, $state $zip</li>";

                $form .= "<br/>";
                
                $form .= "<li>Home Phone: </li>";
                $form .= "<li>Cell Phone:  $phoneCell</li>";
                $form .= "<li>Email:  $email</li>";

                $form .= "</ul>";

        $form .= "</div>";  #end profile info         
        
        
        $form .= '<div id="profile_image_show">

                    <img class="imgShow" src="images/user_icon_200.png"/>

                    <ul class="side_menu">
                    <li><a href="#">Change Profile Pic</a></li>
                    <li><a href="manage_profile.php">Edit Profile</a></li>
                    </ul>';

        $form .= '</div>'; #end profile_image
        
            //
            // Statistics
            //
            $form .= "<p><b>Numbers</b>";

            $form .= "<ul>";

            $form .= "<li>Total Models Registered: $modelCount";

            $form .= "</ul></p>";

     $form .= "</div>"; #end profile wrapper
    echo $form; 
 }
?>