<?php 
  include 'include/layout.php';  
  require 'include/validation.php';	
  session_start();
  
  requireUserLoggedIn();
  requireWebsiteAdminPrivilege();
  
  if(isset($_GET['link']))
  {
      $link = sanitizeCharacterOnly($_GET['link']);
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
         
            if($link == "updateblog")
            {
                $form .= '<form method="POST" action="show_manager.php?link=createshow&horseshow='.$horseshowid.'" >
                            <p>';
                
                $form .= '<label>Title</label>
                            <input name="blogtitle" type="text" size="30"/>
                            <br/>';
                
                $form .= '<label>Text</label>
                            <textarea rows="2" cols="20">
                            </textarea>
                            <br/>';
                
                $form .= '</p>
                            <input class="cancelbutton" type="submit" name="cancelcreatenewshow" value="Cancel"/>
                            <input class="statebutton" type="submit" name="addblogpost" value="Add"/>
                            </form>';
                
                echo $form;
            }
            elseif($link == "updatemotto")
            {
                echo "Update Motto Form";
            }
            else
            {
                echo "default stuff";
            }
	 ?>
        <div class="cleaner"></div>
    </div> <!-- end of main column -->
    
  <div class="side_column_w200">
            	
        <div class="box">

           <?php
               printManageWebsiteSideBar();
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