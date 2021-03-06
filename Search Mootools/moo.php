<?php include_once('./boss-config.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title><?php echo$headername; ?></title>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <link rel="stylesheet" href="http://yui.yahooapis.com/2.5.1/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
  <style>

   html,body{

      background:#369;
      color:#000;
      font-family:"Lucida Grande",Helvetica,Arial,Sans-serif;
   }

   form,#nav{

      background:#036;
      border-radius:5px;
      -moz-border-radius:5px;
      -webkit-border-radius:5px;
      padding:1em;
      color:#fff;
      font-size:120%;
      overflow:hidden;
    }



   #nav a{

      text-decoration:none;
      color:#fff;
   }


   .kw{
      padding:.5em;
      margin-top:.5em;
      background:#ddd;
   }
 
   .kw a{

      color: #000;
      text-decoration: none;
      display:block;        
   }


    h1{

      font-size:250%;
      margin:.5em 0;
      color:#036;
      font-family:"futura","Gill Sans",Arial,sans-serif;
      text-align:center;
    }


   #ft{

       margin-top:2em;
       color:#222;
       font-size:90%;
    }

   #ft a{

       color:#000;
   }


   #doc{

        background:#fff;
        border:1em solid #fff;
   }


  h2{
        font-size:130%;
        padding:1em 0 .5em 0;
    }

  h2 a{color: #001D52}

 
  #results{

        margin-bottom: 1em;
  }


  ul{

    margin:0;
    padding:.5em;
     
  }

  ul li {display: inline;padding: 3px;}
 
  #resultscounter {padding: 1px;margin: 3px}

  .keyterms li a{color: #888}

  #nav li.right {float: right}

  #nav li.left {float: left}

  span { background: transparent url(sitepoint.gif) no-repeat scroll left top;height:67px; min-width: 209px; position: absolute; width: 21%; top: 17px;left: 245px; }

  #results {margin-bottom:2em;}

  #results.js ul {display:none;}

  #results.js ul.show {display:block;background:#eee;}


  </style>
</head>
<body>
<div id="doc" class="yui-t7">

    <span></span>
 
    <div id="hd"><h1><?php echo$headername; ?></h1></div>

    <div id="bd">

      <div class="yui-g">

        <form action="<?php echo$filename;?>" method="get" accept-charset="utf-8">
        <p><label for="s">Search for:</label><input type="text" id="s" name="s"><input type="submit" value="Search"></p>
        </form>

        <?php include_once('./boss-search.php'); ?>

      </div>

    </div><!-- end bd -->

    <div id="ft">

      <p>Written by <a href="http://thinkphp.ro">Adrian Statescu</a>, powered by <a href="http://developer.yahoo.com/search/boss/">BOSS</a> and <a href="http://developer.yahoo.com/yui/3/">YUI</a></p>
      <p>This is not an official Yahoo! site and not endorsed by the company.</p>

    </div><!-- end ft -->

</div><!-- end doc -->

 <script type="text/javascript" src="http://yui.yahooapis.com/3.0.0pr1/build/yui/yui-min.js"></script>
 <script type="text/javascript">

// get the YUI 3:)
YUI({combine: true, timeout: 10000}).use("node",
function(Y){

//If we have an ID results then execute
if(Y.get('#results')) {

// add a class to the results list to trigger the different style
   Y.get('#results').addClass('js');

// inject links into the keyword labels
   Y.all('#results .kw').set('innerHTML',
                            '<a href="show keywords">Keywords</a>');

// on clicking the results list
   Y.get('#results').on('click',function(e){ 

// get the parent node
   var parent = e.target.get('parentNode');

// make sure it was the keyword label 
   if(parent.hasClass('kw')){

// don't follow the link
   e.preventDefault();

// get the target and the next sibling of the parent (the link and the nested 
// list of keywords)
      var current = e.target;
      var next = parent.next();

// if the class show exists, remove it and change the link and parent class 
// accordingly 
      if(next.hasClass('show')){
        current.set('href','show keywords');
        next.removeClass('show');
        parent.removeClass('open');

// otherwise do the opposite
      } else {
        current.set('href','hide keywords');
        next.addClass('show');
        parent.addClass('open');
      }
    } 
  });

}//end if


});

 </script>

</body>
</html>