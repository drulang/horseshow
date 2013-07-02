<?php 
  session_start();
  include_once 'include/layout.php';
  include_once 'include/validation.php';

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

<div id="templatemo_content_small">

	<div id="main_column_small">
            
            <?php
                $mainContent = "";
                $blogArray = SelectTopFourBlogs();

                if($blogArray)
                {
                    $count = 0;
                    while($row = mysqli_fetch_array($blogArray, MYSQL_BOTH))
                    {

                        if($count != 0)
                        {
                            $post = '<div class="margin_bottom_10"></div>';
                        }

                        $post .= '<div class="section_w500">';

                            $title = '<h5>'.$row["TITLE"].'</h5>';
                            $blog = $row["BLOG"];

                            $post .= $title.$blog;
                            
                        $post .= '</div>';
                        $post .= '<div class="margin_bottom_20"></div>';

                        $mainContent .= $post;

                        $count++;
                    }

                    echo $mainContent;
                }
                else
                {
                    echo "No blogs selected";
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