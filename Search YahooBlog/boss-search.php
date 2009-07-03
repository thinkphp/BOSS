<?php

 /*
   BOSS Search by Adrian Statescu
   Version: 1.0
   Homepage: 
   Code licensed under the BSD License:
   http://thinkphp.ro/license.txt   
 */

   if($appID === ''){

      echo '<h2>No application ID found, <a href="<a href="https://developer.yahoo.com/wsregapp/"> get an ID </a>get an ID</a> and edit the configuration file!</h2>';  

   } else {

       $start = 0;

       if(isset($start) && preg_match("/[0-9]+/",$_GET['start'])) {

            $start = $_GET['start'];
       } 

      //get the search term and define a filter mechanism for illegal terms.
 
        $term = $_GET['s'];

        $word = "/^[a-z|A-Z|0-9| |-|\"]+$/"; 

                if(isset($term) && $term != '') {

                         if(!preg_match($word,$term)) {

                                  echo"<h2>".$badtermmessage."</h2>";

                            //otherwise let s go 
                         } else {

                            //assemble the BOSS API call url and get  the XML version of data
                              $url = "http://boss.yahooapis.com/ysearch/web/v1/".

                                      urlencode($term)."?appid=".$appID. "&view=keyterms&sites=". 

                                      urlencode($sites)."&format=xml&start=".$start;

                              $terms = get($url);
 
                              //convert the XML string to ab Object structure

                                $feed = new SimpleXMLElement($terms);

                                $all = $feed->resultset_web['totalhits'];

 
                                   if($all < 1) {

                                              //if there are no results message, display the no results message 
                                              echo"<p id='resultscounter'>".$noresultmessage."</p>";
                         
                                   } else {
                                   //otherwise display the results message and replace the placeholder with real data

                                          $resultsmessage = str_replace('$start',$start+1,$resultsmessage);

                                          $end = ( $all > $start + 10) ? $start + 10 : $all;

                                          $resultsmessage = str_replace('$end', $end,$resultsmessage);                          

                                          $resultsmessage = str_replace('$all',$all,$resultsmessage);

                                          echo"<p id='resultscounter'>".$resultsmessage."</p>"; 
 
                                   //display the results list                                                                                                               
      
                                     echo"<ul id='results'>";

                                           //loop over all the results and display the right data
                                             foreach($feed->resultset_web->result as $result) {

                                                  echo'<li><h2><a href="'.$result->clickurl.'">'.$result->title.'</a></h2><p>'.$result->abstract.'</p><p class="kw">'.$keywordslabel.'</p>'; 

                                                  echo'<ul class="keyterms">';  

                                                            //get the keywords for the term and display them as a nested list to allow refining the search

                                                              foreach($result->keyterms->terms->term as $keyterm) {
                
                                                                      echo'<li><a href="'.$filename.'?s='.$term.'+'.urlencode($keyterm).'">'.$keyterm.'</a></li>'; 
                                                                 
                                                              }

                                                  echo'</ul></li>';
 
                                             }//end foreach
                                
                                     echo"</ul>";





                                     //pagination list
                                       if($feed->prevpage || $feed->nextpage) {

                                            echo'<ul id="nav">';   
                                       } 


                                       //if there is a previous page then display a previous link
                                         if($feed->prevpage) {

                                            $start = split('=',$feed->prevpage);

                                            echo'<li class="left"><a href="'.$filename.'?s='.$term.'&start='.$start[sizeof($start)-1].'">'.$previouslabel.'</a></li>';

                                         }


                                       //if there is a next page then display a next link

                                         if($feed->nextpage) {

                                            $start = split('=',$feed->nextpage);

                                            echo'<li class="right"><a href="'.$filename.'?s='.$term.'&start='.$start[sizeof($start)-1].'">'.$nextlabel.'</a></li>';
                                         }


                                       if($feed->prevpage || $feed->nextpage) {

                                            echo'</ul>';   
                                       } 


                                   }//end else
                            
 
                         }//end else

                } 
   }//end else


   function get($url) {

        $handle = curl_init();

        curl_setopt($handle,CURLOPT_URL,$url);

        curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);

        curl_setopt($handle,CURLOPT_CONNECTTIMEOUT,2);

        $buffer = curl_exec($handle);


        // for some reason SIMPLEXML doesn't like cdata around the terms,
       // so let's remove it.
             $buffer = str_replace("/<term><!\[CDATA\[/",'<term>',$buffer);
             $buffer = str_replace("/\]\]><\/term>/",'</term>',$buffer);


        return $buffer;
   }


?>