<?php

namespace MyShop\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class CrawlerController extends Controller
{
    private function getCarModelsByMarkValue($value)
    {
        $url = 'https://auto.ria.com/api/categories/1/marks/'.$value.'/models?langId=2';
        $jsonData = file_get_contents($url);
        $data = json_decode($jsonData, true);

        $result = [];
        foreach ($data as $d)
        {
            $result[] = $d['name'];
        }

        return $result;
    }

    public function indexAction()
    {
        $html = file_get_contents("https://auto.ria.com/");
        //dump($html);
        //die();

        $crawler = new Crawler($html);

//        $node = $crawler->filter('#post_328134 > div.post__header > h2 > a.post__title_link');
//        $crawler->filterXPath('//*[@id="post_328134"]/div[1]/h2/a[2]')
//        $node->text();
        
        $carsMarks = $crawler->filter("select#marks > option")
            ->each(function (Crawler $nodeItem) {
                $markName = $nodeItem->text();
                $res = explode("(", $markName);
                $markName = trim($res[0]);

                $value = $nodeItem->attr("value");

                return [
                    'value' => $value,
                    'name' => $markName
                ];
            });

//        unset($carsMarks[0]);
//        $carsMarks = array_values($carsMarks);
        array_splice($carsMarks, 0, 1);

        echo '<ul>';
        foreach ($carsMarks as $car)
        {
            echo '<li><b>' . $car["name"] . "</b>";
            $models = $this->getCarModelsByMarkValue($car["value"]);
            echo '<ul>';
            foreach ($models as $model) {
                echo '<li>' . $model . '</li>';
            }
            echo '</ul></li>';
        }
        echo '</ul>';

        die();
    }
}


/****
 *        $regex = '|.*?<select class="e-form selected dhide" id="marks"  name="marka_id".*?>(.*?)</select>|sui';
preg_match_all($regex, $html, $res);

foreach ($res[1] as $optionHtml)
{
$regex2 = '|.*?<option.*?>(.*?)</option>.*?|sui';
preg_match_all($regex2, $optionHtml, $res2);
dump($res2);
die();
}
 */
