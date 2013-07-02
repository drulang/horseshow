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
  
  if(isset($_GET['horseid']))
  {
      $horseid = sanitizeIntegerOnly($_GET['horseid']);
      
      $result = SelectHorseInformation($horseid);
      
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
         
      $modelPersonId = $row["PERSON_ID"];
      $showModelName = $row["SHOW_MODEL_NAME"];
      $showModelId = $row["SHOW_MODEL_ID"];
      $showModelBreed = $row["SHOW_MODEL_BREED"];
      $showModelGender = $row["SHOW_MODEL_GENDER"];
      
      $horseArray = ModelStats($horseid);
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
                printShowHorseSubmenu($horseid);
            ?>

        </div>
          <?php
            //MAIN CONTENT
          //test
            if($link == "edithorseinfo" && isset($horseid))
            {
                printEditShowHorseForm();
            }
            else if ($link == "horsedocuments" && isset($horseid))
            {
                echo "print horse document form";
            }
            else if ($link == "results" && isset($horseid))
            {
                echo "print results form";
            }
            else
            {
                PrintHDashboard($horseArray);
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
 function printEditShowHorseForm($horseid)
 {
        echo "<h5>Edit Horse</h5>";
        echo 
            '<form method="POST" action="mange_login?link=changepassword">
                <p>
                
                    <label>Name</label>
                        <input type="text" name="showmodelid" size="30" value="'.$GLOBALS['showModelId'].'"/>
                    <br/>
                    
                    <label>Name</label>
                        <input type="text" name="showmodelname" size="30" value="'.$GLOBALS['showModelName'].'"/>
                    <br/>
                    
                    <label>Breed</label>
                        <input name="showmodelbreed" type="text" size="30" value = "'.$GLOBALS['showModelBreed'].'"/> 
                    <br/>
                    
                    <label>Gender</label>
                        <input name="showmodelgender" type="text" size="30" value = "'.$GLOBALS['showModelGender'].'"/> 
                    <br/>
                    
                    <br/>
                    
                </p>
                <input class="cancelbutton" type="submit" name="cancelupdatelogin" value="Cancel"/>
                <input class="statebutton" type="submit" name="updatelogin" value="Update"/>
                </form>    
            ';
 }
 
 function PrintHDashboard($horseArray)
 {
     //Set Fields
     $modelId = $horseArray["ModelId"];
     $personId = $horseArray["PersonId"];
     $modelName = $horseArray["ShowModelName"];
     $modelBreed = $horseArray["ShowModelBreed"];
     $modelGender = $horseArray["ShowModelGender"];
     $showModelId = $horseArray["ShowModelId"];
     $userField1 = $horseArray["UserField1"];
     
     $form .= "<p><h5>$modelName</h5></p>";
     
     $form .= '<div id="profile_wrapper">';
     
     
        $form .= '<div id="profile_info_user">';
                $form .= "<ul>";

                    $form .= "<li>Website Id: $modelId</li>";
                    $form .= "<li>Show Model Id: $showModelId</li>";
                    $form .= '<li>Person Id: <a href = "show_person.php?personid='.$personId.'">'.$personId.'</a></li>';

                    $form .= "<br/>";

                    $form .= "<li>Model Name: $modelName</li>";
                    $form .= "<li>Model Breed: $modelBreed</li>";
                    $form .= "<li>Model Gender: $modelGender</li>";
                    $form .= "<li>Division: $userField1</li>";

                $form .= "</ul>";
        
        $form .= '</div>'; #end profile info show
          
         
        
        $form .= '<div id="profile_image_show">

                    <img class="imgShow" src="images/user_icon_200.png"/>

                    <ul class="side_menu">
                    <li><a href="#">Change Profile Pic</a></li>
                    </ul>';

        $form .= '</div>'; #end profile_image
        
        
        $form .= '<div id="profile_footer"></div>';
        
     $form .= "</div>"; #end profile wrapper
     
     echo $form;
 }
?>

