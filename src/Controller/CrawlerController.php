<?php

namespace App\Controller;
use App\Components\tools;
use DOMDocument;
use http\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Crawl;

class CrawlerController extends DefaultController
{

    /**
     * @Route("/", name="crawler")
     */
    public function index($news = "", $result = "", $result_title = "", $news_title = "")
    {
        return $this->render('crawler/index.html.twig', [
            'controller_name' => 'CrawlerController',
            'news' => $news,
            'result' => $result,
            'result_title' => $result_title,
            'news_title' => $news_title
        ]);
    }
    /**
     * @Route("/search", name="search")
     */
    public function search()
    {
        $news = "";
        $result = "";
        $result_title = "";
        $news_title = "";
        if(isset($_POST['url']) && isset($_POST['depth'])) {
            $url = $_POST['url'];
            $depth = $_POST['depth'];
            $result = $this->crawl_page($url, $depth);
            $news_arr = $this->mapSavedUrls($result);
            $result = Tools::formatHtmlList($result);
            $news = Tools::formatHtmlList($news_arr['news']);
            $news_title = $news_arr['news_title'];
            $result_title = 'Result:';
        }

        return $this->index($news, $result, $result_title, $news_title);
//        return $this->render('crawler/index.html.twig', [
//            'controller_name' => 'CrawlerController',
//            'news' => $news,
//            'news_title' => $news_title,
//            'result' => $result,
//            'result_title' => $result_title
//        ]);
    }
    private function mapSavedUrls($array){
        $repeat = 0;
        $news = [];
         $data = Crawl::arrayMapperIdUrl($this->getDoctrine()->getRepository(Crawl::class)->findAll(), 'id', 'url');
         foreach ($array as $url){
             if(!in_array($url, $data)){
                 $this->createCrawl($url);
                 $news[] = $url;
             }else{
                 $repeat++;
             }
         }
         $news_info = (count($news) > 0) ? ', News: '.count($news) : '';
         return ['news_title' => 'Repeated total Urls: '.$repeat.$news_info, 'news' =>$news];
    }
    private function createCrawl($url){
        $obj = new Crawl();
        $obj->setUrl($url);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($obj);
        $entityManager->flush();
    }
    private function crawl_page($url, $depth, $urls = [])
    {
        ini_set("memory_limit",-1);
        libxml_use_internal_errors( true );
        if(!in_array($url, $urls)) { $urls[] = $url;}
        if ($depth <= 0) { return $urls; }
        $web_page =  $this->_getHTMLContent($url);
        $doc = new DOMDocument();
        if($web_page['body'] !== "" ) {$doc->loadHTML($web_page['body']);}
            $anchors = $doc->getElementsByTagName('a');
            foreach ($anchors as $element) {
                $href = $element->getAttribute('href');
                if (is_numeric(stripos($href, 'http')) == false) {
                    $parts_url = parse_url($url);
                    $path = ($href !== '/') ? parse_url($href) : parse_url("");
                    $href = $parts_url['scheme'] . '://';
                    $href .= $parts_url['host'];
                    if (isset($path['path']) && (!isset($parts_url['path']) || $parts_url['path'] !== $path['path'])) { $href .=  $path['path']; }
                    if (!in_array($href, $urls)) { $depth--; $urls = $this->crawl_page($href, $depth, $urls); }
            }
        }
      return $urls;
    }
    private function _getHTMLContent ( $url) {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($handle);
        curl_close($handle);
        $output = (!$output) ? '' : $output;
        $ret = Array("body" => $output);
        return $ret;
    }
}
