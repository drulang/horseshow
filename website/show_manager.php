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
  
  if(isset($_GET['horseshow']))
  {
     $horseshowid = sanitizeIntegerOnly($_GET['horseshow']);

     //Redirect user if they're not authorized to view the show
     if(!UserAuthorizedForShow($_SESSION['USER_ID'], $horseshowid))
     {
         header("Location: show_manager.php");
     }
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
    
    
    <div id="main_column">
        <div id="templatemo_submenu">       
                <?php
                //  if(isset($_GET['horseshow']))
                if(isset($horseshowid))
                {
                    //$horseshow = strtoupper(filter_var($_GET["horseshow"], FILTER_SANITIZE_STRING));
                    //$horseshow = preg_replace('#\W#', '', $horseshow);
                    printShowManagerSubmenu($horseshowid);

                    echo "<br/><br/>";
                }
                ?>
        </div>

      
          <?php
          //MAIN CONTENT
            if(isset($horseshowid) && $link == "editinfo")
            {
                
                printEditHorseShowForm($horseshowid);
 
            }
            elseif(isset($horseshowid) && $link == "activeshows")
            {
               // $horseshowid = strtoupper(filter_var($_GET['horseshow'], FILTER_SANITIZE_STRING));
                /*!!!!!!
                 * Need to validate that user has access to the show!!! Super Important!!!!!
                !!!!!!!!!
                */
                
                $activeShows = SelectActiveShows($_SESSION['USER_ID'], $horseshowid);
                
                $table = '<table id="table">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Open Date</th>
                            <th>Show Status</th>
                        </tr>';
                $count = 1;    
                while ($row = mysqli_fetch_array($activeShows, MYSQL_ASSOC))
                {
                    if($count % 2)
                    {
                        $table .= '<tr>
                                    <td><a href="manage_show.php?showid='.$row['SHOW_ID'].'">'.$row["SHOW_ID"].'</a></td>
                                    <td>'.$row["SHOW_TITLE"].' </td>
                                    <td>'.$row["SHOW_OPEN_DATE"].' </td>
                                    <td>'.$row["SHOW_STATUS"].'</td>
                                   </tr>
                                  ';
                    }
                    else
                    {
                        $table .= '<tr class="alt">
                                    <td><a href="manage_show.php?showid='.$row['SHOW_ID'].'">'.$row["SHOW_ID"].'</a></td>
                                    <td>'.$row["SHOW_TITLE"].' </td>
                                    <td>'.$row["SHOW_OPEN_DATE"].' </td>
                                    <td>'.$row["SHOW_STATUS"].'</td>
                                   </tr>
                                  ';
                    }
                    
                    $count++;
                }
                
                $table .= '</table>';
                
                echo $table;
            }
            elseif(isset($horseshowid) && $link == "createshow")
            {
                printCreateShowForm($horseshowid);
            }
            elseif(isset($horseshowid) && $link == "showhistory")
            {
                echo "print show history form";
            }
            elseif(isset($horseshowid) && $link == "showdocuments")
            {
                echo "print show documents form";
            }
            else
            {
                if(isset($horseshowid))
                {
                   $stats = HorseShowStats($horseshowid);
                   
                   $form = "<p><h5>Horse Show Dashboard</h5></p>";
                   
                   $form .= '<div id="profile_wrapper">';
                   
                    $form .= '<div id="profile_info_show">';
                    
                        $form .= "<b>General Information</b>";

                                $form .= "<ul>";
                                    $form .= "<li>HorseShow ID: ".$stats["HorseshowId"]."</li>";
                                    $form .= "<li>Name One: ".str_pad($stats["NameOne"], 15)."</li>";
                                    $form .= "<li>Name Two: ".$stats["NameTwo"]."</li>";
                                    $form .= "<li>Address Line 1: ".$stats["Addr1"]."</li>";
                                    $form .= "<li>Address Line 2: ".$stats["Addr2"]."</li>";
                                    $form .= "<li>Address Line 3: ".$stats["Addr3"]."</li>";
                                    $form .= "<li>Primary Email: ".$stats["EmailPrimary"]."</li>";
                                    $form .= "<li>Secondary Email: ".$stats["EmailSecondary"]."</li>";
                                    $form .= "<li>Website: ".$stats["Website"]."</li>";
                                    $form .= "<li>Primary Phone: ".$stats["PhonePrimary"]."</li>";
                                    $form .= "<li>Secondary Phone: ".$stats["PhoneSecondary"]."</li>";
                                    $form .= "<li>Rating: ".$stats["Rating"]."</li>";
                                    $form .= "<li>Nahmsa Qualified: ".$stats["Nahmsa"]."</li>";
                                $form .= "</ul>";


                           # $form .= "<p><b>Statistcs</b>";
                           #     $form .= "<ul>";
                           #         $form .= "<li>Exhibitors signed up for HorseShow:  ".$stats["PersonCount"]."</li>";                
                           #         $form .= "<li>Total Horses Registered: ".$stats["ModelCount"]."</li>";    
                           #     $form .= "</ul>";
                           #  $form .= "</p>";
                                
                    $form .= '</div>'; #end profile info div 
                    
                    
                    $form .= '<div id="profile_image_show">';
                    
                    $form .= '<img class="imgShow" src="images/bf_open_show.jpeg"/>';
                    
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

function printCreateShowForm($horseshowid)
{
    if($_POST['cancelcreatenewshow'] == "Cancel")
    {
        header("Location: show_manager.php");
    }
    
    $horseshowid = sanitizeIntegerOnly($horseshowid);
    $horseshowname =  SelectHorseShowName($horseshowid);
    
    #Error Flags
    $showTitleError = TRUE;
    $openDateError = TRUE;
    $commentError = TRUE;

    $form =  '<h5>Create a Show for <i class="orange">'.$horseshowname.'</i></h5>';
    
    $form .= '<form method="POST" action="show_manager.php?link=createshow&horseshow='.$horseshowid.'" >
                        <p>';
    ##
    #  Validate ShowTitle
    ##
        $showtitle = sanitizeAlphaNumericSpaceOnly($_POST['showtitle']);
        $showTitleErrorMessage = validateRequiredField($showtitle, 0, 45);

        if($_POST['createnewshow'] == "Create" && strlen($showTitleErrorMessage) > 0)
        {
            $showTitleError = TRUE;
            $form .= '<label>Show Title</label>
                    <input type="text" name="showtitle"  size="45"/> '.$showTitleErrorMessage;
        }
        elseif($_POST['createnewshow'] == "Create" && $showTitleErrorMessage == 0)
        {
            $showTitleErrorMessage = FALSE;
            $form .= '<label>Show Title</label>
                    <input type="text" name="showtitle" size="45" value = "'.$showtitle.'" />';
        }
        else
        {
            $form .= '<label>Show Title</label>
                    <input type="text" name="showtitle"  size="45"/>';
        }    



    $form .= ' <br/>
               <label>Show Status</label>';
    $form .= printShowStatusList();
    
    ##
    #  Validate Date
    ##
    #    !!!!NEED TO PROPERY VALIDATE DATE
        $opendate = $_POST['opendate'];
        $dateErrorMessage = validateRequiredField($opendate, 6, 11);
        
        if($_POST['createnewshow'] == "Create" && strlen($dateErrorMessage) > 0)
        {
             $openDateError = TRUE;
             
             $form .= '<label>Open Date</label>
                       <input name="opendate" type="text" size="30" value = "'.$opendate.'" /> '.$dateErrorMessage.'
                       <br/>';
        }
        elseif($_POST['createnewshow'] == "Create" && strlen($dateErrorMessage) == 0)
        {
            $openDateError = FALSE;
            
             $form .= '<label>Open Date</label>
                       <input name="opendate" type="text" size="30" value= "'.$opendate.'" /> 
                       <br/>';
        }
        else
        {
            $form .= '<label>Open Date</label>
                      <input name="opendate" type="text" size="30" /> 
                      <br/>';
        }
        
    ##
    #  Validate Comment
    ##
        $comment = sanitizeAlphaNumericSpaceOnly($_POST['comment']);
        $commentErrorMessage = validateRequiredField($comment, -1, 100);
        
        if($_POST['createnewshow'] == "Create" && strlen($showTitleErrorMessage) > 0)
        {
            $commentError = TRUE;
            $form .= '<label>Comment</label>
                      <input name="comment" type="text" size="30" value = "'.$comment.'" />'.$commentErrorMessage.'
                      <br/>';
        }
        elseif($_POST['createnewshow'] == "Create" && strlen($showTitleErrorMessage) == 0)
        {
            $commentError = FALSE;
            
             $form .= '<label>Comment</label>
                       <input name="comment" type="text" size="30" value = "'.$comment.'" />
                       <br/>';
        }
        else
        {
             $form .= '<label>Comment</label>
                       <input name="comment" type="text" size="30"/>
                       <br/>';
        }

    $form .= '</p>
              <input class="cancelbutton" type="submit" name="cancelcreatenewshow" value="Cancel"/>
              <input class="statebutton" type="submit" name="createnewshow" value="Create"/>
              </form>';
    
    if($showTitleErrorMessage || $openDateError || $commentError)
    {
         echo $form;
    }
    elseif($_POST['createnewshow'] == "Create")
    {
         $showstatusid = $_POST['showstatustype'];
        CreateShow($showstatusid, $horseshowid, $showtitle, $opendate, $comment);
        
        header("Location: show_manager.php?link=activeshows&horseshow=".$horseshowid);
    }
    else
    {
       echo $form;
    }
    

}


function printShowStatusList()
{
    $listbox = '<select name="showstatustype">';
    
    $statusArray = SelectOpenShowStatusTypes();
    
    while ($row = mysqli_fetch_array($statusArray, MYSQL_ASSOC))
    {
        $listbox .= '<option value="'.$row['ID'].'">'.$row['STATUS_NAME'].'</option>';
    }
    
    $listbox .= '</select>
                 <br/>';
    
    return $listbox;
}


function printEditHorseShowForm($horseshowid)
{
     $horseshowname =  SelectHorseShowName($horseshowid);
    
    $form =  '<h5>Edit Information for <i class="orange">'.$horseshowname.'</i></h5>';
    
    $form .= '<form method="POST" action="show_manager.php?link=createshow&horseshow='.$horseshowid.'" >
              <p>';
    
    $form .= '<label>Name One</label>
                       <input name="hsNameOne" type="text" size="30" />
                       <br/>';
    
    $form .= '<label>Name Two</label>
                       <input name="hsNameTwo" type="text" size="30" />
                       <br/>';
    
     $form .= '<label>Address Line 1</label>
                       <input name="hsNameTwo" type="text" size="30" />
                       <br/>';
     
     $form .= '<label>Address Line 2</label>
                       <input name="hsNameTwo" type="text" size="30" />
                       <br/>';
      
     $form .= '<label>City</label>
                       <input name="hsNameTwo" type="text" size="30" />
                       ';
     
     $form .= '<label>State</label>
                       <input name="hsNameTwo" type="text" size="30" />
                       <br/>';
     
     $form .= '<label>Zip</label>
                       <input name="hsNameTwo" type="text" size="30" />
                       <br/>';
     
     $form .= '<label>Website</label>
                       <input name="hsNameTwo" type="text" size="30" />
                       <br/>';
     
     $form .= '<label>Website</label>
                       <input name="hsNameTwo" type="text" size="30" />
                       <br/>';
     
     $form .= '<label>Primary Phone Number</label>
                       <input name="hsNameTwo" type="text" size="30" />
                       <br/>';
     
     $form .= '<label>Nahmsa Qualified</label>
                       <input name="hsNameTwo" type="text" size="30" />
                       <br/>';
     
    $form .= '</p>
            <input class="cancelbutton" type="submit" name="cancelcreatenewshow" value="Cancel"/>
            <input class="statebutton" type="submit" name="createnewshow" value="Create"/>
            </form>';
    
    echo $form;
    
}
?>
