<?php include('./getData.php');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>guardian.co.uk - news mixer</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <style> 

   html,body{ background:#369;color:#000;}

   #doc,#doc2{ background:#fff;border:1em solid #fff;}

   h1,h2,h3,h4 {font-family:Calibri,arsenal,sans-serif;}

   h1{font-size:300%;color: #4F77A9;font-weight: bold}

   h2,h3{ font-size:150%; margin-bottom:.5em}

   a {color:#666 }  

   strong{ font-weight:bold;}

   h3 a{text-decoration:none;color:#369;}

   #search h3,#articles h3{margin:0 0 .5em 0; font-size:130%;}

   #ft p{font-size:80%; margin:3em 0; padding-top:1em;}

   form{ font-size:150%; background:#ccc;padding:10px;margin:10px 0;}

   p { margin-bottom:1em;}

   #images{ overflow:auto;}

   #images li img{ display:block; margin:5px auto;}

   #images li{text-align:center;width:210px;margin-right:10px;height: 200px;overflow:auto; float:left;}

   #tags {font-size:130%;overflow:auto;padding:0 0 1em 0;}

   #tags li{float:left;padding-right:1em;}

  .keywords li{ display:inline; padding-right:1em;color: #393}
  .keywords {color: #6B81AA}

   </style>
</head>
<body>
<div id="doc2" class="yui-t7">

   <div id="hd" role="banner"><h1>Guardian.co.uk</h1></div>

   <div id="bd" role="main">

	<div class="yui-g">

        <form action="index.php">
          <div>
            <label for="s">Search for:</label>
            <input type="text" id="s" name="s">
            <input type="submit" value="do it">
         </div>
      </form>


	</div>

<div class="yui-g">

 <?php    if($error) { echo"<h2>We are sorry, this is an invalid search term. Please only enter words and spaces.</h2>";}  ?>

    <div class="yui-u first">
 
        
        <?php 

            if(!$error) {

                $search = urldecode($search);

                $label = str_replace('<strong>title</strong>',"<strong>$search</strong>",$label); 

                echo$label; 

                echo$keyterms;
            }

         ?>

        <?php 

            if(!$error) {

                echo"<h2>Images for <strong>$search</strong></h2>";

                echo$imagesresults;
            }

         ?>

        <?php 

            if(!$error) {

                echo"<h2>News results for <strong>$search</strong></h2>";

                echo$bossresults;
            }

        ?>


    </div>
    <div class="yui-u">

        <?php 

            if(!$error) {

              echo"<h2>Articles by The Guardian for <strong>$search</strong></h2>";

              echo$guardresults;
            }

        ?>

    </div>
</div>

	</div>

   <div id="ft" role="contentinfo"><p>Written By <a href="http://thinkphp.ro">Adrian Statescu.</a> Using YUI, BOSS and Guardian</p></div>

</div>
</body>
</html>
