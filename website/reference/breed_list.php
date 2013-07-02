<?php 
  session_start();
  include_once '../include/layout.php';
  include_once '../include/validation.php';
  
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
               
               $breedList = SelectBreed();
               
               echo "<h4>By Michelle E. Masters</h4>";
               
               $table = '<table id="table">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>';
               
              $count = 1;    
               while ($row = mysqli_fetch_array($breedList, MYSQL_ASSOC))
               {
                   if($count % 2)
                    {
                        $table .= '<tr class="tr">
                                    <td>'.$row["ID"].' </td>
                                    <td>'.$row["LONG_NAME"].' </td>
                                    <td>'.$row["DESCRIPTION"].'</td>
                                   </tr>
                                  ';
                    }
                    else
                    {
                        $table .= '<tr class="alt">
                                    <td>'.$row["ID"].' </td>
                                    <td>'.$row["LONG_NAME"].' </td>
                                    <td>'.$row["DESCRIPTION"].'</td>
                                   </tr>
                                  ';
                    }
                    
                    $count++;
               }
               
               $table .= "</table>";
               
               echo $table;
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