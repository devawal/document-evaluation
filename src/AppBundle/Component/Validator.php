<?php
namespace AppBundle\Component;

use Symfony\Component\Cache\Simple\FilesystemCache;
use AppBundle\Services\Helpers;

class Validator
{	
	/**
	 * Get information data for validation
	 *
	 * @param array $data
	 * @param array $formatData
	 * @return string
	 */
    public static function getInformationData($data, $formatData)
    {
    	// Check if there is any blank rows
    	if (count($data) > 0) {
    		// Make all country code in lowercase
    		$countryCode = strtolower($data[1]);
    		// Call helper class for getting number of years using two dates
    		$dateDiff = Helpers::dateInterval(date('Y-m-d'), $data[4]);

    		// 1. check if document_type_is_invalid
    		if ($countryCode == 'uk' && ($data[4] < '2019-01-01')) {
    			if ($data[2] != 'passport') {
    				return 'document_type_is_invalid';
    			}
    		}

    		// 2. Check if document_is_expired
			if ($countryCode == 'de' && $dateDiff['y'] >= 10 || $countryCode == 'es' && $dateDiff['y'] >= 15) {
				if ($dateDiff['m'] > 0 || $dateDiff['d'] > 0) {
					return 'document_is_expired';
				}
	    	} elseif ($countryCode != 'de' && $countryCode != 'es' && $dateDiff['y'] >= 5) {
	    		if ($dateDiff['m'] > 0 || $dateDiff['d'] > 0) {
	    			return 'document_is_expired';
	    		}
	    	}

	    	// 3. Check if document_number_length_invalid
	    	$document_length = strlen($data[3]);
	    	if ($document_length != 8) {
	    		if ($countryCode == 'pl' && ($data[4] >= '2018-09-01') && $document_length == 10) {
	    			return 'valid';
	    		} else {
	    			return 'document_number_length_invalid';
	    		}
	    	}

	    	// 4. Check if document_number_invalid
	    	if ($countryCode == 'es' && in_array($data[3], range(50001111,50009999))) {
	    		return 'document_number_invalid';
	    	}

	    	// 5. Check if document_issue_date_invalid
	    	$nameOfDay = date('D', strtotime($data[4]));
	    	if ($countryCode == 'it' && $nameOfDay == 'Sat') {
	    		if ($data[4] >= '2019-01-01' && $data[4] <= '2019-01-31') {
	    			return 'valid';
	    		} else {
	    			return 'document_issue_date_invalid';
	    		}
	    	} elseif ($countryCode != 'it') {
	    		if ($nameOfDay == 'Sat' || $nameOfDay == 'Sun') {
	    			return 'document_issue_date_invalid';
	    		}
	    	}

	    	// 6. Check if request_limit_exceeded
	    	$cache = new FilesystemCache();
	    	$getCD = $cache->get('stats.'.$countryCode);
	    	$getCD = 1+$getCD;
	    	$cache->set('stats.'.$countryCode, $getCD);
	    	// Check more than two date exist for single client
	    	if ($getCD > 2 && isset($formatData[2])) {
	    		// Compare date 
	    		$getDate = Helpers::dateInterval($formatData[2], $formatData[0]);
	    		// Allow request per 5 working days
	    		if ($getDate['days'] <= 5) {
	    			return 'request_limit_exceeded';
	    		}
	    	}
    	}
    }
}