<?php
namespace App\Http\Controllers;

use App\sellerratings;
use App\sellerreviews;
use App\sellerid;

require app_path()."/include/simple_html_dom.php";

use Illuminate\Support\Facades\Request;

use App\Http\Controllers\Controller;


class scrapeController extends Controller
{

	public function index()
	{
		return view('sellerwx.scrapemain');
	}

	public function scrape_fkart($name, $id, $page = 0)
	{
		$input['from'] = Request::get('fromDate');
		$input['to'] = Request::get('toDate');

		$url = "http://www.flipkart.com/seller/".$name."/".$id."?start=".$page;
		$html = file_get_html($url);
		$number = $html->find('div[class="fk-float-right bmargin5"]', 0)->plaintext;
		$num1 = explode("of", $number);
		$num2 = explode("reviews", $num1[1]);
		$out = (int)$num2[0];
		$int = ($out-10);
		$sql = array();
		$table = new sellerreviews();
		$model = new sellerratings();
		$ident = new sellerid();
		$resultRowToBeWrittenToDB = $model->newInstance();
		$idRowToBeWrittenToDB = $ident->newInstance();

		for($i = 0 ; $i < 5 ; $i++)
		{
			$asm[$i] = $html->find('li[class="fk-font-small tmargin3"]', $i)->plaintext;
			$asmw[$i] = $html->find('li[class="fk-font-small tmargin3"] span', $i)->innertext;
			$asme[$i] = explode(";", $asm[$i]);
			$results['ratings'][$i] = $asmw[$i].":".$asme[$i][1]."ratings";
		}

		$results['Seller_Name'] = $html->find('span[class="seller-page-title fk-color-title"]', 0)->innertext;
		$results['feedback_rating'] = $html->find('div[class="rating-percentage tmargin20"] span', 0)->innertext;
		$str = $html->find('div[class="avgRatingSection sellerrating"] span', 2)->innertext;
		
		$str2 = explode("on", $str);
		$result["rating"] = explode("ratings", $str2[1])[0];
		$results['rating_count'] = str_replace(',', '', $result['rating']);
		$results['5_star'] = str_replace(',', '', $asme[0][1]);
		$results['4_star'] = str_replace(',', '', $asme[1][1]);
		$results['3_star'] = str_replace(',', '', $asme[2][1]);
		$results['2_star'] = str_replace(',', '', $asme[3][1]);
		$results['1_star'] = str_replace(',', '', $asme[4][1]);
		// $html->clear();
		
		$idRowToBeWrittenToDB->seller_name = $results['Seller_Name'];
		$idRowToBeWrittenToDB->fk_seller_id = $id;
		$idRowToBeWrittenToDB->save();

		$resultRowToBeWrittenToDB->seller_id = $idRowToBeWrittenToDB->id ;
		$resultRowToBeWrittenToDB->feedback_rating = $results['feedback_rating'] ;
		$resultRowToBeWrittenToDB->rating_count = $results['rating_count'] ;
		$resultRowToBeWrittenToDB->five_star = $results['5_star'];
		$resultRowToBeWrittenToDB->four_star = $results['4_star'];
		$resultRowToBeWrittenToDB->three_star = $results['3_star'];
		$resultRowToBeWrittenToDB->two_star = $results['2_star'];
		$resultRowToBeWrittenToDB->one_star = $results['1_star'];

		$resultRowToBeWrittenToDB->save();

		$page_number = 1;

		while($page <= $int)
		{
			$k = 0;
			$url = "http://www.flipkart.com/seller/".$name."/".$id."?start=".$page;
			$html = file_get_html($url);
			$count = $html->find('div[class="line stretchable"]');
			foreach($count as $element)
			{
				$k++;
			}
		    for($j = 0 ; $j < $k ; $j++)
		    {
				
		 		$buyer[$j] = $html->find('div[class="line stretchable"]', $j)->children(0)->children(1)->children(0);
		 		$date[$j] = $html->find('div[class="line stretchable"]', $j)->children(0)->children(2);
		 		if(!$buyer[$j]) {
		 			$buyer[$j] = 'NOBUYER';
		 			$date[$j] = $html->find('div[class="line stretchable"]', $j)->children(0)->children(1);
		 		} else {
		 			$buyer[$j] = $buyer[$j]->plaintext;
		 		}

				if(!$date[$j]) {
		 			$date[$j] = 'NODATE';
		 		} else {
		 			$date[$j] = $date[$j]->plaintext;
		 		}

				$rev[$j] = $html->find('div[class="line stretchable"]', $j)->children(1)->children(0);

				if(!$rev[$j]) {
		 			$rev[$j] = 'NOREV';
		 		} else {
		 			$rev[$j] = $rev[$j]->plaintext;
		 		}

				$rev[$j] = preg_replace('[\r\n] ', '', $rev[$j]);

				$reviewdate[$j] = str_replace(',', '', $date[$j]);
				$ymd[$j] = strtotime($reviewdate[$j]);
				$din[$j] = date('Y-m-d', $ymd[$j]);

				
				$results['user_reviews'][$page_number][$j] = $buyer[$j]." On ".$date[$j].":".$rev[$j];

				$stringToHash = (string)$results['Seller_Name'] . (string)$buyer[$j] . (string)$rev[$j];
				$hasher[$page_number][$j] = hash('md2' , $stringToHash);

				$reviewRowToBeWrittenToDB = $table->where('hash_id', '=', $hasher[$page_number][$j])
												  ->first();

				if(!$reviewRowToBeWrittenToDB) {
					$reviewRowToBeWrittenToDB = $table->newInstance();
				}

				$reviewRowToBeWrittenToDB->buyer = $buyer[$j];
				$reviewRowToBeWrittenToDB->seller_id = $resultRowToBeWrittenToDB->id;
				$reviewRowToBeWrittenToDB->date = $din[$j];
				$reviewRowToBeWrittenToDB->review = $rev[$j];
				$reviewRowToBeWrittenToDB->hash_id = $hasher[$page_number][$j];

				$reviewRowToBeWrittenToDB->save();
		    }

			$page = $page + 10;
			$page_number ++;

		}
		
		$users['ratings'] = $resultRowToBeWrittenToDB;
		$users['reviews'] = $reviewRowToBeWrittenToDB->where('date', '>=', $input['from'])
													->where('date', '<=', $input['to'])
													->get()
													->toArray();

		$filename = tempnam('/tmp', date('Y_m_d')) . '.csv';
        $fp = fopen('php://output', 'w');
        

        $headers_of_file = [
            'buyer',
            'date',
            'review'
        ];
        
        $newData = [
        	0 =>[],
        	1 => [],
        	2 => [
        		'Seller Name :' . $results['Seller_Name']
        	],
        	3 => [
        		'5 Stars :' . $results['5_star']
        	],
        	4 => [
        		'4 Stars :' . $results['4_star']
        	],
        	5 => [
        		'3 Stars :' . $results['3_star']
        	],
        	6 => [
        		'2 Stars :' . $results['2_star']
        	],
        	7 => [
        		'1 Stars :' . $results['1_star']
        	],
        	8 => [
        		'Rating Count :' . $results['rating_count']
        	],
        	9 => [
        		'Feedback Rating :' . $results['feedback_rating']
        	]
        ];


        fputcsv($fp, $headers_of_file);

        foreach ($users['reviews'] as $dataToSlash) {
            $dataToSend = array_only($dataToSlash, $headers_of_file);
            fputcsv($fp, $dataToSend);
        }

        foreach ($newData as $dataToWrite) {
        	fputcsv($fp, $dataToWrite);
        }

        header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=user_review.csv");
    	header("Content-Transfer-Encoding: binary");
        
        fclose($fp);

	}

	public function scrape_amazon($id)
	{
		$url = "http://www.amazon.in/gp/aag/main?seller=".$id;
		$html = file_get_html($url);

		$results['sellername'] = $html->find('div[class="aagMainPage"] h1', 0)->innertext;
		$results['feedback_rating'] = $html->find('div[class="feedbackMeanRating"] b', 0)->innertext;
		$results['rating_count'] = $html->find('div[class="feedbackMeanRating"] b', 1)->innertext;

		$str1   = "http://www.amazon.in/s?merchant=";
		$url2   = $str1.$id;

		$html2 = file_get_html($url2);

		$str2  = $html2->find('div[class="s-first-column"] h2', 0)->innertext;

		$str3  = explode("of", $str2);

		$results["total_products"] = $str4 = explode("results", $str3[1])[0];

		$html->clear();
		unset($html);

		return $results; 
	}

	public function scrape_sdeal()
	{
		
	}
    
}