<?php

/*
 * This contains functions use to format output
 */

//
//  This will return a string in Pascal Case
//
function FormatStringPascalCase($inputString)
{
    $stringArray = str_split($inputString); //string to format

    $returnString = ""; //string to return
    
    for($i = 0; $i < sizeof($stringArray); $i++)
    {
        if($i == 0)
        {
            $returnString = $returnString.strtoupper($stringArray[$i]);
        }
        else
        {
            $returnString = $returnString.strtolower($stringArray[$i]);
        }
    }
    
    return $returnString;
}

//
//  This will split a string based on a char and return the original string in Pascal Case
//
function FormatStringSplitPascalCase($inputString, $splitChar)
{
    $strings = split($splitChar, $inputString);
    
    $returnString = "";
    
    if(sizeof($strings) == 0)
        return FormatStringPascalCase ($inputString);
    else
    {
        for($i = 0; $i < sizeof($strings); $i++)
        {
            if($i == 0)
                $returnString = FormatStringPascalCase ($strings[$i]);
            else
                $returnString = $returnString.$splitChar.FormatStringPascalCase($strings[$i]);
        }
    
    }
    
    return $returnString;
}

?>
