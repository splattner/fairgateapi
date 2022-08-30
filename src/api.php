<?php

namespace splattner\fairgateAPI;

use GuzzleHttp\Client;

class FairgateAPI {

	private $fairgateURL;
	private $username;
	private $password;
	private $client;

	/**
	 * @param $fairgateURL
	 *  Fairgate Base URL
	 * @param $username
	 *  Username for Fairgate
	 * @param $password
	 *  Password for Fairgate
	 */
	public function __construct($fairgateURL, $username, $password, $validade_ssl_certs = true) {

		$this->fairgateURL = $fairgateURL;
		$this->username = $username;
		$this->password = $password;

		$this->client = new Client(['base_uri' => $this->fairgateURL, 'cookies' => true, 'verify' => $validade_ssl_certs]);

		$csrf = $this->getCSRFToken();

		$response = $this->client->request('POST', $this->fairgateURL . '/internal/login_check', [
    	'form_params' => [
			'_username' => $this->username,
			'_password' => $this->password,
			'_csrf_token' => $csrf
			]
		]);

	}


	/**
	 * Return Array of all Members in a Mailman List
	 */
	public function getContactList() {

		$response = $this->client->request('POST', $this->fairgateURL . '/backend/contact/listcontact/contact', [
			'form_params' => [
				'start' => "0",
				'length' => "500",
				'tableField' => '{"1":{"id":"3","type":"CF","club_id":"5055","name":"CF_3"},"2":{"id":"47","type":"CF","club_id":"5055","name":"CF_47"},"3":{"id":"79","type":"CF","club_id":"5055","name":"CF_79"},"4":{"id":"77","type":"CF","club_id":"5055","name":"CF_77"},"5":{"id":"86","type":"CF","club_id":"5055","name":"CF_86"},"6":{"id":"128000","type":"CF","club_id":"5055","name":"CF_128000"},"7":{"id":"2","type":"CF","club_id":"5055","name":"CF_2"},"8":{"id":"23","type":"CF","club_id":"5055","name":"CF_23"},"9":{"id":"1","type":"CF","club_id":"5055","name":"CF_1"},"10":{"id":"72","type":"CF","club_id":"5055","name":"CF_72"},"11":{"id":"4","type":"CF","club_id":"5055","name":"CF_4"},"12":{"id":"122927","type":"CF","club_id":"5055","name":"CF_122927"},"13":{"id":"122931","type":"CF","club_id":"5055","name":"CF_122931"},"14":{"id":"122928","type":"CF","club_id":"5055","name":"CF_122928"},"15":{"id":"birth_year","type":"G","club_id":"5055","name":"Gbirth_year"},"16":{"id":"age","type":"G","club_id":"5055","name":"Gage"},"17":{"id":"128787","type":"CF","club_id":"5055","name":"CF_128787"}}',
				'filterdata[contact_filer][0][entry]' => 'membership',
				'filterdata[contact_filer][0][condition]' => 'is',
				'filterdata[contact_filer][0][input1]' => '23791',
				'filterdata[contact_filer][0][connector]' => 'null',
				'filterdata[contact_filer][0][type]' => 'CM',
				'filterdata[contact_filer][0][data_type]' => 'select',
				'order[0][column]' => "1",
				'order[0][dir]' => "asc",

				]
		]);

		//$dom = new \DOMDocument;
		//$dom->loadHTML($response->getBody());

		$result = json_decode($response->getBody()->getContents());

		$data = $result->aaData;

		//print_r($data);

		$contacts = array();

		foreach ($data as $person) {
			$contact = array(
				"name" => $person->CF_23,
				"prename" => $person->CF_2,
				"address" => $person->CF_47,
				"ort" => $person->CF_77,
				"plz" => $person->CF_79,
				"phone" => $person->CF_122931,
				"mobile" => $person->CF_86,
				"email" => $person->CF_3,
				"email_parent" => $person->CF_122927,
				"gender" => $person->CF_72,
				"ahv" => $person->CF_122928,
				"birthday" => $person->CF_4,
				"schreiber" => $person->CF_128787,
				"licence" => $person->CF_128000,
				"external_id" => $person->id,
			);

			$contacts[] = $contact;

			
		}
		return $contacts;
	}


	/*
	 * Get CSRF Token for a Page
	 * @param $page
	 *  the Page you want the token for
	 */
	private function getCSRFToken() {

		$response = $this->client->request('GET', $this->fairgateURL . '/internal/signin');

		libxml_use_internal_errors(true);

		$dom = new \DOMDocument;
		$dom->loadHTML($response->getBody()->getContents());



		$form = $dom->getElementsByTagName("form")[0];

		return $form->getElementsByTagName("input")[0]->getAttribute("value");
	}

}


?>
