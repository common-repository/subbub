<?php
/**
 * Plugin Name:		Subbub
 * Plugin URI:		https://subbub.org/plugin
 * Description:		Buttons and shortcodes to interface to Subbub
 * Version:		1.0.7
 * Requires at least:	5.0
 * Requires PHP:	7.0
 * Author:		Jonathan Pinnock
 * Author URI:		https://jonathanpinnock.com
 * License:		GPLv3 or later
 * License URI:		https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) exit;		// Exit if accessed directly      

/**
 * Get unit selection and convert into form suitable for Subbub.
 *
 * @param	array	$atts	Incoming attributes
 *
 * @return string
 */

function subbub_getUnitSelection($atts) {
	$marketNumber = $atts['market'];
	$eventNumber = $atts['event'];
	$categoryNumber = $atts['category'];

	if ($marketNumber != null) {
		$unitSelection = 'marketNumber=' . $marketNumber;
	} else if ($eventNumber != null) {
		$unitSelection = 'eventNumber=' . $eventNumber;
	} else if ($categoryNumber != null) {
		$unitSelection = 'categoryNumber=' . $categoryNumber;
	}

	return $unitSelection;
}

/**
 * Get correct base address depending on whether testing or live.
 *
 * @param	boolean	$test	Test flag
 *
 * @return string
 */

function subbub_getBaseAddress($test) {
	if ($test == 1) {
		$address = 'http://test.subbub.org/';
	} else {
		$address = 'https://subbub.org/';
	}

	return $address;
}

/**
 * Handle short code for submitting work to a market.
 *
 * @param	array	$atts	Incoming attributes
 *
 * @return	\button
 */
 
function subbub_submit_shortcode($atts) {
	$unitSelection = subbub_getUnitSelection($atts);

	$test = $atts['test'];
	$demoUser = $atts['demouser'];
	$newWindow = $atts['newwindow'];
	$align = $atts['align'];

//	Construct return address from current address

	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
		$returnAddress = 'https://';
	} else {
		$returnAddress = 'http://';
	}

	$returnAddress .= sanitize_text_field($_SERVER['HTTP_HOST']);
	$returnAddress .= sanitize_url($_SERVER['REQUEST_URI']);

//	Construct target address for submission

	$address = subbub_getBaseAddress($test) . 'submit';

	if ($unitSelection != null) {
		$address .= ('?function=submitDirect&' . $unitSelection);
	}

	if ($demoUser != null) {
		$address .= ('&demoUser=' . $demoUser);
	}

	if ($unitSelection != null) {
		$address .= ('&returnAddress=' . $returnAddress);
	}

	if ($newWindow == 1) {
		$address .= '" target="_blank" rel="noreferrer noopener';
	}

//	Locate image for button to display to user

	$image = subbub_getBaseAddress($test);
      
	$image .= ('libs/jpa/subbub/subbub_logo.php?function=plugin&' . $unitSelection);

	if ($test == 1) {
		$image .= ('&random=' . uniqid());
	}

	$style = 'display:inline-block;';

	if ($align != null) {
		$style = 'display:block; margin: 0 auto;';
	}

//	Construct button and return to user

	$button = '<a href="' . $address . '" style="' . $style . 'border-style:solid;border-radius:5px;border-color:white;fill:white;border-width:thin;overflow:hidden;background:url(' . $image . ') no-repeat center;width:120px;height:47px;background-size:contain;"></a>';

	return $button;
}

/**
 * Get results for specified marking stage.
 *
 * @param	\stage	$stage	Specified stage
 *
 * @return	string
 */
 
function subbub_getStage($stage) {
	$text = '';

	foreach ($stage->entries as $entry) {
		$text .= ('<tr><td style="width:20%">' . $entry->placing . '</td><td>' . $entry->title . '</td></tr>');
	}

	return $text;
}

/**
 * Get results for specified category.
 *
 * @param	\category	$category	Specified category
 *
 * @return	string
 */
 
function subbub_getCategory($category) {
	$text = '';

	if ($category->header != '') {
		$text .= ('<h' . $category->headerLevel . '>' . $category->header . '</h' . $category->headerLevel . '>');
	}

	if ($category->status != '') {
		$text .= $category->status;
	}

	$text .= '<table>';

	$break = false;

	foreach ($category->stages as $stage) {
		if ($break) {
			$text .= '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
		} else {
			$break = true;
		}

		$text .= subbub_getStage($stage);
	}

	$text .= '</table>';

	if ($category->reportURL != null) {
		$text .= ('<a href="' . $category->reportURL . '">Judge\'s report</a>');
	}

	return $text;
}

/**
 * Get results for specified event.
 *
 * @param	\event	$event	Specified event
 *
 * @return	string
 */
 
function subbub_getEvent($event) {
	if ($event->header != '') {
		$text .= ('<h' . $event->headerLevel . '>' . $event->header . '</h' . $event->headerLevel . '>');
	}

	if ($event->status != '') {
		$text .= $event->status;
	}

	foreach ($event->categories as $category) {
		$text .= subbub_getCategory($category);
	}

	return $text;
}

/**
 * Handle short code for displaying results to user.
 *
 * @param	array		$atts	Incoming attributes
 *
 * @return	string
 */
 
function subbub_results_shortcode($atts) {
	$unitSelection = subbub_getUnitSelection($atts);

	$test = $atts['test'];

//	Construct address to request results data from

	$address = subbub_getBaseAddress($test) . 'libs/jpa/subbub/subbub_results_json.php?' . $unitSelection;

//	Request data and parse results

	$response = wp_remote_get($address);
	$json = wp_remote_retrieve_body($response);

	$result = json_decode($json, false);

	if ($result->status != '') {
		return $result->status;
	}

//	Construct HTML text to display to user

	$text = '';

	foreach ($result->events as $event) {
		$text .= subbub_getEvent($event);
	}

	return $text;
}

/**
 * Register shortcodes
 *
 * @return	none
 */
 
function subbub_register_shortcodes() {
	add_shortcode('subbub_submit', 'subbub_submit_shortcode');
	add_shortcode('subbub_results', 'subbub_results_shortcode');
}

add_action('init', 'subbub_register_shortcodes');

?>
