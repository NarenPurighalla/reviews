<?php
echo "Getting Data From URL:\n";
$url = "http://www.flipkart.com/seller/starshinestore/3zd7eet39psxijv4?start=50";
$html = file_get_contents( $url);
libxml_use_internal_errors( true);

$doc = new DOMDocument; $doc->loadHTML( $html);
$xpath = new DOMXpath( $doc);
print_r($xpath);
//get all ratings where <meta itemprop="ratingValue">
//$ratings = $xpath->query('//meta[@itemprop="ratingValue"]');

//get all headings where <h3 class="review-title en h4">
$headings = $xpath->query( '//*[@id="fk-mainbody-id"]/div/div[2]/div[1]/div[3]/div[2]/ul/li[1]/text()');

//get all content
//$node = $xpath->query( '//div[@itemprop="reviewBody"][@class="review-body"]');

get_object_vars($headings);
