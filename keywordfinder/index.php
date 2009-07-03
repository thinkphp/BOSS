<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html> 
<head> 
   <title>KeywordFinder - find keywords connected to a certain term</title> 
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css"> 
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <style>html,body {background: #ccc;font-family: arial,verdana} #doc {background: #ECECEC;border: 1em solid #ECECEC;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius: 10px} #hd h1 {font-size: 30px;color: #4C549A;font-weight: bold;margin-bottom: 10px} #bd {padding: 10px;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius: 10px} form {background: #85BB23;padding: 20px;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius: 10px} .yui-g {margin-top: 20px;padding: 4px} #related {padding: 3px;font-size: 14px;color: #4C549A} ul#topsource li{padding: 3px;list-style-type: decimal} #topsource a{color: #000;font-size: 15px} #ft a{color: #ccc;} #ft{color: #ccc;font-size: 11px} label{color: #fff;font-weight: bold;font-size: 20px} span{font-size: 27px;font-weight: bold;margin-bottom: 10px} select{font-size: 20px;font-weight: bold;margin-left: 154px;margin-top: 10px} .key {color: #96A1FB} #s,#run {font-size: 20px} </style>
</head> 
<body> 
   <div id="doc" class="yui-t7"> 
        <div id="hd" role="banner"><h1><span class="key">Keyword</span>Finder - find keywords connected to a certain term</h1></div> 
    <div id="bd" role="main"> 
	    <div class="yui-g"> 
                     <form action="index.php" method="post"> 
<label for="s">Find keywords for: </label><input type="text" name="s" id="s" value="<?php if(isset($_POST['s'])) {echo$_POST['s'];}?>"/><input type="submit" name="run" id="run" value="search" /><br/>
<label for="locale">in: </label>
<select name="locale" id="locale">
<option value="Argentina|ar|es">Argentina</option><option value="Austria|at|de">Austria</option><option value="Australia|au|en">Australia</option>
<option value="Brazil|br|pt">Brazil</option><option value="Canada - English|ca|en">Canada - English</option><option value="Canada - French|ca|fr">Canada - French</option><option value="Catalan|ct|ca">Catalan</option>
<option value="Chile|cl|es">Chile</option><option value="Columbia|co|es">Columbia</option><option value="Denmark|dk|da">Denmark</option><option value="Finland|fi|fi">Finland</option>
<option value="French|fr|fr">French</option><option value="German|de|de">German</option><option value="Indonesia - English|id|en">Indonesia - English</option><option value="Indonesia - Indonesian|id|id">Indonesia - Indonesian</option><option value="India|in|en">India</option><option value="Israel|il|he">Israel</option>
<option value="Italian|it|it">Italian</option><option value="Japan|jp|jp">Japan</option><option value="Korea|kr|kr">Korea</option><option value="Malaysia - English|my|en">Malaysia - English</option><option value="Malaysia|my|ms">Malaysia</option><option value="Mexico|mx|es">Mexico</option>
<option value="Netherlands|nl|nl">Netherlands</option><option value="New Zealand|nz|en">New Zealand</option><option value="Norway|no|no">Norway</option><option value="Peru|pe|es">Peru</option><option value="Philippines|ph|tl">Philippines</option><option value="Philippines - English|ph|en">Philippines - English</option><option value="Romania|ro|ro">Romania</option><option value="Russia|ru|ru">Russia</option><option value="Singapore|sg|en">Singapore</option><option value="Spanish|es|es">Spanish</option>
<option value="Sweden|se|sv">Sweden</option><option value="Switzerland - French|ch|fr">Switzerland - French</option><option value="Switzerland - German|ch|de">Switzerland - German</option><option value="Switzerland - Italian|ch|it">Switzerland - Italian</option><option value="Thailand|th|th">Thailand</option><option value="Turkey|tr|tr">Turkey</option><option value="United Kingdom|uen">United Kingdom</option><option value="United States - English|us|en" selected="selected" >United States - English</option><option value="United States - Spanish|us|es">United States - Spanish</option><option value="Venezuela|ve|es">Venezuela</option><option value="Vietnam|vn|">vietnam</option>
</select>
                     </form>
                    
	    </div><!-- end yui-g --> 
<?php

        if(sizeof($_POST) > 0 && isset($_POST['s']) && $_POST['s'] !== '' && isset($_POST['locale'])) {

                  $appID = 'Kzv_lcHV34HIybw0GjVkQNnw4AEXeyJ9Rb1gCZSGxSRNrcif_HdMT9qTE1y9LdI-';

                  $related = "<span>Related Keywords:</span>";

                  $topsource = "<span>Top Source:</span>";

                  $locale = trim($_POST['locale']);

                  list($country,$region,$lang) = split("\|",$locale);

                  $term = trim($_POST['s']);
  
                  $word = "/^[a-z|A-Z|0-9| ]+$/";

                        if(preg_match($word,$term)) {

                                $url = 'http://boss.yahooapis.com/ysearch/web/v1/'. urlencode($term). '?appid='. $appID. '&view=keyterms&format=xml&count=20&region='.$region.'&lang='.$lang;

                                $xml = get($url);

                                $k = $xml;

                                //escape new line new tab
                                $keywords = preg_replace("/\n|\r|\t/","",$k);

                                //if exists matchs then hold in matrix
                                preg_match_all("|<term>(.*)<\/term>+|U",$k,$hits);

                                //keep only unique array
                                $keywords = array_unique($hits[1]);

                                //get compontents from 0 to 19
                                $keywords = array_slice($keywords,0,28);

                                //begin lists
                                $listkeywords = '<ul id="related">';

                                foreach($keywords as $k=>$v) {

                                        $listkeywords .='<li>'.$v.'</li>';
                                }

                                $listkeywords .= '</ul>';

                                $x = simplexml_load_string($xml);

                                $output = '<ul id="topsource">'; 

                                //display titles and urls
                                foreach($x->resultset_web->result as $r) {

                                   $title = preg_replace("/<b>|<\/b>/","",$r->title);

                                   $clickurl = $r->clickurl;

                                   $output .= '<li><a href="'.$clickurl.'">'.html_entity_decode($title).'</a></li>';
                                }

                                $output .= '</ul>';


?>
	<div class="yui-g"> 
	    <div class="yui-u first"><?php echo$related . $listkeywords; ?></div> 
	    <div class="yui-u"><?php  echo$topsource . $output; ?></div> 
	</div><!-- end yui-g -->

<?php
                       //end preg_match
                       } else {

                                echo'<div class="yui-g">Illegal term</div>';
 
                       }//end-else-if

        }//endif

?>
    </div><!-- end bd --> 
               <div id="ft" role="contentinfo"><p>&copy; 2009 Created By <a href="http://thinkphp.ro">Adrian Statescu.</a> Based on YUI and BOSS</a></p></div> 
	</div><!--end doc -->
    </body> 
</html> 


<?php
     //using cURL
     function get($url) {

           $ch = curl_init();

           curl_setopt($ch,CURLOPT_URL,$url);  

           curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

           curl_setopt($ch,CONNECTTIMEOUT,2);

           $data = curl_exec($ch);

           $data = preg_replace("/\t+|\n|\r/",'',$data);

        return $data;
 
     }//end function

?>