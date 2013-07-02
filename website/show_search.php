<?php 
  include_once 'include/layout.php';  
  require_once 'include/validation.php';	
  session_start();
  
  requireUserLoggedIn();
  
  #require that User is registerd for
  requireShowManager();
  
  $arrayHorseShowId = SelectUserHorseShows($_SESSION['USER_ID']);
  
  if(isset($_GET['entity']))
  {      
      $entity = sanitizeCharacterOnly($_GET['entity']);
  }
  
  if(isset($_GET['search']))
  {
      $search = sanitizeAlphaNumericOnly($_GET['search']);
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

      
          <?php
          
            $form = "<h5>Search HorseShow</h5>";
            $form .= 
                '<form method="GET" action="show_search.php">
                    <p>
                        <select name="entity">';
            
            if($entity == "Model")
            {
                $form .=' <option value = "Exhibitor">Exhibitor</option>
                          <option selected value = "Model"/>Model</option>';
            }
            else
            {
                $form .=' <option selected value = "Exhibitor">Exhibitor</option>
                          <option value = "Model"/>Model</option>';
            }
            
            $form .= '</select>

                            <input name="search" type="text" size="60"/> 
                       
                        <input type="submit" name="updatelogin" value="Search"/>';
            
            if(isset($search) && isset($entity))
            {
                $form .= "</br>";
                
                if($entity == "Exhibitor")
                {
                    //
                    // EXHIBITOR
                    //
                    
                    $resultSet = searchExhibitor($search);
                    
                        if(!$resultSet || sizeof($resultSet) == 0)
                        {
                            $form .= "No results found.";
                        }
                        else
                        {
                                $table = '<table id="table">
                                <tr>
                                    <th>Exhibitor ID</th>
                                    <th>Show ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                </tr>';

                                $count = 1;    
                                while ($row = mysqli_fetch_array($resultSet, MYSQL_ASSOC))
                                {
                                    if($count % 2)
                                    {
                                        $table .= '<tr>
                                                    <td><a href = "show_person.php?personid='.$row["ID"].'">'.$row["SHOW_EXHIBITOR_ID"].'</a></td>
                                                    <td>'.$row["SHOW_ID"].'</td>
                                                    <td>'.$row["FIRST_NAME"].'</td>
                                                    <td>'.$row["LAST_NAME"].'</td>
                                                    <td>'.$row["EMAIL"].'</td>
                                                    </tr>
                                                    ';
                                    }
                                    else
                                    {
                                        $table .= '<tr class="alt">
                                                    <td><a href= "show_person.php?personid='.$row["ID"].'">'.$row["SHOW_EXHIBITOR_ID"].'</a></td>
                                                    <td>'.$row["SHOW_ID"].'</td>
                                                    <td>'.$row["FIRST_NAME"].'</td>
                                                    <td>'.$row["LAST_NAME"].'</td>
                                                    <td>'.$row["EMAIL"].'</td>
                                                    </tr>
                                                    ';
                                    }

                                    $count++;
                                }

                                $table .= "</table>";

                                $form .= $table;
                        }
                }
                else if ($entity == "Model")
                {
                    //
                    //  MODEL
                    //
                    
                    $resultSet = searchModel($search);
                    
                        if(!$resultSet || sizeof($resultSet) == 0)
                        {
                            $form .= "No results found.";
                        }
                        else
                        {
                                $table = '<table id="table">
                                        <tr>
                                            <th>Model ID</th>
                                            <th>Model Name</th>
                                            <th>Person Name</th>
                                            <th>Person Initials</th>
                                        </tr>';

                                $count = 1;    
                                while ($row = mysqli_fetch_array($resultSet, MYSQL_ASSOC))
                                {
                                    if($count % 2)
                                    {
                                        $table .= '<tr>
                                                    <td><a href = "show_horse.php?horseid='.$row["ID"].'">'.$row["SHOW_MODEL_ID"].'</a></td>
                                                    <td>'.$row["SHOW_MODEL_NAME"].'</td>
                                                    <td>'.$row["PERSON_NAME"].'</td>
                                                    <td>'.$row["NICKNAME"].'</td>
                                                    </tr>
                                                    ';
                                    }
                                    else
                                    {
                                        $table .= '<tr class="alt">
                                                    <td><a href = "show_horse.php?horseid='.$row["ID"].'">'.$row["SHOW_MODEL_ID"].'</a></td>
                                                    <td>'.$row["SHOW_MODEL_NAME"].'</td>
                                                    <td>'.$row["PERSON_NAME"].'</td>
                                                    <td>'.$row["NICKNAME"].'</td>
                                                    </tr>
                                                    ';
                                    }

                                    $count++;
                                }

                                $table .= "</table>";

                                $form .= $table;
                        }
                }
                else
                {
                    $form .= "No results returned.";
                }
            }

            $form .= ' </p>
                    
                    </form>    
                ';
            
            echo $form;
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

