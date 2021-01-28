<?php

/**
 * Resources:
 * - https://github.com/adavijit/AVDOJO-TUTORIALS/blob/master/webcrawling_php.php
 * 
 * Limitations:
 * - Shopify sites
 * - Redirect links (ie levogirar.com -> levogirar.github.io)
 */

$url = ''; // Target website
$url = 'http://' . parse_url($url, PHP_URL_HOST); // Find the base url

$bodyTexts = getBodyTexts($url);
$emails = getEmails($bodyTexts);
$numbersES = getNumbersES($bodyTexts);
$phonesES = getPhonesES($numbersES);
$mobilesES = getMobilesES($numbersES);

/**
 * Get the website homepage
 * @param string $url Website URL
 * @return string $bodyTexts Text between <body></body> in a HTML
 */
function getBodyTexts($url)
{
	$bodyTexts = ''; // Text
	$pages = ['', 'contacto', 'contact']; // What pages to scrape
	foreach ($pages as &$page) {
		$newURL = $url . '/' . $page;
		$file_headers = @get_headers($newURL);
		// Verify if it is a valid website
		if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
			// do nothing
		} else {
			$html = file_get_contents($newURL);
			$dom = new DOMDocument();
			@$dom->loadHTML($html);
			foreach ($dom->getElementsByTagName('body') as $body) { // Get the body text and add it as a string
				$bodyTexts = $bodyTexts . " " . $body->textContent;
			}
		}
		$url = 'http://' . parse_url($url, PHP_URL_HOST); // Reset the URL
	}

	return $bodyTexts;
}

/**
 * Find emails
 * @param string $texts
 * @return array $emails
 * 
 * Limitation:
 * - Does not find email inside <a>
 * 
 * TO DO:
 * - Bug: Array ( [0] => info@rightsidecoffee.comTel )
 */
function getEmails($texts)
{
	// Pattern By Eugene Kudashev
	// https://anchor.fm/slashcircumflex/episodes/a-zA-Z0-9-_-a-zA-Z0-9---a-zA-Z2-6-enq826

	$patternEmail = '/[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,6}/';
	preg_match_all($patternEmail, $texts, $emails);
	$emails = array_values(array_unique($emails)); // Remove duplicate emails

	$emails = $emails[0];

	return $emails;
}

/**
 * Find Spanish phone numbers
 * @param string 
 * @return string $numbersES
 * 
 * Resource:
 * https://en.wikipedia.org/wiki/Telephone_numbers_in_Spain
 * Country calling code: +34 or 0034
 * Number of digits: 9 digits
 */
function getNumbersES($texts)
{
	// Spain calling Code
	$callingCodeES = '+34';
	$callingCodeES2 = "0034";
	$patternNumberES = '/(?:([+]|00)34(.)?)?(6|9)[0-9]{1,}.[0-9]{1,}.[0-9]{1,}.[0-9]{1,}(.[0-9]{1,})?/'; // Look for a Spanish phone number

	// Find phone number & sanitize results
	preg_match_all($patternNumberES, $texts, $numbersES);
	$numbersES = array_values(array_unique($numbersES[0])); // Remove duplicates
	$eliminate = ['.', ' ', '-']; // Characters to be replaced
	$numbersES = str_replace($eliminate, '', $numbersES); // Remove special characters

	// Standardize mobile numbers
	foreach ($numbersES as &$number) {
		if (substr($number, 0, 3) === $callingCodeES) {
			$number = $number;
		} elseif (substr($number, 0, 4) === $callingCodeES2) {
			$number = str_replace($callingCodeES2, $callingCodeES, substr($number, 0, 4)) . substr($number, 4, 9); // Change 0034612345678 to +34612345678
		} else {
			$number = $callingCodeES . $number; // Add +34 to mobile number
		}
	}

	//Turn array into string
	$numbersES = implode('; ', $numbersES);

	return $numbersES;
}

/**
 * Find Spanish mobile numbers
 * @param string $numbersES
 * @return array $mobilesES
 */
function getMobilesES($texts)
{
	$patternMobileES = '/[+]34(6|7)[0-9]{8}/';

	preg_match_all($patternMobileES, $texts, $mobilesES); // Verify Spanish mobile number
	$mobilesES = $mobilesES[0];

	return $mobilesES;
}

/**
 * Find Spanish landline numbers
 * @param string $numbersES
 * @return array $phonesES
 */
function getPhonesES($texts)
{
	$patternPhoneES = '/[+]349[0-9]{8}/';

	preg_match_all($patternPhoneES, $texts, $phonesES); // Verify Spanish landline number
	$phonesES = $phonesES[0];

	return $phonesES;
}
