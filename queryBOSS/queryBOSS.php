<?php

     if($appID === '') {

              echo'<h2>No application ID found. get an ID and edit the configuration file!</h2>';  

     } else {

           //predefined start as zero and overwrite if there is a start defined
           //make sure that the start property is numeric and nothing else
             $start = 0;

             if(isset($_GET['start']) && preg_match("/[0-9]+/",$_GET['start'])) {

                      $start = $_GET['start'];
             }      

          //get the search term and define a filter mechanism for illegal terms.
          $term = $_GET['s'];

          $word = "/^[a-z|A-Z|0-9]| |-|\"]+$/";

                //if a term is defined
                if(isset($term) && $term !== ''){

                         //if the term has illegal characters then display an error message
                         if(!preg_match($word,$term)) {

                                  echo'<h2>'.$badtermmessage.'</h2>';

                         //othewise let s go for it                         
                         } else {

                             //assemble the BOSS API call URL end GET XML version of data

                             $url = 'http://boss.yahooapis.com/ysearch/images/v1/'. urlencode($term) .'?appid='.$appID.'&format=xml&start='. $start .'&count=20'; 

                             $xml = get($url);
 
                             $x = simplexml_load_string($xml);

                             //get the amount of results
                             $all = $x->resultset_images['totalhits'];

                                if($all > 0) {

                                    //display the results messages and replace the placeholders 
                                    $resultsmessage = str_replace('$start',$start+1,$resultsmessage);

                                    $end = ( $all > $start+ 20 ) ? $start + 20 : $all;

                                    $resultsmessage = str_replace('$end',$end,$resultsmessage);  

                                    $resultsmessage = str_replace('$all',$all,$resultsmessage);

                                    echo"<div id='resultcounter'>".$resultsmessage."<b>".$term."</b></div>";


                                    //display the images in the table
                                    echo'<table id="results">';

                                    $k = 0;
 
                                   //loop over the results and display the rights data
                                     foreach($x->resultset_images->result as $r) {
                                 
                                                 if($k%5 == 0) echo"<tr><td>"; else {echo"<td>";}
                                         
                                                 $shortener = $r->refererurl;

                                                 $shortener = substr($shortener,0,20);

                                                 $abstract = substr($r->title,0,20);

                                                 $size = intVal($r->size/1024) . 'K';

                                                 $height = $r->height;

                                                 $width = $r->width;        

                                                 $format = $height .' X '.$width . ' | '.$size;

                                                 echo'<a href="'.$r->url.'" title="'.$r->url.'"><img src="'.$r->thumbnail_url.'" width="'.$r->thumbnail_width.'" height="'.$r->thumbnail_height.'"></a><br/><span style="color: #222">'.$abstract.'</span><br/><span style="color: #222">'.$format.'</span><br/><span>'.$shortener.'</span></td>';

                                                 if($k == 4) echo"</tr>";

                                                 $k++;

                                     }//end loop fetch

                                     echo'</tr></table>';


                      ///pagination list

                                  //if there are a previous link or a next link

                                    if($x->nextpage || $x->prevpage) {

                                           echo"<ul id='nav'>"; 
                                    }                      


                                    if($x->prevpage) {

                                         $start = split("=",$x->prevpage);

                                         echo"<li class='left'><a href='".$filename."?s=".$term."&start=".$start[sizeof($start)-1]."'>".$previouslabel."</a>";

                                    }


                                    if($x->nextpage) {
             
                                         $start = split("=",$x->nextpage);

                                         echo"<li class='right'><a href='".$filename."?s=".$term."&start=".$start[sizeof($start)-1]."'>".$nextlabel."</a>";
                                    }


                                    if($x->nextpage || $x->prevpage) {

                                           echo"</ul>"; 
                                    }                      



                                 //othewise there is no results then display the message!
                                 } else {

                                      echo '<p id="resultcounter">'.$noresultsmessage.'</p>';
                                 }   

?> 
        <form action="<?php echo$filename;?>" method="get" accept-charset="utf-8">

             <p><label for="s">Search for:</label><input type="text" id="s" name="s" value="<?php if(isset($_GET['s'])) {echo$_GET['s'];}?>" size="40"><input type="submit" value="Images Search"></p>

        </form>
<?php

                         }//end preg-match 
                         

                }//end isset term


     }//end-if $appID    


     //cURL
     function get($url) {

             $ch = curl_init();

             curl_setopt($ch,CURLOPT_URL,$url);

             curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2); 

             curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

             $data = curl_exec($ch);

             curl_close($ch);
             
             if(empty($data)) {return "Server Timeout.";}

                        else

                              {return $data;}   
     } 
   



?>