<?php

/*
Plugin Name: googleCalendarList
Plugin URI:
Description: shows a fancy event list from your google calendar
Version: 1.0
Author: Peter Wraae Marino
Author URI: http://marino.dk
License: GPL2
*/

require_once 'googleCalendarListOptions.php';

add_shortcode( 'googlecalendarlist', 'shortcodeGoogleCalendarList' );

add_action('template_redirect','wp_my_shortcode_head');

// only load data like javascript, css when shortcode is being used
function wp_my_shortcode_head()
{
  global $posts;
  $pattern = get_shortcode_regex();
  preg_match('/'.$pattern.'/s', $posts[0]->post_content, $matches);
  if (is_array($matches) && $matches[2] == 'googlecalendarlist') {
        loadData();
  }
}

function loadData()
{
	wp_enqueue_script("jquery");

	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );

	wp_enqueue_script( 'google_api', 'http://www.google.com/jsapi' );

	$plugindir = get_settings('home').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
	wp_enqueue_script( 'googlecalendarlist_js', $plugindir . '/googleCalendarList.js' );

	wp_enqueue_style( 'googlecalendarlist_style', $plugindir . '/googleCalendarList.css' );

	wp_localize_script(
		'googlecalendarlist_js',
		'langDay',
		array(
			'Monday'	=> __('Monday'),
			'Tuesday'	=> __( 'Tuesday' ),
			'Wednesday' => __( 'Wednesday' ),
			'Thursday'	=> __( 'Thursday' ),
			'Friday'	=> __( 'Friday' ),
			'Saturday'	=> __( 'Saturday' ),
			'Sunday'	=> __( 'Sunday' )
	) );

	wp_localize_script(
		'googlecalendarlist_js',
		'langMonth',
		array(
			'January'	=> __('January'),
			'February'	=> __( 'February' ),
			'March'		=> __( 'March' ),
			'April'		=> __( 'April' ),
			'May'		=> __( 'May' ),
			'June'		=> __( 'June' ),
			'July'		=> __( 'July' ),
			'August'	=> __( 'August' ),
			'September' => __( 'September' ),
			'October'	=> __( 'October' ),
			'Novembet'	=> __( 'Novembet' ),
			'December'	=> __( 'December' )
	) );
}

function shortcodeGoogleCalendarList( $atts )
{
	loadData();
	
	$options = get_option('plugin_options');

	$s			= $options['url_feed'];
	$day		= $options['lang_day'];
	$time		= $options['lang_time'];
	$event		= $options['lang_event'];
	$colorHead	= $options['colorHeader'];

	$use_caption = isset( $options['use_caption_text'] );

	// set defaults if not supplied
	if ( $day=="" ) $day="Day";
	if ( $time=="" ) $time="Time";
	if ( $event=="" ) $event="Event";

	$table = "<table class='mytable' style='background-color:$colorHead'>";
		
	$thead = "<thead>";
	$thead.= "<tr>";
	$thead.= "<th>".__("Date")."</th>";
	$thead.= "<th>".$day."</th>";
	$thead.= "<th>".$time."</th>";
	$thead.= "<th>".$event."</th>";
	$thead.= "<th>".__("Description")."</th>";
	$thead.= "</tr>";
	$thead.= "</thead>";

	$caption_text = "";

	if ( $use_caption )
		$caption_text.= "<caption><h2><div style='padding-top:5px;' id='calendarTitle'></div></h2></caption>";
	else
		$caption_text.= "<caption style='display:none;'><div id='calendarTitle'></caption>";

	?>
		<?php 
		
			echo $table;

			echo $caption_text;

			echo $thead;
		?>
			<tfoot>
				<tr>
					<th>&nbsp;</th>
					<td colspan="4"><?php echo $options["footer_text"]; ?></td>
				</tr>
			</tfoot>

			<tbody id="cal_body">
			</tbody>
		</table>

		<script type="text/javascript">
				cal_loadCalendar( "<?php echo $s; ?>" );
		</script>
	<?php
}

?>