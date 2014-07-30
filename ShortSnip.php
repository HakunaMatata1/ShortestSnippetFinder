<?php

//Problem:
//Suppose you are given a full page of text, 1000+ words, and a list of search terms. Write an algorithm 
//that will traverse the entire page and find the shortest snippet of text that contains all the search terms in any order. 
//The output should be the entire snippet that contains all terms, along with a list of numeric word count positions for the terms.


//How to run: Install Microsoft WebMatric at http://www.microsoft.com/web/webmatrix/
//After Installing the WebMatrix, A WebPlatform Installer should pop up again, and then click on the Product tab
//Scroll down until you find "PHP 5.5.11 For IIS Express" and then click Add and Install
//Afterwards find the     +                                                                                                                   ShortSnip.php wherever you saved it, and right click and open with "WebMatrix"
//The application should open up a screen with the code on it, and to the left, right click CRcodechallenge.php and click Launch in Browser to run the code

//Test Cases:
//1 key word
//5 key words
//10+ key words
//identical key words correctly shows invalid
//different copied text formats
//added code so that hyperlinks or other heavily symboled texts can be used as search terms
//500+ body text

//Version 3 Changes
//cleared up the parsing portion of the code to look a bit more clean
//now parses things correctly (before there were slahes being added which screwed up the program sometimes)
//added some code to make the printed stuff look like it was from original text rather than being lower cased and punctuations removed


//By: Justin Suen 7/20/2014

$time_start = microtime(true);

// Sleep for a while
usleep(100);


function optimize_search($array)
{
    $punctuation = array('.',':',';','`','~',',','(',')','"','-','=','+','{','@','#','$','%','^','&','*','_','}','?','|','/','"\"','!',"'");   
    for ($x=1;$x<=(count($array)-1);$x++)
    {
        if(str_replace($punctuation,"",$array[0]) == str_replace($punctuation,"",$array[$x]))
           {
                return true;
           }
           
    }
    return false;                       
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
        <form action="ShortSnip.php" method="post">
        Text: <input type="textarea" rows="4" cols="50" name="body"><br>
        Search Terms (e.g. new, york, jesus): <input type="text" name="search"><br>
        <input type="submit">        
        </form>
    <?php
        //used to keep track of possible punctuations to filter from texts
        $punctuation = array('.',':',';','`','~',',','(',')','"','-','=','+','{','@','#','$','%','^','&','*','_','}','?','|','/','"\"','!',"'");
        //pulls body of text and splits into array without punctuations
        $body = explode(" ",strtolower($_POST["body"]));
        $newbody = str_replace($punctuation,"",$body);
        //body left untouched to print out nicely later
        $prettybody = explode(" ",$_POST["body"]);
        
        $search = str_replace($punctuation,"",explode(", ",strtolower($_POST["search"])));
        $searchindex = array();
        $currentposition = array();
        $currentitem = array();
        $matches = 0;
        $length = 0;
        $counter = 0;
        $minlength ="100000000000";
        
        //determines if searchterm is used or not
        foreach ($search as $value)
        {$searchindex[$value] = "false";}
        //traverses through body of text
        foreach($newbody as $value)
        {
            $counter++;
            //if this element is in the search term array
            if (in_array(str_replace($punctuation,"",$value),$search))
            {
                 //updates matches if searchterm isnt used
                 if ($searchindex[$value] == 'false')
                 {
                      $matches++;
                 }
                 $searchindex[$value] = 'true';
                 //double arrays to keep track of pos and value
                 $currentitem[] = $value;
                 $currentposition[] = $counter;
                 

                 //optimize search
                
                while (optimize_search($currentitem))   
                 {
                         array_shift($currentitem);
                         array_shift($currentposition);
                           
                 }
                     //print_r($currentitem);
                     //print("<br>");  
                     
                  //find and save minimum length
                  if ($matches == count($search))
                  {   
                       $first = reset($currentposition);
                       $last = end($currentposition);
                       $length = ($last - $first)+1;
                       
                     
                       if ($length<$minlength)
                       {
                             $minlength = $length;
                             $final_position = $currentposition;
                             $final_item = array();
                             foreach ($final_position as $value)
                             {
                                 $final_item[] = $prettybody[$value-1];
                             }
                       }   
                       if($length = $minlength)
                       {
                           
                           $alt_position = $currentposition;
                           $alt_item = $currentitem;
                       }
                   }
                   }  

                
                  
            }
            
             print_r($final_position);
             print("<BR>");  
             print_r($final_item);
             print("<BR>");
             print("Answer: ");
             if (!empty($final_position))
             {echo implode(" ",array_splice($prettybody,reset($final_position)-1,end($final_position)-reset($final_position)+1));}
             else {echo "Invalid";}
             
             print ("<br>");
             print( "Minimum Length: "); 
             if ($minlength != "100000000000")
             {echo $minlength;}
             else {echo "Invalid";}
          
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            print("<BR>");
            print("Run-Time:$time");
        
    ?>    
    </body>
</html>
