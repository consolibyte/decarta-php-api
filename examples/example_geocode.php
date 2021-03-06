<?php

/**
 * Example of geo-coding an address (convert address to lat/long) using the Decarta API (http://api.decarta.com/)
 *
 * @author Keith Palmer <keith@consolibyte.com>
 */

require_once dirname(__FILE__) . '/../Decarta.php';

$api_key = '0e396b9ab3da3b31ded2d3743a3be5b8';

$Decarta = new Decarta($api_key);

$str = '1066 Storrs Road
Storrs CT 06268';

$results = $Decarta->geocode($str);

foreach ($results as $result)
{
	print_r($result);
}