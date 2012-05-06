<?php

add_action( 'admin_menu', 'init' );

function init()
{
	register_setting(
		'googlecalendarlist-group',	// same as what you used in the settings_fields function call
		'plugin_options'			// name of the options
	);

	add_settings_section(
		'section_id',				// unique id
		'Setup',					// a title shown on page
		'displaySectionContent',	// a callback to display content
		'googlecalendarlist-group'	// page name (must match do_settings_sections function call)
	);

	add_settings_field(
		'idUrlFeed',				// unique id
		'Url Feed',					// title of field
		'displayUrlFeed',			// callback to display the input box
		'googlecalendarlist-group',	// page name (same as the do_settings_sections function call)
		'section_id'				// id of the settings section, same as the first argument to add_settings_section
	);

	add_settings_field(
		'idUseCaptionText',			// unique id
		'Use Caption Text',			// title of field
		'displayCaptionText',		// callback to display the input box
		'googlecalendarlist-group',	// page name (same as the do_settings_sections function call)
		'section_id'				// id of the settings section, same as the first argument to add_settings_section
	);

	add_settings_field(
		'idHeaderColor',			// unique id
		'Header Color',				// title of field
		'displayHeaderColor',		// callback to display the input box
		'googlecalendarlist-group',	// page name (same as the do_settings_sections function call)
		'section_id'				// id of the settings section, same as the first argument to add_settings_section
	);

	add_settings_field(
		'idFooterText',				// unique id
		'Footer Text',				// title of field
		'displayFooterText',		// callback to display the input box
		'googlecalendarlist-group',	// page name (same as the do_settings_sections function call)
		'section_id'				// id of the settings section, same as the first argument to add_settings_section
	);

	add_settings_field(
		'idLangDay',				// unique id
		'Localize: Day',			// title of field
		'diplayLangDay',			// callback to display the input box
		'googlecalendarlist-group',	// page name (same as the do_settings_sections function call)
		'section_id'				// id of the settings section, same as the first argument to add_settings_section
	);

	add_settings_field(
		'idLangTime',				// unique id
		'Localize: Time',			// title of field
		'diplayLangTime',			// callback to display the input box
		'googlecalendarlist-group',	// page name (same as the do_settings_sections function call)
		'section_id'				// id of the settings section, same as the first argument to add_settings_section
	);

	add_settings_field(
		'idLangEvent',				// unique id
		'Localize: Event',			// title of field
		'diplayLangEvent',			// callback to display the input box
		'googlecalendarlist-group',	// page name (same as the do_settings_sections function call)
		'section_id'				// id of the settings section, same as the first argument to add_settings_section
	);

	////////////////////////////////////////////////
	// create a menu option in the settings menu
	////////////////////////////////////////////////
	$mypage = add_options_page(
		'googleCalendarList',			// test to be displayed in the title tags of te page when the menu is selected
		'googleCalendarList',			// text to be usded for the menu
		'manage_options',				// capability
		'googlecal',
		'outputContent' );

	// create an action so we can load javascript at the right time
	add_action( "admin_print_scripts-$mypage", 'loadDataOptions' );
}

function loadDataOptions()
{
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
}

function outputContent()
{
	?>

	<div class="wrap">
	<h2>googleCalendarList</h2>

	<form action="options.php" method="post">
	<?php settings_fields( 'googlecalendarlist-group' ); ?>
	<?php do_settings_sections( 'googlecalendarlist-group' ); ?>
	<?php submit_button(); ?>
	</form>
	</div>

	<?php
}

function displaySectionContent()
{
	$options = get_option('plugin_options');
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#colorpickerHeader').hide();
		jQuery('#colorpickerHeader').farbtastic("#idHeaderColor");
		jQuery("#idHeaderColor").click(function(){jQuery('#colorpickerHeader').slideToggle()});
	  });
	  </script>
	<?php

	echo '<p>Google Setup</p>';
}

function displayUrlFeed()
{
	$options = get_option('plugin_options');
	echo "<input id='idUrlFeed' name='plugin_options[url_feed]' size='140' type='text' value='{$options['url_feed']}' />";
	echo "<br/><i>example: http://www.google.com/calendar/feeds/r7d43175q01dkslt6ulc2347nk@group.calendar.google.com/public/full</i>";
	echo "<br/><i>open google setup for calendar, select which calendar, click the XML for web address for calendar</i>";
}

function displayCaptionText()
{
	$options = get_option('plugin_options');

	echo "<input id='idUseCaptionText' name='plugin_options[use_caption_text]' type='checkbox' value='1' ".checked( isset( $options['use_caption_text'] ), true, false )."  />";
	echo "<br/><i>use caption text supplied by google calendar?</i>";
}

function displayHeaderColor()
{
	$options = get_option('plugin_options');

	if ( $options["colorHeader"]=="" )
		$options["colorHeader"] = "blue";

	echo "<input id='idHeaderColor' name='plugin_options[colorHeader]' type='text' value='{$options['colorHeader']}' />";
    echo "<div id='colorpickerHeader'></div>";
}

function displayFooterText()
{
	$options = get_option('plugin_options');
	echo "<input id='idFooterText' name='plugin_options[footer_text]' size='140' type='text' value='{$options['footer_text']}' />";
}

function diplayLangDay()
{
	$options = get_option('plugin_options');
	echo "<input id='idLangDay' name='plugin_options[lang_day]' size='140' type='text' value='{$options['lang_day']}' />";
	echo "<br/><i>(optional) default if left blank: Day</i>";
}

function diplayLangTime()
{
	$options = get_option('plugin_options');
	echo "<input id='idLangTime' name='plugin_options[lang_time]' size='140' type='text' value='{$options['lang_time']}' />";
	echo "<br/><i>(optional) default if left blank: Time</i>";
}

function diplayLangEvent()
{
	$options = get_option('plugin_options');
	echo "<input id='idLangEvent' name='plugin_options[lang_event]' size='140' type='text' value='{$options['lang_event']}' />";
	echo "<br/><i>(optional) default if left blank: Event</i>";
}

?>