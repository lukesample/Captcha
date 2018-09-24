<?php
    /**
     * Author: chris at linuxuser.at
     * Licence: MIT
     */
    date_default_timezone_set('America/Chicago');
    
    $fn = "chat.txt";
    $maxlines = 20;

    $nick_maxlength = 10;

    /* Set this to a minimum wait time between posts (in sec) */
    $waittime_sec = 0;

    /* spam keywords */
   

    /* IP's to block */
    $blockip[] = "72.60.167.89";

    /* spam, if message IS exactly that string */
    $espam[] = "ajax";
    
    $msg = $_REQUEST["m"]; //message
    $n = $_REQUEST["n"]; //nickname
    $d = $_REQUEST["d"]; //captcha on or off
    
    if ($waittime_sec > 0) {
        $lastvisit = $_COOKIE["lachatlv"];
        setcookie("lachatlv", time());

        if ($lastvisit != "") {
            $diff = time() - $lastvisit;
            if ($diff < $waittime_sec) { die(); }
        }
    }

    if ($msg != "") {
        if (strlen($msg) < 2) { die(); }
        if (strlen($msg) > 3) {
            /* Smilies are ok */
            if (strtoupper($msg) == $msg) { die(); }
        }
        if (strlen($msg) > 150) { die(); }
        if (strlen($msg) > 15) {
            if (substr_count($msg, substr($msg, 6, 8)) > 1) { die(); }
        }

    //     foreach ($blockip as $a) {
    //         if ($_SERVER["REMOTE_ADDR"] == $a) { die(); }
    //     }

        $mystring = strtoupper($msg);
        foreach ($spam as $a) {
             if (strpos($mystring, strtoupper($a)) === false) {
                 /* Everything Ok Here */
             } else {
                 die();
             }
        }

    //     foreach ($espam as $a) {
    //         if (strtoupper($msg) == strtoupper($a)) { die(); }
    //     }

         $handle = fopen ($fn, 'r');
         $chattext = fread($handle, filesize($fn)); fclose($handle);

         $arr1 = explode("\n", $chattext);

        if (count($arr1) > $maxlines) {
            /* Pruning */
            $arr1 = array_reverse($arr1);
            for ($i=0; $i<$maxlines; $i++) { $arr2[$i] = $arr1[$i]; }
            $arr2 = array_reverse($arr2);
        } else {
            $arr2 = $arr1;
        }

         $chattext = implode("\n", $arr2);

        // Last spam filter: die if message has already been in the chat history
        //if (substr_count($chattext, $msg) > 2) { die(); }

        $spaces = "";
        if (strlen($n) > $nick_maxlength-1) $n = substr($n, 0, $nick_maxlength-1);
        for ($i=0; $i<($nick_maxlength - strlen($n)); $i++) $spaces .= " ";
        
        
        if ($d == 1)
        {
            //$wordArray = []; //create empty array to place words into
            
            //this array is used as a "bool" array, 0 for false 1 for true
            //if 0, letter isn't converted. if 1, letter is converted
            $convertedArray = [];
        
            $wordArray = preg_split("/[\s,]+/", $msg);
            $searchStartIndex = 0;
            
            for($i = 0; $i < sizeof($wordArray); $i++)
            {
                
                $replaceString = ""; //clear the contents of the string to prevent overwriting
                $wordToReplace = $wordArray[$i]; //target word
                echo "Word to replace:  $wordToReplace";
                $strLen = strlen($wordToReplace);
                $convertedPercent = 0; //percent of the current word that is converted
                $lettersConverted = 0; //how many letters were converted, used for calculating % converted
                srand(mktime());
                for ($k = 0; $convertedPercent < .5; $k++) 
                {
                    $randomIndex = rand(0, $strLen - 1); //randomly choose an index to change
                    echo "Randomindex:  $randomIndex ";   
                    if ($convertedArray[$randomIndex] !== 1) //if the letter hasn't been converted
                    {
                        echo "inside the if for conversion array";
                        $convertedArray[$randomIndex] = 1; //set the conversion value to true
                        $lettersConverted++; //increment the total letters converted
                        $convertedPercent = $lettersConverted / $strLen; //calculate percentage of the word that is converted
                        echo "Converted percent: $convertedPercent";
                    }
                }//end for (bool array to select letters to change)
                
                //iterate over each character, creating captcha html code for each
                for ($j = 0; $j < $strLen; $j++) 
                {
                    if ($convertedArray[$j] === 1) // || isVowel($wordToReplace[$j]))
                    {
                        $replaceString = $replaceString . "<img src = \"https://lukesample.000webhostapp.com/captcha/" . $wordToReplace[$j] . ".jpeg\">";
                    }
                    else
                    {
                        $replaceString = $replaceString . $wordToReplace[$j];
                    } //end inner if/else
                  
                }//end inner for(conversion + string building)
                
                $replaceString = $replaceString . "&emsp;";

                //$msg = chatmsg.value.replaceAll(wordArray[i], replaceString);
                
                //find the position in the message where the first occurance of the word is
                //starts checking after the already converted words so that they are not
                //overwritten or messed up
                $pos = strpos($msg, $wordArray[$i], $searchStartIndex);
                if ($pos !== false)
                {
                    $msg = substr_replace($msg, $replaceString, $pos, strlen($wordArray[$i]));
                }

                //used to skip over what we have replaced so far in the string.  this prevents replacing parts of a string we have already replaced
                $searchStartIndex += strlen($replaceString); 
                $convertedArray = array(); //clears the array to reuse
            
            
        } 
        
    }//end outer if
        
        
        //monday, september 13 hour:minute:second
        $out = $chattext . $n . " - " . date('l\, F j g:i:s A') . " <br> &emsp;" . $msg . " <br><br> ";
        $out = str_replace("\'", "'", $out);
        $out = str_replace("\\\"", "\"", $out);

        $handle = fopen ($fn, 'w'); 
        fwrite($handle, $out.trim()); 
        fclose($handle);
    }
?>
