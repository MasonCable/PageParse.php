<?php

namespace mcable\PageParse;

use mcable\PageParse\Format;

class PageParse
{
    public function __construct($url)
    {
        $this->pageContents = file_get_contents($url);
        
    }

    

    public function page_data(){
        $page = $this->pageContents;
        $pageWithNoScript = $this->delete_all_between('<script', '</script>', $page);
        $pageWithNoCss = $this->delete_all_between('<style', '</style>', $pageWithNoScript);
        $finalPage = $this->pretty_page($pageWithNoCss);

        return $pageWithNoCss;
        
    }

    private function delete_all_between($beginning, $end, $string)
    {
        $beginningPos = strpos($string, $beginning);
        $tmpstring = substr($string, $beginningPos);  
        $endPos = strpos($tmpstring, $end);
        if ($beginningPos === false || $endPos === false) {
            return $string;
        }
        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) );
        return $this->delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
    }

    private function pretty_page($html)
    {
        $format = Format::class;

        $formatted_html = $format->HTML($html);

        return $formatted_html;
    }
    
}

$url = "https://www.omgubuntu.co.uk/2021/08/cutefish-os-ubuntu";
$pageParse = new PageParse($url);

$page = $pageParse->page_data();

echo $page;



