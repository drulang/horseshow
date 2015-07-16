<?php
    include_once('format_func.php');
    require_once('recaptchalib.php');
    
    $cssLocation = "http://".$_SERVER['SERVER_NAME']."/horseshow/style.css";
    $serverLocation = "http://".$_SERVER['SERVER_NAME']."/horseshow/";
    
    function printLogo() {
        echo
        '
        <h1>
          <a href="#">modelhorseshow.com 
          <span>All of the fun, none of the vet bills.</span>
          </a>
        </h1>
        
        ';
    }
    
    function printHtmlHead()
    {
        echo 
        '
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>ModelHorseShow.com</title>
            <meta name="keywords" content="model horse, horse breed, breed list, horse, model, model horse show, show, plastic horse, breyer, model horse images, model horse pictures" />
            <meta name="description" content="ModelHorseShow.com is a site dedicated to helping you collect and show your model horses." />
            <link href="'.$GLOBALS['cssLocation'].'" rel="stylesheet" type="text/css" />

            <script language="javascript" type="text/javascript">
                function clearText(field)
                {
                    if (field.defaultValue == field.value) field.value = \'\';
                    else if (field.value == \'\') field.value = field.defaultValue;
                }
            </script>

            </head>
        ';
        
        
    }
    
    function printTopBar()
    {
        
        $form = '
            <div id="templatemo_top_bar">

                <div class="rss_contact_section">

                    <div class="rss_contact user_profile">';
                        
                        if(isset($_SESSION['LOGIN_NAME']) && $_SESSION['INACTIVIE_DATE'] == '')
                        {
                            $form .= '<a href="profile.php" class="logout">My Profile</a>';
                        }


        $form .= '  </div> 

                    <div class="rss_contact user_button">';
                      
                            if(isset($_SESSION['LOGIN_NAME']) && $_SESSION['INACTIVIE_DATE'] == '')
                            {
                                $form .= printLogout();
                            }
                       
        $form .='   </div>

                </div>
            </div> <!-- end of top bar -->
        ';
        
        echo $form;
    }
    
    function printSearch()
    {
        echo '<div class="box">
            <form method="GET" action="show_search.php">
                <p>
                    <select name="entity">
                    <option value = "Exhibitor">Exhibitor</option>
                    <option value = "Model"/>Model</option>
                    </select>

                    <input name="search" type="text" size="18"/> 
                </p>

                 <input type="submit" class="statebutton" name="updatelogin" value="Search"/>
            </form>
        </div>';
        
    }
    
    function printFooter()
    {
        echo '
                <div id="templatemo_footer_wrapper">

                        <div id="templatemo_footer">

                            <div class="section_w184">
                              <h5>About</h5>
                                <ul class="footer_menu_list">
                                  <li><a href="#">Terms Of Service</a></li>
                                  <li><a href="#">Security Policy</a></li> 
                                  <li><a href="#">Become a Developer</a></li>
                                  <li><a href="#">Become a Contributor</a></li>
                                </ul>
                            </div>
                           
                            <div class="margin_bottom_20"></div>

                            <div class="section_w940">
                                    Copyright © 2012 <a href="http://www.modelhorseshow.com">ModelHorseShow</a> | <a href="http://www.templatemo.com" target="_parent">Designed by Templatemo</a> | <a href="http://www.drulang.com">Implementation by Dru lang</a>
                            </div>

                            <div class="cleaner"></div>
                        </div> <!-- end of footer -->

                </div> <!-- end of footer wrapper -->
        ';
    }

    //Returns UL
    function printMenu()
    {	
        echo 
        ' 
            <ul>
                <li id="current"><a href="'.$GLOBALS['serverLocation'].'index.php">Home</a></li>
                <li><a href="'.$GLOBALS['serverLocation'].'login.php">Login</a></li>
                <li><a href="'.$GLOBALS['serverLocation'].'comingsoon.php">Articles</a></li>
                <li><a href="'.$GLOBALS['serverLocation'].'comingsoon.php">Blog</a></li>
                <li><a href="'.$GLOBALS['serverLocation'].'comingsoon.php">Reference</a></li>
                <li><a href="'.$GLOBALS['serverLocation'].'reference/breed_list.php">Breed List</a></li>
            </ul>
        ';
    }
    

    function printSideBar()
    {
            echo
            '
                <h3>Quick Link</h3>
                <ul class="side_menu">
                    <li><a href="create_user.php">Create an Account</a></li>
                    <li><a href="index.php">Register Your Horses</a></li>
                    <li><a href="comingsoon.php">Become a Show Admin</a></li>
                    <li><a href="comingsoon.php">Upcoming Events</a></li>
                </ul>';
    }
    
    function printRandomQuote()
    {
        echo SelectRandomQuoteFormatted();
    }

    function printLoginSideBar()
    {

            echo
            '
                <h3>Quick Link</h3>
                <ul class="side_menu">
                    <li><a href="comingsoon.php">Need Help Logging In?</a></li>
                </ul>
            ';

    }

    function printMain()
    {
            echo 
            ' 
            <h1>We Welcome You!</h1>
            <p><strong>ModelHorseShow.com</strong> is a site dedicated to helping you participate and manage your models and model horse shows.  Please stay tuned as we develop
               more features, references, and articles to be your one-stop-shop for everything horse related!</p>
            
            <h1>Quick Quote</h1>
            <blockquote>
                <p>We\'re seeing an incredible increase in online activity and have already made plans to rapidly vamp up content.</p>
            </blockquote>
        ';
    }
    
    function printTopThreeBlogPosts($blogArray)
    {
        
        
    }

    function printLogin()
    {
            echo
            '
            <form method="POST" action="login.php">
                <p>
                    <label>Username</label>
                    <input name="username" type="text" size="30" />
                    <label>Password</label>
                    <input name="password" type="password" size="30" /> 
                    <br />
                    <a href="create_user.php">Create User</a>&nbsp;&nbsp;<a href="Forgot Password"> Forgot Password</a>
                    <br />
                    <br />
                    <input class="button" type="submit" />
                </p>
            </form>
            <br />
            ';

    }

    function printFailedLogin($loginAttempts)
    {
            
        $form =    '
            <form method="POST" action="login.php">
                <p>
                    Invalid Login.<br/>
                    <label>Username</label>
                    <input name="username" type="text" size="30" />
                    <label>Password</label>
                    <input name="password" type="password" size="30" /> 
                    <br />
                    <a href="createuser.php">Create User</a>&nbsp;&nbsp;<a href="Forgot Password"> Forgot Password</a>
                    <br />
                    <br />';
        
        if($loginAttempts > 5)
        {
            $form .= recaptcha_get_html($GLOBALS['publickey'], $error);
            $form .= "</br></br>";
        }
        
        $form .= '<input class="button" type="submit" />
                </p>
            </form>
            <br />
            ';
        
        echo $form;
    }

    function printRightBar()
    {
            echo 
            '
            <h1>More Text</h1>
            <p>ModelHorseShow is a new and upcoming site that you can use to manage your horses!</p>    
            ';

    }
    
    function printRightAdBar()
    {
        $form = '
                <div class="side_column_w200"> ';       

        $form2 .= '      <div class="box">
 
                   <h3>Popular Topics</h3>

                        <div class="news_section">
                            <div class="news_title"><a href="#">Lorem ipsum dolor sit amet consectetur </a></div>
                            <p>Maecenas tellus erat, dictum vel semper a, dapibus ac elit. Nunc rutrum pretium porta.</p>
                        </div>

                        <div class="news_section">
                            <div class="news_title"><a href="#">Aenean feugiat mattis est nec egestas</a></div>
                            <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
                        </div>

                    </div>';

       $form .= '    <div class="box">
                        <h3>Wise Words</h3>
                          '. SelectRandomQuoteFormatted().'
                        <div class="cleaner"></div>
                    </div>


                  </div> <!-- end of right side column -->
            ';
        
        echo $form;
    }

    function printUserProfileSideBar()
    {       
            $menu .= returnShowManagerSideBar();
                
            $menu .=
            '   <h5>Horseshow</h5>
                <ul class="side_menu">
                    <li><a href="manage_horseshow.php?link=myshows">My HorseShows</a></li>
                    <li><a href="manage_horseshow.php?link=registershow">Register for a Show</a></li>
                    <li><a href="manage_horseshow.php?link=findshow">Find a Show</a></li>
                    <li><a href="manage_horseshow.php?link=showhistory">My Show History</a></li>
                </ul>
                <h5>Models</h5>
                <ul class="side_menu">
                    <li><a href="#">My Models</a></li>
                    <li><a href="#">Add a Model</a></li>
                    <li><a href="#">Remove a Model</a></li>
                    <li><a href="#">Print a List</a></li>
                </ul>

                <h5>Help</h5>
                <ul class="side_menu">
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="#">Submit an Issue</a></li>
                    <li><a href="#">Check Issue Status</a></li>
                </ul>
            ';
            
            echo $menu;
    }
    
    function printManageProfileSideBar()
    {

            echo
            '               
                <h5>Profile</h5>
                <ul class="side_menu">
                    <li><a href="manage_profile.php?link=nameinfo">Name Info</a></li>
                    <li><a href="manage_profile.php?link=addressinfo">Address Info</a></li>
                    <li><a href="manage_profile.php?link=contactinfo">Contact Info</a></li>
                </ul>
                <h5>Login</h5>
                <ul class="side_menu">
                    <li><a href="manage_login.php?link=changepassword">Change Password</a></li>
                    <li><a href="manage_login.php?link=inactivateaccount">Inactivate Account</a></li>
                </ul>
                
                
            ';
    }

    function printAdminProfileSideBar()
    {
            echo
            '
                <h5>Admin</h5>
                <ul class="side_menu">
                    <li><a href="manage_website.php">Manage Website</a></li>
                    <li><a href="#TemplateInfo">Manage Users</a></li>
                </ul>

            ';

            printUserProfileSideBar();
    }
    
    function printManageWebsiteSideBar()
    {
            echo
            '
                <h5>Website</h5>
                <ul class="side_menu">
                    <li><a href="#">Change Motto</a></li>
                </ul>
                
                <h5>Blog</h5>
                <ul class="side_menu">
                    <li><a href="manage_website.php?link=updateblog">Add a Post</a></li>
                    <li><a href="manage_website.php?link=updatemotto">View Previous Posts</a></li>
                </ul>
                
                <h5>Article</h5>
                <ul class="side_menu">
                    <li><a href="#">Add an Article</a></li>
                    <li><a href="#">View Previous Articles</a></li>
                </ul>
                
                <h5>Quote</h5>
                <ul class="side_menu">
                    <li><a href="#">Add a Quote</a></li>
                    <li><a href="#">View Quotes</a></li>
                </ul>
            ';
    }

    function printLogout()
    {
        $form = '<a href="logout.php" class="logout">logout - '.FormatStringPascalCase($_SESSION['FIRST_NAME']).'</a>';
    
        return $form;
    }
    
    
    function printShowManagerSideBar($arrayHorseShowId)
    {
        $menu = '<h5>horseshow</h5>
                  <ul class="side_menu">';
        
        while ($row = mysqli_fetch_array($arrayHorseShowId, MYSQL_ASSOC))
        {
            $horseshowid = $row['HORSESHOW_ID'];
            $horseshowname = FormatStringSplitPascalCase($row['HORSESHOW_NAME'], ' ');
            
            $menu .= '<li><a href="show_manager.php?horseshow='.$horseshowid.'">'.$horseshowname.'</a></li>';
        }
                    
        $menu .='</ul>';
        
        echo $menu;
    }
    
    function printShowManagerActiveShowsSideBar($arrayShows)
    {
        $menu = '<h5>active shows</h5>
                  <ul class="side_menu">';
        
        if(!$arrayShows)
        {
            $menu .= '<li>Unable to select Active Shows</li>';
        }
        else
        {
            while ($row = mysqli_fetch_array($arrayShows, MYSQL_ASSOC))
            {
                $showId = $row['SHOW_ID'];
                $showName = FormatStringSplitPascalCase($row['SHOW_TITLE'], ' ');

                $menu .= '<li><a href="manage_show.php?showid='.$showId.'">'.$showName.'</a></li>';
            }

            $menu .='</ul>';
        }
        
        echo $menu;
    }
    
    function returnShowManagerSideBar()
    {
        $arrayHorseShowId = SelectUserHorseShows($_SESSION['USER_ID']);
         
        $menu = '<h5>show manager</h5>
                  <ul class="side_menu">';
        
        while ($row = mysqli_fetch_array($arrayHorseShowId, MYSQL_ASSOC))
        {
            $horseshowid = $row['HORSESHOW_ID'];
            $horseshowname = FormatStringSplitPascalCase($row['HORSESHOW_NAME'], ' ');
            
            $menu .= '<li><a href="show_manager.php?horseshow='.$horseshowid.'">'.$horseshowname.'</a></li>';
        }
                    
        $menu .='</ul>';
        
        return $menu;
    }
    
    function printShowManagerSubmenu($horseshowid)
    {
        echo ' <ul>
                <li><a href="show_manager.php?link=editinfo&horseshow='.$horseshowid.'">Edit HS Info</a></li>
                <li><a href="show_manager.php?link=activeshows&horseshow='.$horseshowid.'">Active Shows</a></li>
                <li><a href="show_manager.php?link=createshow&horseshow='.$horseshowid.'">Open a Show</a></li>
                <li><a href="show_manager.php?link=showhistory&horseshow='.$horseshowid.'">History</a></li>
                <li><a href="show_manager.php?link=showdocuments&horseshow='.$horseshowid.'">Documents</a></li>
                <li><a href="show_search.php">Search</a></li>
               </ul>
             ';
    }
    
    function printShowResultsSubmenu($showid)
    {
        echo ' <ul>
                <li><a href="show_results.php?showid='.$showid.'">Dashboard</a></li>
                <li><a href="show_results.php?showid='.$showid.'&link=view">View</a></li>
                <li><a href="show_results.php?showid='.$showid.'&link=process">Process</a></li>
                <li><a href="show_results.php?showid='.$showid.'&link=nan">NAN</a></li>
               </ul>
             ';
    }
    
    function printShowSubmenu($showid)
    {
        echo ' <ul>
                <li><a href="manage_show.php?link=editinfo&showid='.$showid.'">Edit Info</a></li>
                <li><a href="manage_show.php?link=people&showid='.$showid.'">Registered People</a></li>
                <li><a href="manage_show.php?link=closeshow&showid='.$showid.'">Close</a></li>
                <li><a href="manage_show.php?link=showdocuments&showid='.$showid.'">Documents</a></li>
                <li><a href="manage_show.php?link=uploadform&showid='.$showid.'">Forms</a></li>
                <li><a href = "manage_show.php?link=bfchangeform&showid='.$showid.'">Change Form</a></li>
                <li><a href="show_results.php?showid='.$showid.'">Results</a></li>
               </ul>
             ';
    }
    
    function printShowPersonSubmenu($showid, $personid)
    {
        echo ' <ul>
                <li><a href="show_person.php?link=dashboard&showid='.$showid.'&personid='.$personid.'">Dashboard</a></li>
                <li><a href="show_person.php?link=editshowpers&showid='.$showid.'&personid='.$personid.'">Edit Person Info</a></li>
                <li><a href="show_person.php?link=showpershorselist&showid='.$showid.'&personid='.$personid.'">Horse List</a></li>
                <li><a href="show_person.php?link=showpersdocuments&showid='.$showid.'&personid='.$personid.'">Documents</a></li>
               </ul>
             ';
    }
    
    function printShowHorseSubmenu($horseid)
    {
        echo ' <ul>
                <li><a href="show_horse.php?link=dashboard&horseid='.$horseid.'">Dashboard</a></li>
                <li><a href="show_horse.php?link=edithorseinfo&horseid='.$horseid.'">Edit Horse Info</a></li>
                <li><a href="show_horse.php?link=results&horseid='.$horseid.'">Results</a></li>
                <li><a href="show_horse.php?link=horsedocuments&horseid='.$horseid.'">Documents</a></li>
                <li><a href="show_search.php">Search</a></li>
               </ul>
             ';
    }
?>
