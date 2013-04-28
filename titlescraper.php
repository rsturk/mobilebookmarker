<?php
/* name: titlescrape.php
   Used by: bk.php
   This file will take in a php var ($pageinfo)
   
*/
function domain_check($domain) { 
  $data = $domain; 
  // Create a curl handle to a non-existing location 
  $ch = curl_init($data); 
  // Execute 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  // - TESTING //////////////////
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
 
  $bob = curl_exec($ch); 
  // Check if any error occured 
  if(curl_errno($ch)) 
  { 
     echo 'Curl Error: ' . curl_error($ch); 
  } else { 
     return $bob; 
  } 
  // Close handle 
  curl_close($ch); 
} 


//Set delicious username
if(isset($_GET['url'])){
	$pageinfo = $_GET['url'];
	if($pageinfo == ""){
		$pageinfo = 'http://newhighscore.com';
	}
} else{
	$pageinfo = false;
}


if($pageinfo){
    //curl input
    //append the tag to search for
    $myInput = $pageinfo;
    // Usage: 
    $dresponse = domain_check($myInput);
}


if($pageinfo){
    $dom = new DOMDocument();
    // this was loadHTML($dresponse);
    @$dom->loadHTML($dresponse);

    //create an empty array to store data
    $links = array();
    // loop through the data to store in array
    foreach($dom->getElementsByTagName('title') as $link){
        //$links[] = array('url' => $link->getAttribute('href'), 'text' => $link->nodeValue);
        $links[] = array('text' => $link->nodeValue);
    }
    //print_r($links);
    echo $links[0]['text'];

} else if(!$pageinfo){ ?>
<html>
<head>
<title>Title Scraper</title>

</head>
<body>
 <div id="pageinfo">
	<form id="grabDel" method="get" action="">
	  <p>
		<label for="url">URL:</label>
		<input name="url" id="url" type="text" class="formbox"
		<?php echo 'value="' . $pageinfo . '"'; ?>
		/>
		<input name="send" id="send" type="submit" value="Submit URL" />
	  </p>
	</form>
</div>
<?php
// close the html form 
  } 

/*
// --- NOTES ---

//$myreg = '/<%TAG%[^>]*>(.*?)</%TAG%>/';
function get_tag( $tag, $xml ) {
  $tag = preg_quote($tag);
  preg_match_all('{<'.$tag.'[^>]*>(.*?)</'.$tag.'>'.'}',
                   $xml,
                   $matches,
                   PREG_PATTERN_ORDER);

  return $matches[1];
}
*/


if(!$dresponse){
  echo "</div>";
  echo "</body>";
  echo "</html>";
}

/*
if($pageinfo){
    $dom = new DOMDocument();
    // this was loadHTML($dresponse);
    @$dom->loadHTML($dresponse);

    //create an empty array to store data
    $links = array();
    // loop through the data to store in array
    foreach($dom->getElementsByTagName('title') as $link){
        //$links[] = array('url' => $link->getAttribute('href'), 'text' => $link->nodeValue);
        $links[] = array('text' => $link->nodeValue);
    }
    //print_r($links);
    echo $links[0]['text'];
}
 */
?>
