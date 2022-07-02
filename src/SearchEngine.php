<?php

namespace FlScrapper\GoogleScraperPackage;

class SearchEngine
{
    private $search_engine = "";

    public function setEngine($engine="google.com")
    {
        $this->search_engine = $engine;
        echo "Search sngine set to ";
    }

    public function search($keywords=[])
    {
        $query = "";
        if(!empty($keywords)){
            $query = implode("+", $keywords);
        }
        echo "query string is".$query;
    }
}