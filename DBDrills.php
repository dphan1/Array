<?php
   $pdo = new PDO("mysql:host=localhost;dbname=datastore", 'root', 'root'); // Establish database connection
   
   $statement = $pdo->prepare('SELECT email, timestamp, timezone FROM users WHERE cust_id = 19'); // Query creation
   $statement->execute();

   $arrayResult = $statement->fetch(PDO::FETCH_ASSOC); // Fetch search result into an array
   
   // Print out email
   print "Email: ";
   print $arrayResult["email"]; 
   print "<br>";
  
   $timeZone = $arrayResult["timezone"]; // get the timezone value from the database
   $timeStamp = $arrayResult["timestamp"]; // get the timestamp value from the database

   $arrayTimeZoneChar = str_split($timeZone); // Split $timeZone into characters, and put them into an array

   /* Two possible cases:
    * Case 1: -3:00 (or +5:00) (from 0 to 9)
    * Case 2: -11:00 (or +10:00) (10 and above)
    * We just want to get the value 3, or 5, or 11
    */
   if (count($arrayTimeZoneChar) == 5) { // Time zone value is between 0 and 9
      $timeZone = $arrayTimeZoneChar[1];
   } else { // Time zone value is greater than 9
      $timeZone = $arrayTimeZoneChar[1] . $arrayTimeZoneChar[2];
   }

   // Compute user local time in epoch time (in seconds, since 1970)
   if ($arrayTimeZoneChar[0] == '-') {
      $localTime = $timeStamp - ($timeZone * 3600);
      $location = new DateTimeZone(timezone_name_from_abbr("", -$timeZone * 3600, 0)); // get location based on offset
   } else {
      $localTime = $timeStamp + ($timeZone * 3600);
      $location = new DateTimeZone(timezone_name_from_abbr("", $timeZone * 3600, 0)); // get location based on offset
   }

   $abbreviation = ""; // Use this to set time zone later on

   /* getTransitions() return numberically indexed array containing associative array
    * Some of the keys in the associative array:
    * [ts], [time], [offset], [isdst], [abbr]
    * We just want [abbr]
    */
   $transitions = $location->getTransitions();
   for ($i = 0; $i < count($transitions); $i++) {
      $abbreviation = $transitions[$i]["abbr"];
      $abbreviationArray = str_split($abbreviation); // get abbreviation attribute, split it into characters, put those characters into array
      if ($abbreviationArray[1] == "S") { // Look for a standard time, indicated by the letter 'S' in the middle of the abbreviation         
         break; // If found a standard time, get out of the loop!
      }      
   }
   
   date_default_timezone_set($abbreviation); // Set timezone to $abbreviation, which we found it earlier
   print "Sign-up date: ";
   print date("g:i a T F j, Y", $localTime);

   print "<br>";

   $sent = mail("dat@pinleague.com", "Hamburger", "Chicken"); // Sending email doesn't work :( 
   echo $sent;
?>
