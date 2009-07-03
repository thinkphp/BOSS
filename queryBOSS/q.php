<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"><html><head><title>queryBOSS</title><link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css"> <link rel="stylesheet" href="queryBOSS.css" type="text/css"> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
     <body> 
       <div id="doc2" class="yui-t7"> 

         <div id="hd" role="banner"><h1><a href="index.php">query<span>BOSS</span></a></h1></div> 

          <div id="bd" role="main"> 

                 <div class="yui-g"> 

                         <?php require_once('queryBOSS-config.php');?>

                         <form action="<?php echo$filename;?>" method="get" accept-charset="utf-8" id="form">

                             <p><label for="s">Search for:</label><input type="text" id="s" name="s" value="<?php if(isset($_GET['s'])) {echo$_GET['s'];}?>" size="40"><input type="submit" value="Images Search"></p>

                         </form>

                         <?php require_once('queryBOSS.php');?>

                 </div> 
	 
	  </div><!-- end bd -->

        <div id="ft" role="contentinfo"><p>Written By <a href="http://thinkphp.ro">Adrian Statescu</a>. Using YUI and BOSS</p></div> 

    </div><!-- end doc -->
</body> 
</html> 