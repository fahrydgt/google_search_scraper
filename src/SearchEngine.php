<?php

namespace FlScrapper\GoogleScraperPackage;

include('simple_html_dom.php');

class SearchEngine
{
    private $search_engine = "";

    public function setEngine($engine="google.com")
    {
        $this->search_engine = $engine;
        echo "Search sngine set to: ".$this->search_engine;
    }

    public function search($keywords=[])
    {
        $query = "";
        if(!empty($keywords)){
            $query = "&q=".implode("+", $keywords);
        }else{
            throw("Seacrh parameter required!");
            return false;
        }
        
        $search_url = "https://".$this->search_engine."/search?num=50&h1=en".$query;
        echo "<br>query string is".$search_url;
                
        $result = $this->file_get_contents_curl($search_url);

        $domResult = new simple_html_dom();
        $domResult->load($result);

        $search_data =array();
        
        $prev_url = "";
        $i=0;
        foreach($domResult->find('a[href^=/url?]') as $link){

            // List first 50 resuts only (For clean up te crawled data)
            if($i==50)
                break;

            // Clean url to return on array
            $url = explode('=',explode('&',$link->href)[0])[1];

            // Prevent same result quick links as record
            if($prev_url!=""){
                if(strpos($link->href, $prev_url) !== false)
                    continue;
            }

            $meta_data = get_meta_info($url);

            // Construct final array
            $search_data[] = array(
                'keyword' => isset($meta_data['keywords'])? $meta_data['keywords']:"",
                'ranking' => $i,
                'url' => $url,
                'title' => $link->plaintext,
                'description' => isset($meta_data['description'])?$meta_data['description']:"",
                'promoted' => 1,
            );
            $prev_url=$url;
            $i++;
            break;
        }

        return $search_data;
    }

    // Use PHP native curl funtion for http req/res
    function file_get_contents_curl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    // Function to crawl meta data of each invidual page
    function get_meta_info($url){

        $html = $this->file_get_contents_curl($url);
        
        // Parses the HTML contained in the string source
        $doc = new DOMDocument();
        @$doc->loadHTML($html); 
        $metas = $doc->getElementsByTagName('meta');
        
        // Construct array for required meta data
        $meta_array = array();
        for ($i = 0; $i < $metas->length; $i++)
        {
            $meta = $metas->item($i);
            if($meta->getAttribute('name') == 'description')
                $meta_array['description'] = $meta->getAttribute('content');
            if($meta->getAttribute('name') == 'keywords')
                $meta_array['keywords'] = $meta->getAttribute('content');
        }

        return $meta_array;
    }
}