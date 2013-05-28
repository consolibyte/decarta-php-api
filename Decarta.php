<?php

/**
 * Decarta API class (http://api.decarta.com/)
 *
 * @author Keith Palmer <keith@consolibyte.com>
 */

class Decarta
{
	protected $_api_key;

	const TYPE_GEOCODE = 'geocode';
	const TYPE_SEARCH = 'search';

	const ERROR_OK = 200;
	const ERROR_UNAUTHORIZED = 401;
	const ERROR_SERVER = 500;
	const ERROR_TEAPOT = 418;

	const ENDPOINT = 'http://api.decarta.com/v1/[KEY]/[TYPE]/[SEARCH].json';

	public function __construct($api_key)
	{
		$this->_api_key = $api_key;
	}

	protected function _getEndpoint($type, $str, $params = array())
	{
		$url = str_replace(
			array(
				'[TYPE]', 
				'[KEY]',
				'[SEARCH]'
				), 
			array(
				$type, 
				$this->_api_key, 
				urlencode($str), 
				), 
			Decarta::ENDPOINT);

		if (is_array($params) and 
			count($params))
		{
			$url .= '?' . http_build_query($params);
		}

		return $url;
	}

	public function lastRequest()
	{
		return $this->_last_request;
	}

	public function lastResponse()
	{
		return $this->_last_response;
	}

	public function lastError()
	{
		return $this->_last_error;
	}

	public function search($str, $params = array())
	{
		return $this->_request(Decarta::TYPE_SEARCH, $str, $params);
	}

	public function geocode($str, $params = array())
	{
		return $this->_request(Decarta::TYPE_GEOCODE, $str, $params);
	}

	protected function _request($type, $str, $params)
	{
		$url = $this->_getEndpoint($type, $str, $params);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$this->_last_request = $url;
		$retr = curl_exec($ch);

		$info = curl_getinfo($ch);
		$this->_last_response = print_r($info, true) . "\r\n\r\n" . $retr;

		curl_close($ch);

		if ($info['http_code'] == Decarta::ERROR_OK)
		{
			$this->_last_error = Decarta::ERROR_OK;
			$obj = json_decode($retr);

			return $obj->results;
		}

		$this->_last_error = $info['http_code'];
		return false;
	}
}