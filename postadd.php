<?php
// Get post data from delicious mobile app
// Send post data to delicoius to add bookmark
// check for 500 or 999 (throttling)

$user = $_POST["user"];
$pwd = $_POST["pwd"];
$url = $_POST["url"];
$title = $_POST["title"];
$tags = $_POST["tags"];
$notes = $_POST["notes"];
$publicSwitch = $_POST["publicswitch"];

// change the $shared var to yes or no based on publicSwitch
if($publicSwitch == "public"){
     $shared = "yes";
 } else {
     $shared = "no";
 }

// https://$name:$password@api.del.icio.us/v1/posts/add?...
// CAUTION! This should be reimplemented to be more secure
$toSend = "https://" . $user . ":" . $pwd . "@api.del.icio.us/v1/posts/add?url=" . $url . "&description=" . urlencode($title) . "&extended=" . urlencode($notes) . "&tags=" . urlencode($tags) . "&shared=" . $shared;

//Curl function!
function addBookmark($domain) { 
    $data = $domain; 
    // Create a curl handle to a non-existing location 
    $ch = curl_init($data); 
    // Execute 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $myresult = curl_exec($ch); 
    // Check if any error occured 
    if(curl_errno($ch)){
        // echo 'Curl Error: ' . curl_error($ch); 
        return $myresult;
  } else { 
        return $myresult; 
  } 
}

$dresponse = addBookmark($toSend);

if($toSend){
    $dom = new DOMDocument();
    // this was loadHTML($dresponse);
    @$dom->loadHTML($dresponse);

    //create an empty array to store data
    $links = array();

    // loop through the data to store in array
    foreach($dom->getElementsByTagName('result') as $link){
         $links[] = array('text' => $link->getAttribute('code'));
    }

    echo $links[0]['text'];

} 
?>
