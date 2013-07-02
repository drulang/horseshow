<?php 
  include_once 'include/layout.php';  
  require_once 'include/validation.php';	
  session_start();
  
  requireUserLoggedIn();
  
  #require that User is registerd for
  requireShowManager();
  
  $arrayHorseShowId = SelectUserHorseShows($_SESSION['USER_ID']);
  
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
     
     $horseshowid = LookupHorseshowId($showid);

     //Redirect user if they're not authorized to view the show
     if(!UserAuthorizedForShow($_SESSION['USER_ID'], $horseshowid))
     {
         header("Location: show_manager.php");
     }
  }
  elseif(isset($_POST['showid']))
  {
     $showid = sanitizeIntegerOnly($_POST['showid']);
     
     $horseshowid = LookupHorseshowId($showid);

     //Redirect user if they're not authorized to view the show
     if(!UserAuthorizedForShow($_SESSION['USER_ID'], $horseshowid))
     {
         header("Location: show_manager.php");
     }
  }
  
  //CLASS
  if(isset($_GET['classid']))
  {
      $classid = sanitizeIntegerOnly($_GET['classid']);
  }
  elseif(isset($_POST['classid']))
  {
      $classid = sanitizeIntegerOnly($_POST['classid']);
  }
  
  //SECTION
  if(isset($_GET['sectionid']))
  {
      $sectionid = sanitizeIntegerOnly($_GET['sectionid']);
  }
  elseif(isset($_POST['sectionid']))
  {
      $sectionid = sanitizeIntegerOnly($_POST['sectionid']);
  }
  
  //DIVISION
  if(isset($_GET['divisionid']))
  {
      $divisionid = sanitizeIntegerOnly($_GET['divisionid']);
  }
  elseif(isset($_POST['divisionid']))
  {
      $divisionid = sanitizeIntegerOnly($_POST['divisionid']);
  }
  
  //SPLIT
  if(isset($_GET['splitid']))
  {
      $splitid = sanitizeIntegerOnly($_GET['splitid']);
  }
  elseif(isset($_POST['splitid']))
  {
      $splitid = sanitizeIntegerOnly($_POST['splitid']);
  }
  
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
    
    
    <div id="main_column_small">
        <div id="templatemo_submenu_small">       
                <?php
                //  if(isset($_GET['horseshow']))
                if(isset($showid))
                {
                    printShowResultsSubmenu($showid);

                    echo "<br/><br/>";
                }
                ?>
        </div>

      
          <?php
          //MAIN CONTENT
            if(isset($showid) && $link == "view")
            {
                printView($showid);
            }
            elseif(isset($showid) && $link =="process" && isset($classid) && isset($sectionid) && isset($divisionid) && isset($splitid))
            {
                printResultForm($showid, $splitid, $sectionid, $divisionid);
            }
            elseif(isset($showid) && $link =="process" && isset($classid) && isset($sectionid) && isset($divisionid))
            {

                $array = SelectChildList($showid, $classid);
                
                $array = mysqli_fetch_array($array, MYSQLI_ASSOC);
                
                if(sizeof($array) > 0)
                {
                    printSplitForm($showid, $divisionid, $sectionid, $classid);
                }
                else
                {
                    printResultForm($showid, $classid, $sectionid, $divisionid);
                }
            }
            elseif(isset($showid) && $link =="process" && isset($divisionid) && isset($sectionid))
            {
                printClassForm($showid, $divisionid, $sectionid);
            }
            elseif(isset($showid) && $link == "process" && isset($divisionid))
            {
                printSectionForm($showid, $divisionid);
            }
            elseif(isset($showid) && $link == "process")
            {
                printDivisionForm($showid);
            }
            elseif(isset($showid) && $link =="nan")
            {
                printNanForm($showid);
            }
            else //dashboard
            {
                if(isset($horseshowid))
                {
                   
                   $form = "<p><h5>Results for $showid</h5></p>";
                   
                   $form .= '<div id="profile_wrapper">';
                   
                    $form .= '<div id="profile_info_show">';
                    
                        $form .= "<b>General Information</b>";

                                $form .= "<ul>";
                                    $form .= "<li>First Place </li>";
                                    $form .= "<li>Second Place: </li>";
                                    $form .= "<li>Third Place: </li>";
                                    $form .= "<li>Fourth Place: </li>";
                                    $form .= "<li>Fifth Place: </li>";
                                    $form .= "<li>Sixth Place: </li>";
                                    $form .= "<li>Seventh Place: </li>";
                                    
                                $form .= "</ul>";

                                
                    $form .= '</div>'; #end profile info div 
                    
                    
                    $form .= '<div id="profile_image_show">';
                    
                    #$form .= '<img class="imgShow" src="images/bf_open_show.jpeg"/>';
                    
                    $form .= '</div>'; #end profile image div
                    
                    
                    $form .= '<div id="profile_footer"></div>';
                    
                    $form .= "</div>"; #end profile wrapper div
                     
                    
                }
                
                echo $form;
                
            }
	  ?>
        
        <div class="cleaner"></div>
    </div> <!-- end of main column -->
    
  <div class="side_column_w200">
            	
        <div class="box">

          <?php 
            
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

function printView($showid)
{
    $divisions = SelectParentList($showid);
    
    while($row = mysqli_fetch_row($divisions))
    {
        $divId = $row[0];
        $divName = $row[1];
        
        echo '<h5>'.$divId.' '.$divName."</h5><br/>";
        
        //SECTION
        $sections = SelectChildList($showid, $divId);
        
        while($row = mysqli_fetch_row($sections))
        {
            $secId = $row[0];
            $secName = $row[1];
            
            echo '<b>&nbsp&nbsp&nbsp'.$secId.' '.$secName."</b><br/>";
            
            //CLASSES
            $classes = SelectChildList($showid, $secId);
            
            while($row = mysqli_fetch_row($classes))
            {
                $classId = $row[0];
                $className = $row[1];
                
                echo '<b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.$classId.' '.$className.'</b><br/>';
                
                //SPLITS
                $splits = SelectChildList($showid, $classId);
                $splitArray = mysqli_fetch_array($splits, MYSQLI_ASSOC);
                
                if(sizeof($splitArray) > 0)
                {
                    $splits = SelectChildList($showid, $classId);
                    while($row = mysqli_fetch_row($splits))
                    {
                        $splitid = $row[0];
                        $splitName = $row[1];
                        
                        echo '<b>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.$splitid.' '.$splitName.'</b><br/>';
                        
                        $result = BfResultSet($showid, $splitid);
                        
                        
                        while($row = mysqli_fetch_row($result))
                        {
                            echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.$row[0].'<br/>';
                        }
                    }
                }
                else
                {
                     $result = BfResultSet($showid, $classId);
                     
                     while($row = mysqli_fetch_row($result))
                     {
                         echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.$row[0].'<br/>';
                     }
                }
            }
        }
          
    }
}

function printDivisionForm($showid)
{
    $form .= "<h5>Choose Division</h5>";
    
    $form .= '<form action="show_results.php" method="post">';
   
    $form .= '<select name="divisionid">';
    
    $array = SelectParentList($showid);
    
    while($row = mysqli_fetch_row($array))
    {
        $form .= '<option value="'.$row[0].'">'.$row[0].' '.$row[1].'</option>';
    }
    
    $form .= '</select>';
    
    $form .= '<input type="hidden" name="link" value="process"/>
              <input type="hidden" name="showid" value="'.$showid.'"/>';
    
    $form .= '<input class="statebutton" type="submit" value="Next"/>';
   
   $form .= '<br/><br/></form>';
   
   echo $form;
}

function printSectionForm($showid, $divisionid)
{
    $form .= "<h5>Choose Section</h5>";
    
    $form .= '<form action="show_results.php" method="post">';
   
        $form .= '<select name="sectionid">';
    
        $array = SelectChildList($showid, $divisionid);
        
        while($row = mysqli_fetch_row($array))
        {
            $form .= '<option value="'.$row[0].'">'.$row[0].' '.$row[1].'</option>';
        }
        
    $form .= '</select>';
    
    $form .= '<input type="hidden" name="link" value="process"/>
              <input type="hidden" name="showid" value="'.$showid.'"/>
              <input type="hidden" name="divisionid" value="'.$divisionid.'"/>';
    
    
    $form .= '<input class="statebutton" type="submit" value="Next"/>';
   
   $form .= '<br/><br/></form>';
   
   echo $form;
}

function printClassForm($showid, $divisionid, $sectionid)
{
   $form  .= "<h5>Choose Class</h5>"; 
    
   $form .= '<form action="show_results.php" method="post">';
   
    $form .= '<select name="classid">';
    
      $array = SelectChildList($showid, $sectionid);
      
      while($row = mysqli_fetch_row($array))
      {
          $form .= '<option value="'.$row[0].'">'.$row[0].'  '.$row[1].'</option>';
      }
    

    $form .= '</select>';
    

    $form .= '<input type="hidden" name="link" value="process"/>
              <input type="hidden" name="showid" value="'.$showid.'"/>
              <input type="hidden" name="divisionid" value="'.$divisionid.'"/>
              <input type="hidden" name="sectionid" value="'.$sectionid.'"/>';
    
    
    $form .= '<input class="statebutton" type="submit" value="Next"/>';
   
   $form .= '<br/><br/></form>';
   
   echo $form;
}

function printSplitForm($showid, $divisionid, $sectionid, $classid)
{
    $form .= "<h5>Choose Split</h5>";
    
    $form .= '<form action="show_results.php" method="post">';
    
    $form .= '<select name="splitid">';
    
    $array = SelectChildList($showid, $classid);
    
    while($row = mysqli_fetch_row($array))
    {
        $form .= '<option value="'.$row[0].'">'.$row[0].' '.$row[1].'</option>';
    }
    
    $form .= '</select>';
    
    $form .= '<input type="hidden" name="link" value="process"/>
              <input type="hidden" name="showid" value="'.$showid.'"/>
              <input type="hidden" name="divisionid" value="'.$divisionid.'"/>
              <input type="hidden" name="sectionid" value="'.$sectionid.'"/>
              <input type="hidden" name="classid" value="'.$classid.'"/>';
  
    $form .= '<input class="statebutton" type="submit" value="Next"/>';
   
    $form .= '<br/><br/></form>';
   
    echo $form;
}

function printResultForm($showid, $classid, $sectionid, $divisionid)
{
    $processResult = sanitizeCharacterOnly($_POST['processresult']);
    $className = SelectDivName($classid);
    
    // Models
    $firstModel = SelectPlacement($showid, $classid, Rank::FIRST);
    $secondModel = SelectPlacement($showid, $classid, Rank::SECOND);
    $thirdModel = SelectPlacement($showid, $classid, Rank::THIRD);
    $fourthModel = SelectPlacement($showid, $classid, Rank::FOURTH);
    $fifthModel = SelectPlacement($showid, $classid, Rank::FIFTH);
    $sixthModel = SelectPlacement($showid, $classid, Rank::SIXTH); 
    $seventhModel = SelectPlacement($showid, $classid, Rank::SEVENTH); 
    $eigthModel = SelectPlacement($showid, $classid, Rank::EIGTH);
    $ninthModel = SelectPlacement($showid, $classid, Rank::NINETH);
    $tenthModel = SelectPlacement($showid, $classid, Rank::TENTH);
    $hmModel = SelectPlacement($showid, $classid, Rank::HM);
    
    $form .= "<h5>Results for $classid : $className</h5>";
    
    $form .= '<form action="show_results.php" method="post">';
    
    if($processResult == "Submit")
    {
        $firstPlaceId = sanitizeIntegerOnly($_POST['firstID']);
        $secondPlaceId = sanitizeIntegerOnly($_POST['secondID']);
        $thirdPlaceId = sanitizeIntegerOnly($_POST['thirdID']);
        $fourthPlaceId = sanitizeIntegerOnly($_POST['fourthID']);
        $fifthPlaceId = sanitizeIntegerOnly($_POST['fifthID']);
        $sixthPlaceId = sanitizeIntegerOnly($_POST['sixthID']);
        $seventhPlaceId = sanitizeIntegerOnly($_POST['seventhID']);
        $eigthPlaceId = sanitizeIntegerOnly($_POST['eigthID']);
        $ninthPlaceId = sanitizeIntegerOnly($_POST['ninthID']);
        $tenthPlaceId = sanitizeIntegerOnly($_POST['tenthID']);
        $hmPlaceId = sanitizeIntegerOnly($_POST['hmID']);
        
        //
        //First Place
        //
        if($firstPlaceId == $firstModel['ModelId'])
        {
            $form .= '<label>First Place</label>';
            $form .= '<input type="text" name="firstID" size="7" value="'.$firstModel["ModelId"].'" />';
            $form .= '<input type="text" name="firstName" size="45" disabled="disabled" value="'.$firstModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($firstPlaceId))
        {
            $firstModel = BfModelSearch($firstPlaceId);
           
            $firstPlaceError = false;
            $form .= '<label>First Place</label>';
            $form .= '<input type="text" name="firstID" size="7" value="'.$firstModel["ModelId"].'" />';
            $form .= '<input type="text" name="firstName" size="45" disabled="disabled" value="'.$firstModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::FIRST))   
            {
                if(UpdatePlacement($classid, $showid, Rank::FIRST, $firstModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else 
            {
                if(InsertPlacement($classid, $showid, Rank::FIRST, $firstModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $firstPlaceError = true;
            $form .= '<label>First Place</label>';
            $form .= '<input type="text" name="firstID" size="7" value="'.$firstPlaceId.'"/> Invalid Model ID';
            $form .= '<input type="text" name="firstName" size="45" disabled="disabled"/>';
        }
         
        //
        //Second Place
        //
        if($secondPlaceId == $secondModel['ModelId'])
        {
            $form .= '<label>Second Place</label>';
            $form .= '<input type="text" name="secondID" size="7" value="'.$secondModel['ModelId'].'"/>';
            $form .= '<input type="text" name="secondName" size="45" disabled="disabled" value="'.$secondModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($secondPlaceId))
        {
            $secondModel = BfModelSearch($secondPlaceId);
            
            $form .= '<label>Second Place</label>';
            $form .= '<input type="text" name="secondID" size="7" value="'.$secondModel['ModelId'].'"/>';
            $form .= '<input type="text" name="secondName" size="45" disabled="disabled" value="'.$secondModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::SECOND))   
            {
                if(UpdatePlacement($classid, $showid, Rank::SECOND, $secondModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else 
            {
                if(InsertPlacement($classid, $showid, Rank::SECOND, $secondModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $form .= '<label>Second Place</label>';
            $form .= '<input type="text" name="secondID" size="7" value="'.$secondModel['ModelId'].'"/> Invalid Model ID';
            $form .= '<input type="text" name="secondName" size="45" disabled="disabled" value="'.$secondModel['ModelName'].'"/>';
        }
        
        //
        //Third Place
        //
        if($thirdPlaceId == $thirdModel['ModelId'])
        {
            $form .= '<label>Third Place</label>';
            $form .= '<input type="text" name="thirdID" size="7" value="'.$thirdModel['ModelId'].'"/>';
            $form .= '<input type="text" name="thirdName" size="45" disabled="disabled" value="'.$thirdModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($thirdPlaceId))
        {
            $thirdModel = BfModelSearch($thirdPlaceId);
            
            $form .= '<label>Third Place</label>';
            $form .= '<input type="text" name="thirdID" size="7" value="'.$thirdModel['ModelId'].'"/>';
            $form .= '<input type="text" name="thirdName" size="45" disabled="disabled" value="'.$thirdModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::THIRD))
            {
                if(UpdatePlacement($classid, $showid, Rank::THIRD, $thirdModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else
            {
                if(InsertPlacement($classid, $showid, Rank::THIRD, $thirdModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $form .= '<label>Third Place</label>';
            $form .= '<input type="text" name="thirdID" size="7" value="'.$thirdModel['ModelId'].'"/> Invalid Model ID';
            $form .= '<input type="text" name="thirdName" size="45" disabled="disabled" value="'.$thirdModel['ModelName'].'"/>';
        }
        
        //
        //Fourth Place
        //
        if($fourthPlaceId == $fourthModel['ModelId'])
        {
            $form .= '<label>Fourth Place</label>';
            $form .= '<input type="text" name="fourthID" size="7" value="'.$fourthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="fourthName" size="45" disabled="disabled" value="'.$fourthModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($fourthPlaceId))
        {
            $fourthModel = BfModelSearch($fourthPlaceId);
            
            $form .= '<label>Fourth Place</label>';
            $form .= '<input type="text" name="fourthID" size="7" value="'.$fourthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="fourthName" size="45" disabled="disabled" value="'.$fourthModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::FOURTH))
            {
                if(UpdatePlacement($classid, $showid, Rank::FOURTH, $fourthModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else
            {
                if(InsertPlacement($classid, $showid, Rank::FOURTH, $fourthModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $form .= '<label>Fourth Place</label>';
            $form .= '<input type="text" name="fourthID" size="7" value="'.$fourthModel['ModelId'].'"/> Invalid Model ID';
            $form .= '<input type="text" name="fourthName" size="45" disabled="disabled" value="'.$fourthModel['ModelName'].'"/>';
        }
        
        //
        // Fifth Place
        //
        if($fifthPlaceId == $fifthModel['ModelId'])
        {
            $form .= '<label>Fifth Place</label>';
            $form .= '<input type="text" name="fifthID" size="7" value="'.$fifthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="fifthName" size="45" disabled="disabled" value="'.$fifthModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($fifthPlaceId))
        {
            $fifthModel = BfModelSearch($fifthPlaceId);
            
            $form .= '<label>Fifth Place</label>';
            $form .= '<input type="text" name="fifthID" size="7" value="'.$fifthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="fifthName" size="45" disabled="disabled" value="'.$fifthModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::FIFTH))
            {
                if(UpdatePlacement($classid, $showid, Rank::FIFTH, $fifthModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else
            {
                if(InsertPlacement($classid, $showid, Rank::FIFTH, $fifthModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $form .= '<label>Fifth Place</label>';
            $form .= '<input type="text" name="fifthID" size="7" value="'.$fifthModel['ModelId'].'"/> Invalid Model ID';
            $form .= '<input type="text" name="fifthName" size="45" disabled="disabled" value="'.$fifthModel['ModelName'].'"/>';
        }
        
        //
        // Sixth Place
        //
        if($sixthPlaceId == $sixthModel['ModelId'])
        {
            $form .= '<label>Sixth Place</label>';
            $form .= '<input type="text" name="sixthID" size="7" value="'.$sixthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="sixthName" size="45" disabled="disabled" value="'.$sixthModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($sixthPlaceId))
        {
            $sixthModel = BfModelSearch($sixthPlaceId);
            
            $form .= '<label>Sixth Place</label>';
            $form .= '<input type="text" name="sixthID" size="7" value="'.$sixthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="sixthName" size="45" disabled="disabled" value="'.$sixthModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::SIXTH))
            {
                if(UpdatePlacement($classid, $showid, Rank::SIXTH, $sixthModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else
            {
                if(InsertPlacement($classid, $showid, Rank::SIXTH, $sixthModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $form .= '<label>Sixth Place</label>';
            $form .= '<input type="text" name="sixthID" size="7" value="'.$sixthModel['ModelId'].'"/> Invalid Model ID';
            $form .= '<input type="text" name="sixthName" size="45" disabled="disabled" value="'.$sixthModel['ModelName'].'"/>';
        }
        
        //
        // Seventh Place
        //
        if($seventhPlaceId == $seventhModel['ModelId'])
        {
            $form .= '<label>Seventh Place</label>';
            $form .= '<input type="text" name="seventhID" size="7" value="'.$seventhModel['ModelId'].'"/>';
            $form .= '<input type="text" name="seventhName" size="45" disabled="disabled" value="'.$seventhModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($seventhPlaceId))
        {
            $seventhModel = BfModelSearch($seventhPlaceId);
            
            $form .= '<label>Seventh Place</label>';
            $form .= '<input type="text" name="seventhID" size="7" value="'.$seventhModel['ModelId'].'"/>';
            $form .= '<input type="text" name="seventhName" size="45" disabled="disabled" value="'.$seventhModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::SEVENTH))
            {
                if(UpdatePlacement($classid, $showid, Rank::SEVENTH, $seventhModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else
            {
                if(InsertPlacement($classid, $showid, Rank::SEVENTH, $seventhModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $form .= '<label>Seventh Place</label>';
            $form .= '<input type="text" name="seventhID" size="7" value="'.$seventhModel['ModelId'].'"/> Invalid Model ID';
            $form .= '<input type="text" name="seventhName" size="45" disabled="disabled" value="'.$seventhModel['ModelName'].'"/>';
        }
        
        //
        // Eighth Place
        //
        if($eigthPlaceId == $eigthModel['ModelId'])
        {
            $form .= '<label>Eighth Place</label>';
            $form .= '<input type="text" name="eigthID" size="7" value="'.$eigthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="eigthName" size="45" disabled="disabled" value="'.$eigthModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($eigthPlaceId))
        {
            $eigthModel = BfModelSearch($eigthPlaceId);
            
            $form .= '<label>Eighth Place</label>';
            $form .= '<input type="text" name="eigthID" size="7" value="'.$eigthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="eigthName" size="45" disabled="disabled" value="'.$eigthModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::EIGTH))
            {
                if(UpdatePlacement($classid, $showid, Rank::EIGTH, $eigthModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else
            {
                if(InsertPlacement($classid, $showid, Rank::EIGTH, $eigthModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $form .= '<label>Eighth Place</label>';
            $form .= '<input type="text" name="eigthID" size="7" value="'.$eigthModel['ModelId'].'"/> Invalid Model ID';
            $form .= '<input type="text" name="eigthName" size="45" disabled="disabled" value="'.$eigthModel['ModelName'].'"/>';
        }
        
        //
        // Ninth Place
        //
        if($ninthPlaceId == $ninthModel['ModelId'])
        {
            $form .= '<label>Ninth Place</label>';
            $form .= '<input type="text" name="ninthID" size="7" value="'.$ninthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="ninthName" size="45" disabled="disabled" value="'.$ninthModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($ninthPlaceId))
        {
            $ninthModel = BfModelSearch($ninthPlaceId);
            
            $form .= '<label>Ninth Place</label>';
            $form .= '<input type="text" name="ninthID" size="7" value="'.$ninthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="ninthName" size="45" disabled="disabled" value="'.$ninthModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::NINETH))
            {
                if(UpdatePlacement($classid, $showid, Rank::NINETH, $ninthModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else
            {
                if(InsertPlacement($classid, $showid, Rank::NINETH, $ninthModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $form .= '<label>Ninth Place</label>';
            $form .= '<input type="text" name="ninthID" size="7" value="'.$ninthModel['ModelId'].'"/> Invalid Model ID';
            $form .= '<input type="text" name="ninthName" size="45" disabled="disabled" value="'.$ninthModel['ModelName'].'"/>';
        }
        
        //
        // Tenth Place
        //
        if($tenthPlaceId == $tenthModel['ModelId'])
        {
            $form .= '<label>Tenth Place</label>';
            $form .= '<input type="text" name="tenthID" size="7" value="'.$tenthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="tenthName" size="45" disabled="disabled" value="'.$tenthModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($tenthPlaceId))
        {
            $tenthModel = BfModelSearch($tenthPlaceId);
            
            $form .= '<label>Tenth Place</label>';
            $form .= '<input type="text" name="tenthID" size="7" value="'.$tenthModel['ModelId'].'"/>';
            $form .= '<input type="text" name="tenthName" size="45" disabled="disabled" value="'.$tenthModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::TENTH))
            {
                if(UpdatePlacement($classid, $showid, Rank::TENTH, $tenthModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else
            {
                if(InsertPlacement($classid, $showid, Rank::TENTH, $tenthModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $form .= '<label>Tenth Place</label>';
            $form .= '<input type="text" name="tenthID" size="7" value="'.$tenthModel['ModelId'].'"/> Invalid Model ID';
            $form .= '<input type="text" name="tenthName" size="45" disabled="disabled" value="'.$tenthModel['ModelName'].'"/>';
        }
        
        //
        // HM Place
        //
        if($hmPlaceId == $hmModel['ModelId'])
        {
            $form .= '<label>HM Place</label>';
            $form .= '<input type="text" name="hmID" size="7" value="'.$hmModel['ModelId'].'"/>';
            $form .= '<input type="text" name="hmName" size="45" disabled="disabled" value="'.$hmModel['ModelName'].'"/>';
        }
        elseif(IsBfModel($hmPlaceId))
        {
            $hmModel = BfModelSearch($hmPlaceId);
            
            $form .= '<label>HM Place</label>';
            $form .= '<input type="text" name="hmID" size="7" value="'.$hmModel['ModelId'].'"/>';
            $form .= '<input type="text" name="hmName" size="45" disabled="disabled" value="'.$hmModel['ModelName'].'"/>';
            
            if(PlacementExists($classid, $showid, Rank::HM))
            {
                if(UpdatePlacement($classid, $showid, Rank::HM, $hmModel['PersonModelId']))
                {
                    $form .= "Updated";
                }
                else
                {
                    $form .= "Unable to update.";
                }
            }
            else
            {
                if(InsertPlacement($classid, $showid, Rank::HM, $hmModel['PersonModelId']))
                {
                    $form .= "Inserted";
                }
                else
                {
                    $form .= "Unable to insert model.";
                }
            }
        }
        else
        {
            $form .= '<label>HM Place</label>';
            $form .= '<input type="text" name="hmID" size="7" value="'.$hmModel['ModelId'].'"/> Invalid Model ID';
            $form .= '<input type="text" name="hmName" size="45" disabled="disabled" value="'.$hmModel['ModelName'].'"/>';
        }
    }
    else
    {     
        $form .= '<label>First Place</label>';
        $form .= '<input type="text" name="firstID" size="7" value="'.$firstModel["ModelId"].'" />';
        $form .= '<input type="text" name="firstName" size="45" disabled="disabled" value="'.$firstModel['ModelName'].'"/>';
        
        $form .= '<label>Second Place</label>';
        $form .= '<input type="text" name="secondID" size="7" value="'.$secondModel['ModelId'].'"/>';
        $form .= '<input type="text" name="secondName" size="45" disabled="disabled" value="'.$secondModel['ModelName'].'"/>';
        
        $form .= '<label>Third Place</label>';
        $form .= '<input type="text" name="thirdID" size="7" value="'.$thirdModel['ModelId'].'"/>';
        $form .= '<input type="text" name="thirdName" size="45" disabled="disabled" value="'.$thirdModel['ModelName'].'"/>';
        
        $form .= '<label>Fourth Place</label>';
        $form .= '<input type="text" name="fourthID" size="7" value="'.$fourthModel['ModelId'].'"/>';
        $form .= '<input type="text" name="fourthName" size="45" disabled="disabled" value="'.$fourthModel['ModelName'].'"/>';
        
        $form .= '<label>Fifth Place</label>';
        $form .= '<input type="text" name="fifthID" size="7" value="'.$fifthModel['ModelId'].'"/>';
        $form .= '<input type="text" name="fifthName" size="45" disabled="disabled" value="'.$fifthModel['ModelName'].'"/>';
        
        $form .= '<label>Sixth Place</label>';
        $form .= '<input type="text" name="sixthID" size="7" value="'.$sixthModel['ModelId'].'"/>';
        $form .= '<input type="text" name="sixthName" size="45" disabled="disabled" value="'.$sixthModel['ModelName'].'"/>';
        
        $form .= '<label>Seventh Place</label>';
        $form .= '<input type="text" name="seventhID" size="7" value="'.$seventhModel['ModelId'].'"/>';
        $form .= '<input type="text" name="seventhName" size="45" disabled="disabled" value="'.$seventhModel['ModelName'].'"/>';
        
        $form .= '<label>Eighth Place</label>';
        $form .= '<input type="text" name="eigthID" size="7" value="'.$eigthModel['ModelId'].'"/>';
        $form .= '<input type="text" name="eigthName" size="45" disabled="disabled" value="'.$eigthModel['ModelName'].'"/>';
        
        $form .= '<label>Ninth Place</label>';
        $form .= '<input type="text" name="ninthID" size="7" value="'.$ninthModel['ModelId'].'"/>';
        $form .= '<input type="text" name="ninthName" size="45" disabled="disabled" value="'.$ninthModel['ModelName'].'"/>';
        
        $form .= '<label>Tenth Place</label>';
        $form .= '<input type="text" name="tenthID" size="7" value="'.$tenthModel['ModelId'].'"/>';
        $form .= '<input type="text" name="tenthName" size="45" disabled="disabled" value="'.$tenthModel['ModelName'].'"/>';
        
        $form .= '<label>HM Place</label>';
        $form .= '<input type="text" name="hmID" size="7" value="'.$hmModel['ModelId'].'"/>';
        $form .= '<input type="text" name="hmName" size="45" disabled="disabled" value="'.$hmModel['ModelName'].'"/>';
    }
        
    $form .= "<br/>";
    
    $form .= '<input type="submit" class="statebutton" name="processresult" value="Submit" />';
        
    $form .= '<input type="hidden" name="link" value="process"/>
              <input type="hidden" name="showid" value="'.$showid.'"/>
              <input type="hidden" name="classid" value="'.$classid.'"/>
              <input type="hidden" name="sectionid" value="'.$sectionid.'"/>
              <input type="hidden" name="divisionid" value="'.$divisionid.'"/>';
    
    $form .= "<br/><br/></form>";
    
    echo $form;
}


function printNanForm($showid)
{
    $process = sanitizeCharacterOnly($_POST['nansubmit']);
    
    if(isset($_POST['classid']))
    {
        $classid = sanitizeIntegerOnly($_POST['classid']);
        $className = SelectDivName($classid);
    }
    
    $form .= "<h5>NAN Form</h5>";
    
    $form .= '<form action="show_results.php" method="post">';
    
    

    
    if($process == "Submit")
    {
        $form .= '<select name="classid">';

        $array = SelectBfClassList($showid);

        while($row = mysqli_fetch_row($array))
        {
            if($row[0] == $classid)
            {
                $form .= '<option selected="selected" value="'.$row[0].'">'.$row[0].'  '.$row[1].'</option>';
            }
            else
            {
                $form .= '<option value="'.$row[0].'">'.$row[0].'  '.$row[1].'</option>';
            }
        }
        $form .= '</select>';
        
        $firstId = sanitizeIntegerOnly($_POST['nanfirst']);
        $firstError = true;
        
        $secondId = sanitizeIntegerOnly($_POST['nansecond']);
        $secondError = true;
        
        if(IsBfModel($firstId))
        {
            $firstModel = BfModelSearch($firstId);
            $firstError = false;
            
            $form .= '<label>First Place</label>';
            $form .= '<input type="text" size="7" name="nanfirst" value="'.$firstId.'"/>';
    
        }
        else 
        {
            $firstError = true;
            $form .= '<label>First Place</label>';
            $form .= '<input type="text" size="7" name="nanfirst" value="'.$firstId.'"/> Invalid Model ID';
        
        }
        
        if(IsBfModel($secondId))
        {
            $secondModel = BfModelSearch($secondId);
            $secondError = false;
            
            $form .= '<label>Second Place</label>';
            $form .= '<input type="text" size="7" name="nansecond" value="'.$secondId.'"/>';
        }
        else 
        {
            $secondError = true;
            $form .= '<label>Second Place</label>';
            $form .= '<input type="text" size="7" name="nansecond" value = "'.$secondId.'"/> Invalid Model ID';
        }
        
        if(isset($_POST['numberInClass']))
        {
            $numberInClass = $_POST['numberInClass'];
            
            $form .= '<label>Number In Class</label>';
            $form .= '<input type="text" size="7" name="numberInClass" value="'.$numberInClass.'" />';
        }
        else 
        {
            $form .= '<label>Number In Class</label>';
            $form .= '<input type="text" size="7" name="numberInClass"/>';
        }
        
        if(!$firstError && !$secondError)
        {
             $form .= '<p>';
        
             $form .= "$classid. $className ($numberInClass): 1.".$firstModel['ModelName']."/".$firstModel['ExInitials'].", 2.".$secondModel['ModelName']."/".$secondModel['ExInitials'];
    
             $form .= '</p>';
        }
    }
    else 
    {
        $form .= '<select name="classid">';

        $array = SelectBfClassList($showid);

        while($row = mysqli_fetch_row($array))
        {
            $form .= '<option value="'.$row[0].'">'.$row[0].'  '.$row[1].'</option>';
        }
        $form .= '</select>';
        
        $form .= '<label>First Place</label>';
        $form .= '<input type="text" size="7" name="nanfirst"/>';
        
        $form .= '<label>Second Place</label>';
        $form .= '<input type="text" size="7" name="nansecond"/>';
        
        $form .= '<label>Number In Class</label>';
        $form .= '<input type="text" size="7" name="numberInClass"/>';
    }
    
    $form .= '<input type="hidden" name="link" value="nan"/>';
    $form .= '<input type="hidden" name="showid" value="'.$showid.'"/>';
    
    $form .= "<br/>";
    
    $form .= '<input class="cancelbutton" type="submit" name="nanclear" value="Clear">';
    $form .= '<input class="statebutton" type="submit" name="nansubmit" value="Submit"/>';
   
    
    $form .= "</form>";
    
    echo $form;
}

?>