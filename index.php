<?php
 session_start();
 $address = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . "/";

 if(isset($_SESSION['user']) || isset($_SESSION['pwd'])){
     if($_SESSION['user'] != '' || $_SESSION['pwd'] != ''){

         $user = $_SESSION['user'];
	 $pwd = $_SESSION['pwd'];

     } else {
         header('Location: ' . $address . 'logon.php');
     }
 } else {
     header('Location: ' . $address . 'logon.php');
 }

?>
<!DOCTYPE HTML>
<html>
<head>
   <title>Mobile Bookmarker</title>
   <link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.css" />
   <script src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
   <script src="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.js"></script>
</head>
<body>
 
   <script> 
      $(function() {

         var user = "<?php echo $user; ?>";
         var pwd = "<?php echo $pwd; ?>";
         var theName;
            $("#callAjax").click(function() {
                theName = $.trim($("#theName").val());

		if(theName.length > 0)
		{
                    $.ajax({
                       type: "GET",
		       url: "<?php echo $address; ?>titlescraper.php",
                       data: ({url: theName}),
                       cache: false,
                       dataType: "text",
                       success: onSuccess
		     });

		    $.ajax({
		       type: "POST", 
		       url: "<?php echo $address; ?>deliciousTags.php", 
		       data: ({url: theName, 
		              user: user, 
		              pwd: pwd
		      }), 
                       cache: false, 
		       dataType: "text", 
		       success: onSuccessTags
		    });

	      
		       $.mobile.changePage("#enterPage","slide", false, true);
                }
            });
 
            $("#resultLog").ajaxError(function(event, request, settings, exception) {
              $("#resultLog").html("Error Calling: " + settings.url + "<br />HTTP Code: " + request.status);
            });
 
            function onSuccess(data)
            {
		    $("#resultLog").html("Result: " + data);
		    $('#urlbox').val(theName);
		    $('#titlebox').val(data);
            }

	    function onSuccessTags(dataTags)
	    {
		    // TODO Break apart tags by "," 
		    // TODO Turn tags into href's that get added when clicked rather than automatically 
		    // Tags added automatically to tagsbox      
		    $("#tagsbox").val(dataTags);
		    
		    console.log("tags should be loading");
            }
	    // Button click - Page 2
	    $("#callAjaxAdd").click(function(){
                //vars to store  text box data
		var title = $.trim($("#titlebox").val());
	        var tags = $.trim($("#tagsbox").val());
	        var notes = $.trim($("#notesbox").val());
		var publicswitch = $("#publicswitch").val();

                //Testing main vars
                //$("#resultLog2").html("name: " + user + " pwd: " + pwd + " URL: " + theName + " Title: " + title + " Tags: " + tags + " Notes: " + notes + " Switch: " + publicswitch);
	
	if(theName.length > 0 && title.length > 0){
		//console.log("starting ajax 2");
                $.ajax({
		    type:"POST", 
                    url: "<?php echo $address; ?>postadd.php", 
		    data: ({user: user, 
		            pwd: pwd, 
			    url: theName, 
		            title: title, 
		            tags: tags, 
		            notes: notes, 
		            publicswitch: publicswitch}), 
		    cache: false, 
		    dataType: "text", 
		    success: onSuccessAdd
	          });
	         }
        //
             });
            function onSuccessAdd(dataAdd)
	    {
		    $("#resultLog3").html("Result: " + dataAdd);
		    console.log("postAdd returned");
	    }
        });
    </script>
 
    <div data-role="page" id="indexPage" data-theme="a">
       <div data-role="header" data-nobackbtn="true">
	    <h1>Delicious Bookmarks</h1>
	    <a href="logon.php?id=logoff" data-direction="reverse" rel="external" data-icon="delete" class="ui-btn-right">Log Off</a>
        </div>
        <div data-role="content">
            <div data-role="fieldcontain">
                <label for="textfield">Enter URL to bookmark:</label>
                <input type="text" id="theName" name="theName" value="" />
            </div>
	        <input id="callAjax" type="button" value="Get URL Data" data-theme="b"/> 
            <div id="resultLog"></div>
        
        </div>
	<div data-role="footer">
	<h1><?php echo $user; ?> &nbsp; <a href="aboutdialog" data-rel="page" data-transition="pop">About</a></h1>
        </div>
    </div> <!-- end indexPage -->
    
    <div data-role="page" id="enterPage" data-theme="a">
	    <div data-role="header">
                <a href="indexPage" data-rel="back" data-direction="reverse">Back</a>
                <h1>Enter URL Information</h1>
                <a href="logon.php?id=logoff" data-ajax="false" data-direction="reverse" rel="external" data-icon="delete" class="ui-btn-right">Log Out</a>
            </div>
	    <div data-role="content">

	        <div data-role="fieldcontain">
                    <label for="urlbox">URL</label>
	            <input type="text" id="urlbox" name="urlbox" value="" />
                    <br />

                    <label for="titlebox">Title</label>
                    <input type="text" id="titlebox" name="titlebox" value="" />
                    <br />
		    
                    <label for="tagsbox">Tags</label>
                    <input type="text" id="tagsbox" name="tagsbox" value="" />
                    <br />
                   
                    <label for="notesbox">Notes</label>
                    <input type="text" id="notesbox" name="notesbox" value="" />
                    <br />

		    <label for="publicswitch">Private</label>
		    <select name="publicswitch" id="publicswitch" data-role="slider">
		        <option value="private">Private</option>
		        <option value="public">Public</option>
		    </select>	

                </div>
            </div>
            <input id="callAjaxAdd" type="button" value="Save to Bookmark" data-theme="b" />
	    <div id="resultLog2"></div>
	    <div id="resultLog3"></div>
            <div id="resultLogTags"></div>      
        <div> <?php // echo $address; ?> </div>     
	    <div data-role="footer">
	    <h1>Add to Delicious</h1>
	    </div>
         </div>

         <!-- about dialog -->
	<div data-role="page" id="aboutdialog" data-theme="a">
          <div data-role="header">
            <h2>About</h2>
          </div>
	  <div data-role="content">
            <div>
                <p>This is a project for learning jQuery Mobile and posting information to a social network.</p>
                
                <p>Check out the code on Github.</p>
                
                <p>Find me on <a href="http://twitter.com/rsturk" data-theme="b" date-role="button">Twitter (@rsturk)</a></p>
                <br />
                <a href="indexPage" data-rel="page" data-theme="b" data-role="button" data-transition="pop">Back</a>

             </div>
	  </div>
          <div data-role="footer">
            <h2> </h2>
          </div>
        </div> <!-- End about dialog -->

    </div> <!-- End enterPage -->

</body>
</html>
