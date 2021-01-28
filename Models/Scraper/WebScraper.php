<?php

/**
 * Resources:
 * - https://github.com/adavijit/AVDOJO-TUTORIALS/blob/master/webcrawling_php.php
 * 
 * Limitations:
 * - Shopify sites
 * - Redirect links (ie levogirar.com -> levogirar.github.io)
 */

$url = 'wuwana.com'; // Target website
$url = 'http://' . parse_url($url, PHP_URL_HOST); // Find the base url

$bodyTexts = getBodyTexts($url);
print_r(getEmails($bodyTexts));



/**
 * Get the website homepage
 * @param string $url Website URL
 * @return string $bodyTexts Text between <body></body> HTML
 */
function getBodyTexts($url)
{
	$bodyTexts = ''; // String of the website used to find phone and email
	$pages = ['', 'contacto', 'contact']; // What pages to scrape
	foreach ($pages as &$page) {
		$url = $url . '/' . $page;
		$file_headers = @get_headers($url);
		// Verify if it is a valid website
		if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
			// do nothing
		} else {
			$html = file_get_contents($url);
			$dom = new DOMDocument();
			@$dom->loadHTML($html);
			foreach ($dom->getElementsByTagName('body') as $body) { // Get the body text and add it as a string
				$bodyTexts = $bodyTexts . $body->textContent;
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
 */
function getEmails($texts)
{
	// Pattern By Eugene Kudashev
	// https://anchor.fm/slashcircumflex/episodes/a-zA-Z0-9-_-a-zA-Z0-9---a-zA-Z2-6-enq826

	$patternEmail = '/[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,6}/';
	preg_match_all($patternEmail, $texts, $emails);
	$emails = array_values(array_unique($emails)); // Remove duplicate emails
	return $emails;
}

/**
 * Find Spanish phone numbers
 * @param string $texts
 * @return array $mobilesES
 * @return array $phonesES
 * 
 * Resource:
 * https://en.wikipedia.org/wiki/Telephone_numbers_in_Spain
 * Country calling code: +34 or 0034
 * Number of digits: 9 digits
 */
function getPhoneES($texts)
{
	$callingCodeES = '+34';
	$callingCodeES2 = "0034";
	$patternNumberES = '/(?:([+]|00)34(.)?)?(6|9)[0-9]{1,}.[0-9]{1,}.[0-9]{1,}.[0-9]{1,}(.[0-9]{1,})?/'; // Look for a Spanish phone number

	preg_match_all($patternNumberES, $texts, $numbersES);
	$eliminate = ['.', ' ', '-']; // Characters to be replaced
	$numbersES = implode(';', str_replace($eliminate, '', $numbersES[0]));

	// Sort mobile and landline
	$patternMobileES = '/(?:([+]|00)34)?(6|7)[0-9]{8}/'; // Verify Spanish mobile number
	$patternPhoneES = '/(?:([+]|00)34)?9[0-9]{8}/'; // Verify Spanish landline number

	preg_match_all($patternMobileES, $numbersES, $mobilesES);
	preg_match_all($patternPhoneES, $numbersES, $phonesES);

	$mobilesES = $mobilesES[0];
	$phonesES = $phonesES[0];

	foreach ($mobilesES as &$mobile) {
		if (substr($mobile, 0, 3) === $callingCodeES) {
			$mobile = $mobile;
		} elseif (substr($mobile, 0, 4) === $callingCodeES2) {
			$mobile = str_replace($callingCodeES2, $callingCodeES, substr($mobile, 0, 4)) . substr($mobile, 4, 9); // Change 0034612345678 to +34612345678
		} else {
			$mobile = $callingCodeES . $mobile; // Add +34 to mobile number
		}
	}
	foreach ($phonesES as &$phone) {
		if (substr($phone, 0, 3) === $callingCodeES) {
			$phone = $phone;
		} elseif (substr($phone, 0, 4) === $callingCodeES2) {
			$phone = str_replace($callingCodeES2, $callingCodeES, substr($phone, 0, 4)) . substr($phone, 4, 9); // Change 0034912345678 to +34912345678
		} else {
			$phone = $callingCodeES . $phone; // Add +34 to landline number
		}
	}

	// Remove duplicate numbers
	$mobilesES = array_values(array_unique($mobilesES));
	$phonesES = array_values(array_unique($phonesES));
}
