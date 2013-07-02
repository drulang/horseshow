<?php
include_once 'format_func.php';
include_once 'db_connect.php';

function CheckLogin($username, $password) {
    if ($username != '' && $password != '') {
       
        $query = "select u.LOGIN_USERNAME as \"LOGIN_NAME\", ur.NAME as \"ROLE_NAME\", u.LOGIN_COUNT as \"LOGIN_COUNT\", ur.ID as \"USER_ROLE_ID\",
                   up.FIRST_NAME as \"FIRST_NAME\", up.LAST_NAME as \"LAST_NAME\", up.MIDDLE_NAME as \"MIDDLE_NAME\", up.NICKNAME as \"NICK_NAME\",
                   DATE(u.INACTIVIE_DATE) as \"INACTIVE_DATE\", u.ACTIVATION_KEY as \"ACTIVATION_KEY\", u.ID as \"USER_ID\"
                   from frithi_HORSESHOW.USER u 
                    inner join frithi_HORSESHOW.USER_PROFILE up on u.ID = up.USER_ID 
                    inner join frithi_HORSESHOW.USER_ROLE ur on ur.ID = u.USER_ROLE_ID 
                     where u.LOGIN_USERNAME = '$username' and u.LOGIN_PASSWORD = '$password'";
        $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
        
        // $row = mysql_fetch_row($result);
        $row = mysqli_fetch_array($result, MYSQL_BOTH);

        if ($row["LOGIN_NAME"] == strtoupper($username)) {
            session_start();

            $_SESSION['LOGIN_NAME'] = $row["LOGIN_NAME"];
            $_SESSION['ROLE_NAME'] = $row["ROLE_NAME"];
            $_SESSION['LOGIN_COUNT'] = $row["LOGIN_COUNT"];
            $_SESSION['USER_ROLE_ID'] = $row["USER_ROLE_ID"];

            $_SESSION['FIRST_NAME'] =  FormatStringPascalCase($row["FIRST_NAME"]);
            $_SESSION['MIDDLE_NAME'] = FormatStringPascalCase($row["MIDDLE_NAME"]);
            $_SESSION['LAST_NAME'] = FormatStringPascalCase($row["LAST_NAME"]);
            $_SESSION['NICK_NAME'] = FormatStringPascalCase($row["NICK_NAME"]);

            $_SESSION['INACTIVIE_DATE'] = $row["INACTIVE_DATE"];
            $_SESSION['ACTIVATION_KEY'] = $row["ACTIVATION_KEY"];
            $_SESSION['USER_ID'] = $row["USER_ID"];

            //Check to see if person is a show manager


            if(CheckIfShowManager($_SESSION['USER_ID']) == TRUE)
            {
                $_SESSION['IS_SHOW_MANAGER'] = TRUE;
            }
            else
            {
                $_SESSION['IS_SHOW_MANAGER'] = FALSE;
            }

            return true;

            #Next grab UserSetting
            } 
            else
            {
                #User is not valid.  Need to print error message
                return false;
            }
        }
        else
        {
            return false;
        }
    
}

function CheckActivationKey($username, $key)
{
    if($username == '' || $key == '')
    {
        return false;
    }
    
    $query = "SELECT LOGIN_USERNAME, ACTIVATION_KEY from frithi_HORSESHOW.USER where LOGIN_USERNAME = '".$username."' and ACTIVATION_KEY = '".$key."'";

    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if (!$result) 
    {
        return false;
    } 
    else 
    {
        $row = mysqli_fetch_row($result);


        if ($row[0] == $username && $row[1] == $key) 
        {    
            return true;
        } 
        else
        {
            return false;
        }
    }
}

function ActivateAccount($username, $key)
{
    if($username == '' || $key == '')
    {
        return false;
    }
    
    $query = "UPDATE frithi_HORSESHOW.USER set ACTIVATION_KEY = null, INACTIVIE_DATE = null where LOGIN_USERNAME = '$username' and ACTIVATION_KEY = '$key'";
    
    echo "<br/>$query";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);

    echo "<br/><br/>RESULT $row[0]";
}

function UserExists($username)
{
    $username = strtoupper($username);
    
    if($username == '')
        return true; #return true if it's empty because it cannot exist
    
    $query = "SELECT LOGIN_USERNAME FROM frithi_HORSESHOW.USER u where u.LOGIN_USERNAME = '$username'";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if(!$result)
    {
        return false;
    }
    else
    {
        $row = mysqli_fetch_row($result);

        if ($row[0] == $username) 
        {    
            return true;
        } 
        else
        {
            return false;
        }
    }
    
}

function CreateMinimalUser($username, $password, $primaryEmail, $firstname)
{
    $username = strtoupper($username);
    $password = strtoupper(sha1($password));
    $primaryEmail = strtoupper($primaryEmail);
    $activationKey = strtoupper(sha1(uniqid()));
    
    //Create Query
    $query = "insert into frithi_HORSESHOW.USER values (0,'$username','$password','$primaryEmail',null,UTC_TIMESTAMP(),null,null,0,0,null,'$activationKey',500, UTC_TIMESTAMP())";
    if(!mysqli_query($GLOBALS['dbh_horseshow'], $query))
    {
        echo "Unable to Create User";
    }
    
    
    $userid = SelectUserID($username, $password);
    
    $queryProfile = "insert into frithi_HORSESHOW.USER_PROFILE (ID, USER_ID, FIRST_NAME, ADD_DATE) values (0,$userid, '$firstname', UTC_TIMESTAMP())";
    
    
    if(!mysqli_query($GLOBALS['dbh_horseshow'], $queryProfile))
    {
        echo "Unable to create user";
    }
    
}

function SelectUserID($username, $password)
{
    $query = "select id from frithi_HORSESHOW.USER u where u.LOGIN_USERNAME = '$username' and u.LOGIN_PASSWORD='$password'";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if(!$result)
    {
        echo "Unable to Select UserID for username: $username";
    }
    
    $row = mysqli_fetch_row($result);
    
    return $row[0];
}

function UpdateUserProfile($userid, $firstname, $middlename, $lastname, $nickname)
{
    $query = "update frithi_HORSESHOW.USER_PROFILE up set up.FIRST_NAME = '$firstname', up.MIDDLE_NAME = '$middlename', up.LAST_NAME = '$lastname', up.NICKNAME  = '$nickname' where up.USER_ID = $userid";
    
    if(!mysqli_query($GLOBALS['dbh_horseshow'], $query))
    {
        echo "Unable to Update User Profile";
    }
    
}

function RefreshSession($userid)
{
    session_destroy();
    $query = "select u.LOGIN_USERNAME as \"LOGIN_NAME\", ur.NAME as \"ROLE_NAME\", u.LOGIN_COUNT as \"LOGIN_COUNT\", ur.ID as \"USER_ROLE_ID\",
                up.FIRST_NAME as \"FIRST_NAME\", up.LAST_NAME as \"LAST_NAME\", up.MIDDLE_NAME as \"MIDDLE_NAME\", up.NICKNAME as \"NICK_NAME\",
                DATE(u.INACTIVIE_DATE) as \"INACTIVE_DATE\", u.ACTIVATION_KEY as \"ACTIVATION_KEY\", u.ID as \"USER_ID\"
                from frithi_HORSESHOW.USER u 
                inner join frithi_HORSESHOW.USER_PROFILE up on u.ID = up.USER_ID 
                inner join frithi_HORSESHOW.USER_ROLE ur on ur.ID = u.USER_ROLE_ID 
                    where u.ID = $userid";
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);

    $row = mysqli_fetch_array($result, MYSQL_BOTH);

    $_SESSION['LOGIN_NAME'] = $row["LOGIN_NAME"];
    $_SESSION['ROLE_NAME'] = $row["ROLE_NAME"];
    $_SESSION['LOGIN_COUNT'] = $row["LOGIN_COUNT"];
    $_SESSION['USER_ROLE_ID'] = $row["USER_ROLE_ID"];

    $_SESSION['FIRST_NAME'] =  FormatStringPascalCase($row["FIRST_NAME"]);
    $_SESSION['MIDDLE_NAME'] = FormatStringPascalCase($row["MIDDLE_NAME"]);
    $_SESSION['LAST_NAME'] = FormatStringPascalCase($row["LAST_NAME"]);
    $_SESSION['NICK_NAME'] = FormatStringPascalCase($row["NICK_NAME"]);

    $_SESSION['INACTIVIE_DATE'] = $row["INACTIVE_DATE"];
    $_SESSION['ACTIVATION_KEY'] = $row["ACTIVATION_KEY"];
    $_SESSION['USER_ID'] = $row["USER_ID"];
    
    
    if(CheckIfShowManager($_SESSION['USER_ID']) == TRUE)
    {
        echo "TRUE";
        $_SESSION['IS_SHOW_MANAGER'] = TRUE;
    }
    else
    {
        echo "FALSE";
        $_SESSION['IS_SHOW_MANAGER'] = FALSE;
    }


}


function CheckIfShowManager($userid)
{

    $query = "select count(*) as COUNT from frithi_HORSESHOW.USER_has_HORSESHOW uhh where uhh.USER_ID = $userid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if(!$result)
    {
        echo "Unable to check if show manager";
        return false;
    }

    $row = mysqli_fetch_array($result, MYSQL_BOTH);
    
    $count = $row['COUNT'];
    
    
    if($count > 0)
    {
        return true;
    }
    else 
    {
        return false;
    }
        
}

function UserAuthorizedForShow($userid, $horseshowid)
{

    $query = "select count(*) as COUNT from frithi_HORSESHOW.USER_has_HORSESHOW uhh where uhh.USER_ID = $userid and uhh.HORSESHOW_ID = $horseshowid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);

    if(!$result)
    {
        return false;
    }
    
    
    $row = mysqli_fetch_array($result, MYSQL_BOTH);
    
    $count = $row['COUNT'];
    
    
    if($count > 0)
    {
        return true;
    }
    else 
    {
        return false;
    }
        
}

function LookupHorseshowId($showid)
{
    $showid = sanitizeIntegerOnly($showid);
    
    $query = "select HORSESHOW_ID from SHOWS where ID = $showid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if(!$result)
    {
        return 0;
    }
    
    $row = mysqli_fetch_row($result);
    
    return $row[0];
}

function SelectUserHorseShows($userid)
{
    $query = "select distinct uhh.HORSESHOW_ID as \"HORSESHOW_ID\", h.NAME_ONE as \"HORSESHOW_NAME\"
                from frithi_HORSESHOW.USER_has_HORSESHOW uhh 
                inner join frithi_HORSESHOW.HORSESHOW h on uhh.HORSESHOW_ID = h.ID
                where uhh.USER_ID = $userid";

    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
    
}

function SelectActiveShows($userid, $horseshowid)
{
    $query = "select distinct s.ID as \"SHOW_ID\", s.TITLE as \"SHOW_TITLE\", s.OPEN_DATE as \"SHOW_OPEN_DATE\", ss.SHORT_NAME as \"SHOW_STATUS\"
                from frithi_HORSESHOW.SHOWS s
                inner join frithi_HORSESHOW.USER_has_HORSESHOW uhh on s.HORSESHOW_ID = uhh.HORSESHOW_ID
                inner join frithi_HORSESHOW.SHOW_STATUS ss on s.SHOW_STATUS_ID = ss.ID
                where uhh.USER_ID = $userid and uhh.HORSESHOW_ID = $horseshowid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
}

function CreateShow($showstatusid, $horseshowid, $title, $opendate, $comment)
{
    $query = "insert into frithi_HORSESHOW.SHOWS 
                 values (0, $showstatusid, $horseshowid, '$title',50, 10, UTC_TIMESTAMP(), '$opendate', '', '$comment')";
    
    if(!mysqli_query($GLOBALS['dbh_horseshow'], $query) or die("Unable to Create Show"))
    {
        echo "Unable to create show";
    }
}

function SelectShowStatusTypes()
{
    $query = "select ss.ID as \"ID\", ss.SHORT_NAME as \"STATUS_NAME\" from frithi_HORSESHOW.SHOW_STATUS ss order by ss.ID";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
}

function SelectOpenShowStatusTypes()
{
    $query = "select ss.ID as \"ID\", ss.SHORT_NAME as \"STATUS_NAME\" from frithi_HORSESHOW.SHOW_STATUS ss where ss.SHORT_NAME not like '%CLOSE%' order by ss.ID";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
}

function SelectHorseShowName($horseshowid)
{
    
    $query = "select h.NAME_ONE as \"NAME_ONE\" from frithi_HORSESHOW.HORSESHOW h where h.id = $horseshowid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    $row = mysqli_fetch_row($result);
    
    return $row[0];
}

function SelectRandomQuoteFormatted()
{
    $query = "select TEXT as \"QUOTE\", AUTHOR as \"AUTHOR\" from frithi_HORSESHOW_WEBSITE.CONTENT where CONTENT_TYPE_ID = 1 order by RAND() LIMIT 1";
    
    $result = mysqli_query($GLOBALS['dbh_website'], $query);
    
    $row = mysqli_fetch_row($result);
    
    if($row)
    {
        $quote = $row[0];
        $author = $row[1];

        $formattedQuote = '<p>&quot;'.$quote.'&quot;</p>
                        <p class="align-right">- '.$author.'</p>';

        return $formattedQuote;
    }
    else
    {
        return "";
    }
}

function SelectBreed()
{
    $query = "select b.ID as \"ID\", b.LONG_NAME as \"LONG_NAME\", b.DESCRIPTION as \"DESCRIPTION\" from frithi_HORSESHOW.BREED b order by LONG_NAME";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
    
}

function SelectTopFourBlogs()
{
   $query = "select c.TITLE as \"TITLE\", c.TEXT as \"BLOG\" from frithi_HORSESHOW_WEBSITE.CONTENT c where c.CONTENT_TYPE_ID = 2 order by c.DATE_ADD DESC LIMIT 4;";
    
   $result = mysqli_query($GLOBALS['dbh_website'], $query);
     
    return $result;
}

function selectRecentArticle()
{
    $query = "select c.TITLE as \"TITLE\", c.TEXT as \"ARTICLE\" from frithi_HORSESHOW_WEBSITE.CONTENT c where c.CONTENT_TYPE_ID = 3 order by c.DATE_ADD DESC LIMIT 1;";
    
    $result = mysqli_query($GLOBALS['dbh_website'], $query);
    
    $row = mysqli_fetch_row($result);
    
    return $row;
}

function SelectRegisteredPeople($showid)
{
    $query = "  select p.FIRST_NAME as \"FIRST_NAME\", p.LAST_NAME as \"LAST_NAME\", LPAD(p.SHOW_EXHIBITOR_ID, 3, '0') as \"EX_ID\", p.COMMENT as \"COMMENT\", srs.NAME as \"STATUS\", p.ID as \"ID\"
                    from frithi_HORSESHOW.PERSON p
                        inner join frithi_HORSESHOW.SHOW_REGISTRATION_LINK srl on p.ID = srl.PERSON_ID
                        inner join frithi_HORSESHOW.SHOWS s on srl.SHOW_ID = s.ID
                        inner join frithi_HORSESHOW.SHOW_REGISTRATION_STATUS srs on srl.SHOW_REGISTRATION_STATUS_ID = srs.ID
                    where s.ID = $showid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
    
}

function SelectUserInformation($userid)
{
    $queryUser = "select * from USER u 
                     inner join USER_PROFILE up on u.ID = up.USER_ID where u.ID = $userid";
    
    $resultUser = mysqli_query($GLOBALS['dbh_horseshow'], $queryUser);
    
    $rowUser = mysqli_fetch_array($resultUser, MYSQLI_ASSOC);
    
    if($rowUser)
    {
        $firstName = $rowUser['FIRST_NAME'];
        $middleName = $rowUser['MIDDLE_NAME'];
        $lastName = $rowUser['LAST_NAME'];
        $emailPri = $rowUser['EMAIL_PRI'];
    }
    
    $array = array(
        "FirstName" => $firstName,
        "MiddleName" => $middleName,
        "LastName" => $lastName,
        "EmailPri" => $emailPri,
    );
    
    return $array;
}

function SelectShowPersonHorses($personid)
{
    $query = "select pm.ID, pm.SHOW_MODEL_ID, pm.SHOW_MODEL_NAME, pm.SHOW_MODEL_BREED from PERSON_MODEL pm where pm.PERSON_ID = $personid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
    
}

function SelectPersonInformation($personid)
{
    $query = "select p.SHOW_EXHIBITOR_ID, p.FIRST_NAME, p.LAST_NAME, p.COMMENT from PERSON p where p.ID = $personid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
}

function SelectHorseInformation($horseid)
{
    $query = "select PERSON_ID, SHOW_MODEL_ID, SHOW_MODEL_NAME, SHOW_MODEL_BREED, SHOW_MODEL_GENDER 
                from frithi_HORSESHOW.PERSON_MODEL
                where ID = '$horseid'";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;    
}

function ShowStats($showid)
{
    //
    // Information from SHOWS table
    //
    $showid = sanitizeIntegerOnly($showid);
    
    $showQuery = "SELECT s.ID, s.TITLE, s.MAX_REGISTER, s.ADD_DATE, s.CLOSE_DATE, s.OPEN_DATE, s.COMMENT, ssi.SHORT_NAME, h.NAME_ONE from frithi_HORSESHOW.SHOWS s
                        inner join frithi_HORSESHOW.SHOW_STATUS ssi on s.SHOW_STATUS_ID = ssi.ID
                        inner join frithi_HORSESHOW.HORSESHOW h on s.HORSESHOW_ID = h.ID
                        where s.ID = $showid";
   
    
    $showResult = mysqli_query($GLOBALS["dbh_horseshow"], $showQuery);
    
    $showRow = mysqli_fetch_array($showResult, MYSQLI_ASSOC);
    
    if($showRow)
    {
        $id = $showRow["ID"];
        $title = $showRow["TITLE"];
        $maxRegister = $showRow["MAX_REGISTER"];
        $addDate =$showRow["ADD_DATE"];
        $closeDate = $showRow["CLOSE_DATE"];
        $openDate = $showRow["OPEN_DATE"];
        $comment = $showRow["COMMENT"];
        $shortName = $showRow["SHORT_NAME"];
        $horseShowName = $showRow["NAME_ONE"];
    }
    
    //
    // Numbers
    //
    
    //PERSON
    $peopleCountQuery = "select count(*)
                            from  frithi_HORSESHOW.SHOW_REGISTRATION_LINK srl
                            inner join frithi_HORSESHOW.SHOWS s on srl.SHOW_ID = s.ID
                            where s.ID = $showid";
    $peopleCountResult = mysqli_query($GLOBALS["dbh_horseshow"], $peopleCountQuery);
    $peopleCountRow = mysqli_fetch_row($peopleCountResult);
    
    if($peopleCountRow)
    {
        $peopleCount = $peopleCountRow[0];
    }
    else
    {
        $peopleCount = 0;
    }
    
    //MODEL
    $modelCountQuery = "select count(*) 
                            from frithi_HORSESHOW.PERSON_MODEL pm 
                            where pm.PERSON_ID in
                                (SELECT PERSON_ID
                                    from frithi_HORSESHOW.SHOW_REGISTRATION_LINK srl
                                    inner join frithi_HORSESHOW.SHOWS s on srl.SHOW_ID = s.ID 
                                    where s.ID = $showid
                                )";

    $modelCountResult = mysqli_query($GLOBALS["dbh_horseshow"], $modelCountQuery);
    
    $modelCountRow = mysqli_fetch_row($modelCountResult);
    
    if($modelCountRow)
    {
        $modelCount = $modelCountRow[0];
    }
    else
    {
        $modelCount = 0;
    }
    
    //Longest Name
    $longestNameQuery = "select pm.ID, LENGTH(pm.SHOW_MODEL_NAME)
                            from frithi_HORSESHOW.PERSON_MODEL pm 
                            where pm.PERSON_ID in 
                                (SELECT PERSON_ID from frithi_HORSESHOW.SHOW_REGISTRATION_LINK srl inner join frithi_HORSESHOW.SHOWS s on srl.SHOW_ID = s.ID where s.ID = 1)
                            order by 2 desc LIMIT 3";
    $longestNameResult = mysqli_query($GLOBALS["dbh_horseshow"], $longestNameQuery);
    
    $count = 0;
    while($row = mysqli_fetch_row($longestNameResult))
    {
        if($count == 0)
        {
            $firstLongestName = $row[0];
        }
        elseif($count == 1)
        {
            $secondLongestName = $row[0];
        }
        else 
        {
            $thirdLongestName = $row[0];
        }
        $count++;
    }
    
    //Shortest Name
    $shortestNameQuery = "select pm.ID, LENGTH(pm.SHOW_MODEL_NAME)
                            from frithi_HORSESHOW.PERSON_MODEL pm 
                            where pm.PERSON_ID in (SELECT PERSON_ID from frithi_HORSESHOW.SHOW_REGISTRATION_LINK srl inner join frithi_HORSESHOW.SHOWS s on srl.SHOW_ID = s.ID where s.ID = 1)
                            and LENGTH(TRIM(pm.SHOW_MODEL_NAME)) > 0
                            order by 2 LIMIT 3";

    $shortestNameResult = mysqli_query($GLOBALS["dbh_horseshow"], $shortestNameQuery);
    
    $count = 0;
    while($row = mysqli_fetch_row($shortestNameResult))
    {
           if($count == 0)
        {
            $firstShortestName = $row[0];
        }
        elseif($count == 1)
        {
            $secondShortestName = $row[0];
        }
        else 
        {
            $thirdShortestName = $row[0];
        }
        $count++;
        
    }
    
    // States
    $stateQuery = "select STATE, count(STATE) from PERSON group by STATE order by 2 desc limit 3";
    
    $stateQueryResult = mysqli_query($GLOBALS['dbh_horseshow'], $stateQuery);
    
    $count = 0;
    while($row = mysqli_fetch_row($stateQueryResult))
    {
        if($count == 0)
        {
            $firstState = $row[0];
        }
        elseif($count == 1)
        {
            $secondState = $row[0];
        }
        else
        {
            $thirdState = $row[0];
        }
        $count++;
    }

    
    $array = array(
        "ID" => $id,
        "Title" => $title,
        "MaxRegister" => $maxRegister,
        "AddDate" => $addDate,
        "CloseDate" => $closeDate,
        "OpenDate" => $openDate,
        "Comment" => $comment,
        "StatusName" => $shortName,
        "HorseshowName" => $horseShowName,
        "PeopleCount" => $peopleCount,
        "ModelCount" => $modelCount,
        "FirstLongestName" => $firstLongestName,
        "SecondLongestName" => $secondLongestName,
        "ThirdLongestName" => $thirdLongestName,
        "FirstShortestName" => $firstShortestName,
        "SecondShortestName" => $secondShortestName,
        "ThirdShortestName" => $thirdShortestName,
        "FirstState" => $firstState,
        "SecondState" => $secondState,
        "ThirdState" => $thirdState,
    );
    
    return $array;
}

function ModelStats($horseid)
{
    $horseid = sanitizeIntegerOnly($horseid);
    
    $generalHorseQuery = "select * from frithi_HORSESHOW.PERSON_MODEL pm where pm.ID = $horseid";
    
    $generalHorseResult = mysqli_query($GLOBALS["dbh_horseshow"], $generalHorseQuery);
    
    $generalHorseRow = mysqli_fetch_array($generalHorseResult, MYSQLI_ASSOC);
    
    if($generalHorseRow)
    {
        
        $modelId = $generalHorseRow["ID"];
        $personId = $generalHorseRow["PERSON_ID"];
        $showModelId = $generalHorseRow["SHOW_MODEL_ID"];
        $showModelName = $generalHorseRow["SHOW_MODEL_NAME"];
        $showModelBreed = $generalHorseRow["SHOW_MODEL_BREED"];
        $showModelGender = $generalHorseRow["SHOW_MODEL_GENDER"];
        $userField1 = $generalHorseRow["USER_FIELD_1"];
    }
    
    $array = array(
        "ModelId" => $modelId,
        "PersonId" => $personId,
        "ShowModelId" => $showModelId,
        "ShowModelName" => $showModelName,
        "ShowModelBreed" => $showModelBreed,
        "ShowModelGender" => $showModelGender,
        "UserField1" => $userField1,
 
    );
    
    return $array;
}

function PersonStats($personid)
{
    $personid = sanitizeIntegerOnly($personid);
    
    $personQuery = "SELECT * from PERSON where ID = $personid";
    
    $personResult = mysqli_query($GLOBALS['dbh_horseshow'], $personQuery);
    
    $row = mysqli_fetch_array($personResult, MYSQLI_ASSOC);
    
    if($row)
    {
        $id = $row["ID"];
        $showExhibitorId = $row["SHOW_EXHIBITOR_ID"];
        $firstName = $row["FIRST_NAME"];
        $lastName = $row["LAST_NAME"];
        $nickName = $row["NICKNAME"];
        $addr1 = $row["ADDRESS_LINE_1"];
        $addr2 = $row["ADDRESS_LINE_2"];
        $addr3 = $row["ADDRESS_LINE_3"];
        $city = $row["CITY"];
        $state = $row["STATE"];
        $zip = $row["ZIP"];
        $email = $row["EMAIL"];
        $phoneCell = $row["PHONE_NBR_CELL"];
        $comment = $row["COMMENT"];
        
    }
    
    $modelCountQuery = "select count(*) from frithi_HORSESHOW.PERSON_MODEL pm where pm.PERSON_ID = $personid";
    $modelCountResult = mysqli_query(($GLOBALS["dbh_horseshow"]), $modelCountQuery);
    $modelCountRow = mysqli_fetch_row($modelCountResult);
    
    if($modelCountRow)
    {
        $modelCount = $modelCountRow[0];
    }
    else
    {
        $modelCount = 0;
    }
    
    $array = array(
        "ID" => $id,
        "ShowExhibitorId" => $showExhibitorId,
        "FirstName"  => $firstName,
        "LastName" => $lastName,
        "NickName" => $nickName,
        "Addr1" => $addr1,
        "Addr2" => $addr2,
        "Addr3" => $addr3,
        "City" => $city,
        "State" => $state,
        "Zip" => $zip,
        "Email" => $email,
        "PhoneCell" => $phoneCell,
        "Comment" => $comment,
        "ModelCount" => $modelCount,
    );
    
    return $array;
}

function HorseShowStats($horseshowid)
{
    $horseshowid = sanitizeIntegerOnly($horseshowid);
    
    //
    //  Person Count
    //
    
    $personCountQuery = " 
                            select count(*)
                            from frithi_HORSESHOW.SHOW_REGISTRATION_LINK srl 
                            inner join frithi_HORSESHOW.SHOWS s on srl.SHOW_ID = s.ID
                            where s.HORSESHOW_ID = $horseshowid
                        ";
    
    
    $personCountResult = mysqli_query($GLOBALS['dbh_horseshow'], $personCountQuery);
    
    $personCountRow = mysqli_fetch_row($personCountResult);
    
    if($personCountRow)
    {
        $personCount = $personCountRow[0];
    }
    else
    {
        $personCount = 0;
    }
    
    //
    // Model Count
    //
    $modelCountQuery = "select count(*)
                            from frithi_HORSESHOW.PERSON_MODEL sm where sm.PERSON_ID in 
                            (select PERSON_ID from frithi_HORSESHOW.SHOW_REGISTRATION_LINK srl 
                            inner join frithi_HORSESHOW.SHOWS s on srl.SHOW_ID = s.ID
                                where s.HORSESHOW_ID = $horseshowid)";
    
    $modelCountResult = mysqli_query($GLOBALS['dbh_horseshow'], $modelCountQuery);
    
    $modelCountRow = mysqli_fetch_row($modelCountResult);
    
    if($modelCountRow)
    {
        $modelCount = $modelCountRow[0];
    }
    else
    {
        $modelCount = 0;
    }
    
    //
    // General Information
    //
    $genearlQuery = "select * from frithi_HORSESHOW.HORSESHOW h where h.ID = $horseshowid";
    
    $genearlResult = mysqli_query($GLOBALS['dbh_horseshow'], $genearlQuery);
    
    $generalRow = mysqli_fetch_array($genearlResult, MYSQLI_ASSOC);
    
    if($generalRow)
    {
        $id = $generalRow["ID"];
        $nameOne = $generalRow["NAME_ONE"];
        $nameTwo = $generalRow["NAME_TWO"];
        $addr1 = $genearlRow["ADDRESS_LINE_1"];
        $addr2 = $generalRow["ADDRESS_LINE_2"];
        $addr3 = $genearlRow["ADDRESS_LINE_3"];
        $website = $genearlRow["WEBSITE"];
        $email_primary = $generalRow["EMAIL_PRIMARY"];
        $email_secondary = $generalRow["EMAIL_SECONDARY"];
        $phoneNumberPrimary = $generalRow["PHONE_NBR_PRIMARY"];
        $phoneNumberSecondary = $generalRow["PHONE_NBR_SECONDARY"];
        $rating = $genearlRow["RATING"];
        $nahmsa = $generalRow["NAHMSA_QUALIFY"];
        
    }
    

    //
    // Return Results
    //
    $array = array(
                    "PersonCount" => $personCount,
                    "ModelCount" => $modelCount,
                    "HorseshowId" => $id,
                    "NameOne" => $nameOne,
                    "NameTwo" => $nameTwo,
                    "Addr1" => $addr1,
                    "Addr2" => $addr2,
                    "Addr3" => $addr3,
                    "Website" => $website,
                    "EmailPrimary" => $email_primary,
                    "EmailSecondary" => $email_secondary,
                    "PhonePrimary" => $phoneNumberPrimary,
                    "PhoneSecondary" => $phoneNumberSecondary,
                    "Rating" => $rating,
                    "Nahmsa" => $nahmsa,
                   );

    
    return $array;
}

function searchExhibitor($criteria)
{
    if(is_numeric($criteria))
    {
              $query = "select p.ID, srl.SHOW_ID, SHOW_EXHIBITOR_ID, FIRST_NAME, LAST_NAME, EMAIL
                        from frithi_HORSESHOW.PERSON p 
                        inner join frithi_HORSESHOW.SHOW_REGISTRATION_LINK srl on p.ID = srl.PERSON_ID
                        where p.SHOW_EXHIBITOR_ID like '%$criteria%'
                        or p.ZIP like '%$criteria%' order by ID";
    }
    else
    {
        $query = "select p.ID, srl.SHOW_ID, SHOW_EXHIBITOR_ID, FIRST_NAME, LAST_NAME, EMAIL
                    from frithi_HORSESHOW.PERSON p 
                    inner join frithi_HORSESHOW.SHOW_REGISTRATION_LINK srl on p.ID = srl.PERSON_ID
                    where p.SHOW_EXHIBITOR_ID like '%$criteria%'
                    or p.FIRST_NAME like '%$criteria%'
                    or p.LAST_NAME like '%$criteria%'
                    or p.MIDDLE_NAME like '%$criteria%'
                    or p.ADDRESS_LINE_1 like '%$criteria%'
                    or p.CITY like '%$criteria%'
                    or p.STATE like '%$criteria%'
                    or p.ZIP like '%$criteria%'
                    or p.EMAIL like '%$criteria%'
                    or p.PHONE_NBR_CELL like '%$criteria%' order by ID";
    }
  
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
}

function searchModel($criteria)
{
    if(is_numeric($criteria))
    {
        #$query = "select ID, SHOW_MODEL_ID, SHOW_MODEL_NAME, SHOW_MODEL_BREED from frithi_HORSESHOW.PERSON_MODEL pm
        #            where SHOW_MODEL_ID like '%$criteria%' order by SHOW_MODEL_ID
        #         ";
        
        $query = "select pm.ID, SHOW_MODEL_ID, SHOW_MODEL_NAME, concat(p.FIRST_NAME, ' ', p.LAST_NAME) as \"PERSON_NAME\", p.NICKNAME from frithi_HORSESHOW.PERSON_MODEL pm
                    inner join frithi_HORSESHOW.PERSON p on pm.PERSON_ID = p.ID
                    where SHOW_MODEL_ID like '%$criteria%' order by SHOW_MODEL_ID";
    }
    else
    {
        $query = "select ID, SHOW_MODEL_ID, SHOW_MODEL_NAME, SHOW_MODEL_BREED from frithi_HORSESHOW.PERSON_MODEL pm
            where SHOW_MODEL_ID like '%$criteria%'
                or SHOW_MODEL_NAME like '%$criteria%'
                or SHOW_MODEL_BREED like '%$criteria%'
                or SHOW_MODEL_GENDER like '%$criteria%'
                or USER_FIELD_1 like '%$criteria%' order by SHOW_MODEL_ID
            ";
    }
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
}

function ModelNameLookup($modelid)
{
    $modelid = sanitizeIntegerOnly($modelid);
    
    $query = "select SHOW_MODEL_NAME from PERSON_MODEL where ID = $modelid";
    
    $result = mysqli_query($GLOBALS["dbh_horseshow"], $query);
    
    $row = mysqli_fetch_row($result);
    
    return $row[0];
}

function Bf_ModelUpdate($personid, $showModelId, $showModelName, $showModelBreed, $showModelGender, $showModelDivision)
{
     $personid = sanitizeIntegerOnly($personid);
     $showModelId = sanitizeIntegerOnly($showModelId);
     $showModelName = sanitizeAlphaNumericSpaceOnly($showModelName);
     $showModelBreed = sanitizeAlphaNumericSpaceOnly($showModelBreed);
     $showModelGender = sanitizeAlphaNumericSpaceOnly($showModelGender);
     $showModelDivision = sanitizeAlphaNumericSpaceOnly($showModelDivision);
     
     if(!BfModelExists($showModelId))
     {
         return false;
     }
     
     //Determine query
     if($showModelName == "")
     {
         return false;
     }
     
     $query = "update frithi_HORSESHOW.PERSON_MODEL set SHOW_MODEL_NAME='$showModelName'";
     
     if($showModelBreed != "")
     {
         $query .= ", SHOW_MODEL_BREED = '$showModelBreed'";
     }
     
     if($showModelGender != "")
     {
         $query .= ", SHOW_MODEL_GENDER = '$showModelGender'";
     }
     
     if($showModelDivision != "")
     {
         $query .= ", USER_FIELD_1='$showModelDivision'";
     }
     
     $query .= "where SHOW_MODEL_ID = '$showModelId' and PERSON_ID = $personid";
     
     $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
     
     if($result)
     {
         return true;
     }
     else
     {
         return false;
     }
}

function Bf_ModelAdd($personid, $showModelId, $showModelName, $showModelBreed, $showModelGender, $showModelDivision)
{
     $personid = sanitizeIntegerOnly($personid);
     $showModelId = sanitizeIntegerOnly($showModelId);
     $showModelName = sanitizeAlphaNumericSpaceOnly($showModelName);
     $showModelBreed = sanitizeAlphaNumericSpaceOnly($showModelBreed);
     $showModelGender = sanitizeAlphaNumericSpaceOnly($showModelGender);
     $showModelDivision = sanitizeAlphaNumericSpaceOnly($showModelDivision);
     
     if(BfModelExists($showModelId))
     {
         return false;
     }
     
     
     $query = 
       "insert into frithi_HORSESHOW.PERSON_MODEL(ID, PERSON_ID, SHOW_MODEL_ID, SHOW_MODEL_NAME, SHOW_MODEL_BREED, SHOW_MODEL_GENDER, USER_FIELD_1, TIMESTAMP)
           select 0, $personid, '$showModelId', '$showModelName', '$showModelBreed', '$showModelGender', '$showModelDivision', UTC_TIMESTAMP()
             from frithi_HORSESHOW.PERSON_MODEL where not exists (select 'x' from frithi_HORSESHOW.PERSON_MODEL pm where pm.SHOW_MODEL_ID = '$showModelId') limit 1";
     
     $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
     
     
     if($result)
     {
         return true;
     }
     else
     {
         return false;
     }
}

function BfModelExists($showModelId)
{
         //Make sure model doesn't already exists
     $selectQuery = "select 'x' from frithi_HORSESHOW.PERSON_MODEL pm where pm.SHOW_MODEL_ID = '$showModelId'";
     
     $result = mysqli_query($GLOBALS['dbh_horseshow'], $selectQuery);
     
     if($result)
     {
         $row = mysqli_fetch_row($result);
         
         if($row)
         {
             return true;
         }
         else
         {
             return false;
         }
     }
     else
     {
         return true;
     }
}

function BfPersonExists($personId)
{
         //Make sure model doesn't already exists
     $selectQuery = "select 'x' from frithi_HORSESHOW.PERSON p where p.ID = $personId";
     
     $result = mysqli_query($GLOBALS['dbh_horseshow'], $selectQuery);
     
     if($result)
     {
         $row = mysqli_fetch_row($result);
         
         if($row)
         {
             return true;
         }
         else
         {
             return false;
         }
     }
     else
     {
         return true;
     }
}

function SelectParentList($showid)
{
    $query = "select ID, NAME 
                from DIVISION 
                where SHOW_ID=$showid and DIVISION_TYPE_ID = (select ID from DIVISION_TYPE where NAME = 'DIVISION')";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
}   

function SelectChildList($showid, $parentid)
{

    $query = "select ID, NAME from DIVISION where SHOW_ID = $showid and PRIMARY_DIVISION_ID = $parentid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
}

function SelectBfClassList($showid)
{
    $query = "select ID, NAME from DIVISION where SHOW_ID = $showid and DIVISION_TYPE_ID = 1";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
}

function SelectPlacement($showid, $divisionid, $rank)
{
    $showid = sanitizeIntegerOnly($showid);
    $divisionid = sanitizeIntegerOnly($divisionid);
    $rank = sanitizeIntegerOnly($rank);
    
    $query = "select distinct pm.SHOW_MODEL_ID, pm.SHOW_MODEL_NAME
                from frithi_HORSESHOW.PLACEMENT p
                inner join frithi_HORSESHOW.PERSON_MODEL pm on p.PERSON_MODEL_ID = pm.ID 
                where p.DIVISION_ID = $divisionid and p.SHOWS_ID = $showid and p.RANK_ID = $rank";
    
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    $row = mysqli_fetch_row($result);
    
    $modelId = $row[0];
    $modelName = $row[1];
    
    $array = array(
                "ModelId" => $modelId,
                "ModelName" => $modelName,
                );

    return $array;
}

//
// THIS WILL NOT WORK NEXT YEAR
//
function BfModelSearch($showModelId)
{
   
    $showModelId = sanitizeIntegerOnly($showModelId);
    
    $query = "select pm.SHOW_MODEL_ID, pm.SHOW_MODEL_NAME, p.NICKNAME, pm.ID from frithi_HORSESHOW.PERSON_MODEL pm
                inner join frithi_HORSESHOW.PERSON p on pm.PERSON_ID = p.ID
                where pm.SHOW_MODEL_ID = '$showModelId'";

    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if($result)
    {
        $row = mysqli_fetch_row($result);
        
        if($row)
        {
            $modelId = $row[0];
            $modelName = $row[1];
            $exInitials = $row[2];
            $personModelId = $row[3];

            $array = array(
                "ModelId" => $modelId,
                "ModelName" => $modelName,
                "ExInitials" => $exInitials,
                "PersonModelId" => $personModelId,
                );
            return $array;
        }
        else
        {
            return false;
        }
    }
    else 
    {
      return 0;    
    }
    
}

//
// !!! WILL NOT WORK NEXT YEAR
//
function IsBfModel($showModelId)
{
    $showModelId = sanitizeIntegerOnly($showModelId);
    
    $query = "select ID from frithi_HORSESHOW.PERSON_MODEL where SHOW_MODEL_ID = '$showModelId'";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if($result)
    {
        $row = mysqli_fetch_row($result);
        
        if($row)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else 
    {
      return false;;    
    }
    
}

function SelectDivName($divid)
{
    $divid = sanitizeIntegerOnly($divid);
    
    $query = "select NAME from DIVISION where ID = $divid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if($result)
    {
        $row = mysqli_fetch_row($result);
        
        if($row)
        {
            return $row[0];
        }
        else 
        {
            return false;
        }
    }
    else 
    {
        return false; 
    }
}

function InsertPlacement($divid, $showid, $rankid, $modelid)
{
    $query = "insert into frithi_HORSESHOW.PLACEMENT(ID,DIVISION_ID,SHOWS_ID,RANK_ID,PERSON_MODEL_ID,ADD_DATE)
                values (0,$divid,$showid,$rankid,$modelid,NOW())";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if($result)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function UpdatePlacement($divid, $showid, $rankid, $modelid)
{
    $query = "update frithi_HORSESHOW.PLACEMENT set PERSON_MODEL_ID = $modelid where DIVISION_ID=$divid and SHOWS_ID=$showid and RANK_ID = $rankid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if($result)
    {
        return true;
    }
    else
    {
        return false;
    }
    
}

function PlacementExists($divid, $showid, $rankid)
{
    $divid = sanitizeIntegerOnly($divid);
    $showid = sanitizeIntegerOnly($showid);
    $rankid = sanitizeIntegerOnly($rankid);
    
    $query = "select DIVISION_ID from PLACEMENT where DIVISION_ID=$divid and SHOWS_ID=$showid and RANK_ID=$rankid";
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    if($result)
    {
        $row = mysqli_fetch_row($result);
        
        if($row[0] == $divid)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function BfResultSet($showid, $divid)
{
    $query = 'select concat(p.RANK_ID,". ", pm.SHOW_MODEL_NAME, "/", pe.NICKNAME) as name, p.RANK_ID
                from frithi_HORSESHOW.PLACEMENT p 
                inner join frithi_HORSESHOW.PERSON_MODEL pm on p.PERSON_MODEL_ID = pm.ID
                inner join frithi_HORSESHOW.PERSON pe on pm.PERSON_ID = pe.ID
                where p.DIVISION_ID = '.$divid.' and p.SHOWS_ID = '.$showid.' order by 2';
    
    $result = mysqli_query($GLOBALS['dbh_horseshow'], $query);
    
    return $result;
}


class Rank
{
    const FIRST = 1;
    const SECOND = 2;
    const THIRD = 3;
    const FOURTH = 4;
    const FIFTH = 5;
    const SIXTH = 6;
    const SEVENTH = 7;
    const EIGTH = 8;
    const NINETH = 9;
    const TENTH = 10;
    const HM = 11;
    const GC = 12;
    const RC = 13;
}

?>