<?php
     require_once('./keys.php');

     $search = isset($_GET['s']) ? urlencode($_GET['s']) : 'covent+garden';

     $pattern = "/^[a-z|A-Z|0-9| |-|+]+$/";

     $label = "<h2>Top keywords for <strong>title</strong></h2>";

     $error = 0;

     if(preg_match($pattern,$search)) {

                $news = "http://boss.yahooapis.com/ysearch/news/v1/$search?count=5&format=json&amp;lang=en&mp;region=uk&appid=$appID";

                $images = "http://boss.yahooapis.com/ysearch/images/v1/$search?count=4&format=json&amp;lang=en&mp;region=uk&appid=$appID";

                $guardian = "http://boss.yahooapis.com/ysearch/web/v1/$search?count=7&format=json&amp;lang=en&mp;region=uk&appid=$appID&view=keyterms&sites=guardian.co.uk";

                $age = date('Ymd',time()-(30*24*60*60));

                $terms = array();
 
        //grab Images from Web with BOSS
        $images = get($images);  

        $images = json_decode($images);

        $imagesresults = '<ul id="images">';

               if($images->ysearchresponse->resultset_images) {
 
                     foreach($images->ysearchresponse->resultset_images as $n){

                              $imagesresults.= '<li><h4>'.$n->title.'</h4><a href="'.$n->clickurl.'"><img src="'.$n->thumbnail_url.'"></a><p><a href="'.$n->refererclickurl.'">'.substr($n->refererurl,0,40).'</a></p></li>';

                     }//enforeach

                }//endif

        $imagesresults .= '</ul>';


        //grab articles News with BOSS from Web
        $news = json_decode(get($news));

            $bossresults = '';

               if($news->ysearchresponse->resultset_news) {

                     foreach($news->ysearchresponse->resultset_news as $n){

                              $bossresults .= '<li><h3><a href="'.$n->clickurl.'">'.$n->title.'</a></h3><p class="byline">'.$n->date.' - '.$n->time.' (<a href="'.$n->sourceurl.'">'.$n->source.'</a>)</p><p>'.$n->abstract.'</p></li>';

                                    /* Nested YQL query to retrieve keywords NOT in news Search ...only in web search :)*/   
 
                                     $root = 'http://query.yahooapis.com/v1/public/yql?q=';

                                     $url = 'http://boss.yahooapis.com/ysearch/web/v1/'.$search.'?format=xml&amp;lang=en&mp;region=uk&appid=Kzv_lcHV34HIybw0GjVkQNnw4AEXeyJ9Rb1gCZSGxSRNrcif-HdMT9qTE1y9LdI-&view=keyterms&sites='. urlencode($n->url);

                                     $yql = 'select resultset_web.result.keyterms from xml where url="'.$url.'" limit 1';

                                     $query = $root . urlencode($yql) . '&diagnostics=false&format=json';

                                     $keyterms = json_decode(get($query));


                               if($keyterms->query->results) {
 
                                         foreach($keyterms->query->results->ysearchresponse->resultset_web->result->keyterms->terms as $t) {

                                                $bossresults .= '<p class="keywords"><strong>Keywords: </strong>'.implode(', ',$t).'</p>';
                    
                                                $terms = array_merge($terms,$t); 

                                         }//endforeach

                               }//endif


                     }//endforeach


               }//endif

        


       //grab articles from Guardian
         $articles = get($guardian);

         $articles = json_decode($articles);

             $guardresults = '<ul id="guardresults">';

               if($articles->ysearchresponse->resultset_web) {

                     foreach($articles->ysearchresponse->resultset_web as $a){

                              $guardresults .= '<li><h3><a href="'.$a->clickurl.'">'.$a->title.'</a></h3><p class="byline">'.$a->date.'</p><p>'.$a->abstract.'</p></li>';

                                            $k = $a->keyterms->terms;

                                                $guardresults .= '<p class="keywords"><strong>Keywords: </strong>'.implode(', ',$k).'</p>';


                     }//endforeach

                }//endif

             $guardresults .= '<ul id="guardresults">';



      //create list of most used keywords
        sort($terms); 
   
        $count = array_count_values($terms);

        natsort($count);
 
        $count = array_reverse($count);

        $stringout = array();

        foreach(array_keys($count) as $a){

                    $stringout[] = '<li><a href="index.php?s='.decode($a).'">'.decode($a).'</a></li>';

                    $listout[] = decode($a);
        }

        $keyterms = '<ol id="tags">'.join('',array_slice($stringout,0,20)).'</ol>';

     } else {

            $error = 1;
     }

?>





<?php


function get($url){

  $ch = curl_init(); 

  curl_setopt($ch, CURLOPT_URL, $url); 

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

  $output = curl_exec($ch); 

  curl_close($ch);

         if(empty($output)) {return "Server Timeout.";}
 
                      else { return $output;}
}



function decode($x){

  $x = htmlentities($x);

  preg_match_all('/u([a-f|0-9]{4})/msi',$x,$mal);

  foreach($mal[0] as $s){

    $x = str_replace($s,'&#'.hexdec($s).';',$x);

  }

  return stripslashes($x);
}

?>
