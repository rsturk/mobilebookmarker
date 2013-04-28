<?php
// Get tags from Delicious.com for a specific URL
// Output tags formatted to be saved to Delicious

// GET the URL
$url = $_POST["url"];
$name = $_POST["user"];
$pwd = $_POST["pwd"];

// username and pw needed to access the delicious tags
$domain = "https://" . $name . ":" . $pwd . "@api.del.icio.us/v1/posts/suggest?url=" . $url;

// Curl the url, format the data, and output it

//Curl function!
function addBookmark($burl) { 
    $data = $burl; 
    // Create a curl handle to a non-existing location 
    $ch = curl_init($data); 
    // Execute 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $myresult = curl_exec($ch); 
    // Check if any error occured 
    if(curl_errno($ch)) 
    { 
         echo 'Curl Error: ' . curl_error($ch) . "Name: " . $name . "pw: " . $pwd; 
    } else { 
        return $myresult; 
    } 
}

$dresponse = addBookmark($domain);
$mytags = "";
// testing
if($url){
    $dom = new DOMDocument();
    // this was loadHTML($dresponse);
    @$dom->loadHTML($dresponse);

    //create an empty array to store data
    $links = array();
    $moreLinks = array();
    $allLinks = array();
    // loop through the recommended tags to store in array
    foreach($dom->getElementsByTagName('recommended') as $link){
        //$links[] = array('url' => $link->getAttribute('href'), 'text' => $link->nodeValue);
        $links[] = array('text' => $link->getAttribute('tag'));
    }

    $dom2 = new DOMDocument();
    @$dom2->loadHTML($dresponse);

    // get the popular tags
    foreach($dom2->getElementsByTagName('popular') as $moreLink){
        $moreLinks[] = array('text' => $moreLink->getAttribute('tag'));
    }
     
    //combine the recommended and popular arrays
    $allLinks = array_merge($moreLinks, $links);

    $tagsnumber = sizeof($allLinks); 
      
    //formatting tags
    if($tagsnumber > 1) {
        for($i=0; $i<$tagsnumber; $i++){
            if(isset($allLinks[$i]['text'])){
                $mytags .= $allLinks[$i]['text'] . ", ";
	    }
	}
    } else {
        $mytags = $allLinks[0]['text'];
    }
} 
echo $mytags; 
?>
