<?php
include 'top.php';

// Open a CSV file
$debug = false;
if(isset($_GET["debug"])){
     $debug = true; 
}
// as in menu.php, read in the CSV file
$myFolder = 'data/';

$myFileName = 'allergies';

$fileExt = '.csv';

$filename = $myFolder . $myFileName . $fileExt;

if ($debug) print '<p>filename is ' . $filename;

$file=fopen($filename, "r");

if($debug){
    if($file){
       print '<p>File Opened Successful.</p>';
    }
    else{
       print '<p>File Open Failed.</p>';
     }
}
if($file){
    if($debug) print '<p>Begin reading data into an array.</p>';

    // read the header row
    $headers[] = fgetcsv($file);

    if($debug) {
         print '<p>Finished reading headers.</p>';
         print '<p>My header array</p><pre>';
         print_r($headers);
         print '</pre>';
     }

     // read all the data
     while(!feof($file)){
         $allergies[] = fgetcsv($file);
     }

     if($debug) {
         print '<p>Finished reading data. File closed.</p>';
         print '<p>My data array<p><pre> ';
         print_r($allergies);
         print '</pre></p>';
     }
} // ends if file was opened 
fclose($file);

$allergyType = "";

// open the appropriate page depending on
// what the user clicked
if(isset($_GET["allergyType"])){
    $allergyType = htmlentities($_GET['allergyType'], ENT_QUOTES, "UTF-8");    
}

// print only the row for the allergy they selected
foreach ($headers as $header) {
    foreach ($allergies as $allergy) {
        if ($allergyType == $allergy[0]){
            print '<p>' . $header[0] . ': ' . $allergy[0] . '</p>';
            print '<p>' . $header[1] . ': ' . $allergy[1] . '</p>';
            print '<p>' . $header[2] . ': ' . $allergy[2] . '</p>';
            print '<p>' . $header[3] . ': ' . $allergy[3] . '</p>';
        }
    }   
}

include 'footer.php';
?>