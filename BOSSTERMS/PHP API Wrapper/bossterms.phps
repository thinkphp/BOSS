<?php

     if(sizeof($_GET) !== 0) {

        $appID = 'Kzv_lcHV34HIybw0GjVkQNnw4AEXeyJ9Rb1gCZSGxSRNrcif_HdMT9qTE1y9LdI-';

               if(!isset($_GET['format'])) {

                   $format = 'json';  

               } else {

                   $format = $_GET['format'];
               }

               if($format === 'json') {

                       header('content-type: application/javascript');

               } else if($format === 'xml') {

                       header('content-type: text/xml');
               } 

               $term = $_GET['term'];

               $out = '';

               $callback = $_GET['callback'];

               $word = "/^[a-z|A-Z| ]+$/";

                          if(preg_match($word,$term)) {

                               $url = 'http://boss.yahooapis.com/ysearch/web/v1/'. urlencode($term). '?appid='. $appID. '&view=keyterms&format=xml';

                               $terms = get($url);

                               $vec = array();

                               $vec_xml = array();

                               foreach($terms as $te) {

                                     array_push($vec_xml,$te);

                               }//end-foreach
 
                               foreach($terms as $t) {

                                     array_push($vec,'"'.$t.'"');

                               }//end-foreach

                               $outHTML = '<ul><li>'. join($terms,"</li><li>") . '</li></ul>';

                               $outJSON = '{"term":"'.$term.'","keyterms":['.implode($vec,",").'],"html":"'.$outHTML.'"}';
                                                      
                                  if(isset($callback) && $outJSON !== '') {  
      
                                      if(preg_match("/[a-z|A-Z|_|-|\$|0-9||\.]/",$_GET['callback'])) {

                                                   $outJSON = $callback.'('.$outJSON.')';

                                      }//end-preg match

                                  }//end-if

                               $outXML = '<?xml version="1.0" encoding="UTF-8"?>';

                               $outXML .='<searchterms>';

                               foreach($vec_xml as $v) {

                                      $outXML .='<term>'.$v.'</term>';
                               }

                               $outXML .='</searchterms>';

                               if($format === 'xml') {

                                       echo$outXML;

                               } else if($format === 'json') {

                                       echo$outJSON;                                      

                               } else {

                                       echo$outHTML; 
                               }

                          }//end-if

     } else {
?>

     <pre>

      bossterms API
      -------------

      This small API allows you to get keywords connected 
      with a search term using Yahoo`s BOSS API.
      You have several REST parameters:

          term:     the search term (needd to be words and spaces, everything is filtered out)
          format:   json, xml and html if the format isn`t set then format is json
          callback: the name of a javascript method to wrap the JSON return in JSON-P. 
    
       The JSON output has the following properties:

          term:     Your search term
          keyterms: An array of keywords
          html:     An array as an HTML list 

          {"term":"...","keyterms":[t1,t2,t3,t4...],"html":"&lt;ul&gt;&lt;li&gt;...&lt;/li&gt;&lt;li&gt;...&lt;/li&gt;&lt;li&gt;...&lt;/li&gt;&lt;/ul&gt;"} 

       The XML output has the following form:

         &lt;searchterms&gt;
              &lt;term&gt;...&lt;/term&gt;
              &lt;term&gt;...&lt;/term&gt;
              &lt;term&gt;...&lt;/term&gt;
              (...) 
         &lt;/searchterms&gt;

       The HTML output has the following form:

          &lt;ul&gt;
              &lt;li&gt;...&lt;/li&gt;
              &lt;li&gt;...&lt;/li&gt;
              &lt;li&gt;...&lt;/li&gt;
              (...) 
          &lt;/ul&gt;
         

     </pre> 


<?php
     }

     //using cURL
     function get($url) {

           $ch = curl_init();

           curl_setopt($ch,CURLOPT_URL,$url);  

           curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

           curl_setopt($ch,CONNECTTIMEOUT,2);

           $data = curl_exec($ch);

           $data = preg_replace("/\t+|\n|\r/",'',$data);

           preg_match_all("|<term>(.*)<\/term>+|U",$data,$hits);

       return array_unique($hits[1]);

     }//end function
   
?>


