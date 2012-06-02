<?php
/*
Plugin Name: Piwik Analytics
Plugin URI: http://forwardslash.nl/piwik-analytics
Description: This plugin makes it simple to add Piwik Analytics code to your blog. <a href="options-general.php?page=piwikanalytics.php">Configuration Page</a>
Author: Jules Stuifbergen
Version: 1.0.2
Author URI: http://forwardslash.nl/
License: GPL

Based on Joost de Valk's Google Analytics for Wordpress plugin

*/

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

$siteid = "1";

/*
 * Admin User Interface
 */

if ( ! class_exists( 'PA_Admin' ) ) {

	class PA_Admin {

		function add_config_page() {
			global $wpdb;
			if ( function_exists('add_options_page') ) {
				add_options_page('Piwik Analytics Configuration', 'Piwik Analytics', 9, basename(__FILE__), array('PA_Admin','config_page'));
			}
		} // end add_PA_config_page()

		function config_page() {
			if ( $_GET['reset'] == "true") {
				$options['piwik_host'] = '';
				$options['piwik_baseurl'] = '/piwik/';
				$options['admintracking'] = false;
				$options['dltracking'] = true;
				update_option('PiwikAnalyticsPP',$options);
			}
				
			if ( isset($_POST['submit']) ) {
				if (!current_user_can('manage_options')) die(__('You cannot edit the Piwik Analytics options.'));
				check_admin_referer('analyticspp-config');
				$options['siteid'] = $_POST['siteid'];

				if (isset($_POST['piwik_baseurl']) && $_POST['piwik_baseurl'] != "") 
					$options['piwik_baseurl'] 	= strtolower($_POST['piwik_baseurl']);

				if (isset($_POST['piwik_host']) && $_POST['piwik_host'] != "") 
					$options['piwik_host'] 	= strtolower($_POST['piwik_host']);

				if (isset($_POST['dltracking'])) {
					$options['dltracking'] = true;
				} else {
					$options['dltracking'] = false;
				}

				if (isset($_POST['admintracking'])) {
					$options['admintracking'] = true;
				} else {
					$options['admintracking'] = false;
				}

				update_option('PiwikAnalyticsPP', $options);
			}

			$options  = get_option('PiwikAnalyticsPP');
			?>
			<div class="wrap">
				<script type="text/javascript">
					function toggle_help(ele, ele2) {
						var expl = document.getElementById(ele2);
						if (expl.style.display == "block") {
							expl.style.display = "none";
							ele.innerHTML = "What's this?";
						} else {
							expl.style.display = "block";
							ele.innerHTML = "Hide explanation";
						}
					}
				</script>
				<h2>Piwik Analytics Configuration</h2>
				<form action="" method="post" id="analytics-conf">
					<table class="form-table" style="width:100%;">
					<?php
					if ( function_exists('wp_nonce_field') )
						wp_nonce_field('analyticspp-config');
					?>
					<tr>
						<th scope="row" style="width:400px;" valign="top">
							<label for="siteid">Piwik site id</label> <small><a href="#" onclick="javascript:toggle_help(this, 'expl');">What's this?</a></small>
						</th>
						<td>
							<input id="siteid" name="siteid" type="text" size="3" maxlength="4" value="<?php echo $options['siteid']; ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.5em;" /><br/>
							<div id="expl" style="display:none;">
								<h3>Explanation</h3>
								<p>Piwik Analytics is a statistics service provided
									free of charge under the GPL license.
									If you don't have a Piwik installed, you can get it at
									<a href="http://piwik.org/">piwik.org</a>.</p>

								<p>In the Piwik interface, when you "Add Website"
									you are shown a piece of JavaScript that
									you are told to insert into the page, in that script is a 
									unique string that identifies the website you 
									just defined, that is your site ID (usually "1").
								<p>Once you have entered your site id in
								   the box above your pages will be trackable by
									Piwik Analytics.</p>
							</div>
						</td>
					</tr>							
					<tr>
						<th scope="row" valign="top">
							<label for="dltracking">Track downloads</label><br/>
							<small>(default is YES)</small>
						</th>
						<td>
							<input type="checkbox" id="dltracking" name="dltracking" <?php if ($options['dltracking']) echo ' checked="unchecked" '; ?>/> 
						</td>
					</tr>
					<tr>
						<th scope="row" style="width:400px;" valign="top">
							<label for="piwik_host">Hostname of the piwik server (optional)</label> <small><a href="#" onclick="javascript:toggle_help(this, 'expl3');">What's this?</a></small>
						</th>
						<td>
							<input id="piwik_host" name="piwik_host" type="text" size="40" maxlength="99" value="<?php echo $options['piwik_host']; ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.5em;" /><br/>
							<div id="expl3" style="display:none;">
								<h3>Explanation</h3>
								<p>Example: www.yourdomain.com -- Leave blank (default) if this is the same as your blog. Do NOT include the http:// bit.</p>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" style="width:400px;" valign="top">
							<label for="piwik_baseurl">Base URL path of piwik installation</label> <small><a href="#" onclick="javascript:toggle_help(this, 'expl2');">What's this?</a></small>
						</th>
						<td>
							<input id="piwik_baseurl" name="piwik_baseurl" type="text" size="40" maxlength="99" value="<?php echo $options['piwik_baseurl']; ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.5em;" /><br/>
							<div id="expl2" style="display:none;">
								<h3>Explanation</h3>
								<p>The URL path to the piwik installation. E.g. /piwik/ or /stats/. Don't forget the trailing slash!</p>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="admintracking">Track the admin user too</label><br/>
							<small>(default is not to)</small>
						</th>
						<td>
							<input type="checkbox" id="admintracking" name="admintracking" <?php if ($options['admintracking']) echo ' checked="checked" '; ?>/> 
						</td>
					</tr>
					</table>
					<p style="border:0;" class="submit"><input type="submit" name="submit" value="Update Settings &raquo;" /></p>
				</form>
				<p>All options set? Then <a href="http://<?php if ($options['piwik_host']) { echo $options['piwik_host']; }else{ echo $_SERVER['HTTP_HOST'];}; echo $options['piwik_baseurl']; ?>" title="Piwik admin url" target="_blank">check out your stats!</a>
			</div>
			<?php
			if (isset($options['siteid'])) {
				if ($options['siteid'] == "") {
					add_action('admin_footer', array('PA_Admin','warning'));
				} else {
					if (isset($_POST['submit'])) {
						if ($_POST['siteid'] != $options['siteid'] ) {
							add_action('admin_footer', array('PA_Admin','success'));
						}
					}
				}
			} else {
				add_action('admin_footer', array('PA_Admin','warning'));
			}

		} // end config_page()

		function restore_defaults() {
			$options['piwik_host'] = '';
			$options['piwik_baseurl'] = '/piwik/';
			$options['admintracking'] = false;
			$options['dltracking'] = true;
			update_option('PiwikAnalyticsPP',$options);
		}
		
		function success() {
			echo "
			<div id='analytics-warning' class='updated fade-ff0000'><p><strong>Congratulations! You have just activated Piwik Analytics.</p></div>
			<style type='text/css'>
			#adminmenu { margin-bottom: 7em; }
			#analytics-warning { position: absolute; top: 7em; }
			</style>";
		} // end success()

		function warning() {
			echo "
			<div id='analytics-warning' class='updated fade-ff0000'><p><strong>Piwik Analytics is not active.</strong> You must <a href='plugins.php?page=piwikanalytics.php'>enter your Site ID</a> for it to work.</p></div>";
		} // end warning()

	} // end class PA_Admin

} //endif


/**
 * Code that actually inserts stuff into pages.
 */
if ( ! class_exists( 'PA_Filter' ) ) {
	class PA_Filter {

		/*
		 * Insert the tracking code into the page
		 */
		function spool_analytics() {
			?><!-- Piwik plugin active --><?php
			
			$options  = get_option('PiwikAnalyticsPP');
			
			if ($options["siteid"] != "" && (!current_user_can('edit_users') || $options["admintracking"]) && !is_preview() ) { ?>
				<!-- Piwik code inserted by Piwik Analytics Wordpress plugin by Jules Stuifbergen http://forwardslash.nl/piwik-analytics/ -->
				<script type="text/javascript">
				<?php if ( $options['piwik_host'] ) { ?>
					var pkBaseURL = document.location.protocol + "//" + "<?php echo $options['piwik_host']; ?>" + "<?php echo $options['piwik_baseurl']; ?>";
				<?php } else { ?>
					var pkBaseURL = document.location.protocol + "//" + document.location.host + "<?php echo $options['piwik_baseurl'] ?>";
				<?php
			};
			?>
				document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
				</script><script type="text/javascript">
				try {
				var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", <?php echo $options['siteid']; ?>);
				piwikTracker.setDocumentTitle(document.title);
				piwikTracker.trackPageView();
			<?php
			if ( $options["dltracking"]) { ?>
				piwikTracker.enableLinkTracking();
			<?php } ?>
				} catch( err ) {}
				</script>
				<!-- /Piwik -->
	<?php
			}
		}


	} // class PA_Filter
} // endif

if (function_exists("get_option")) {
	if ($wp_siteid_takes_precedence) {
		$options  = get_option('PiwikAnalyticsPP');
		$siteid = $options['siteid'];
	}
} 

$gaf = new PA_Filter();

$options  = get_option('PiwikAnalyticsPP',"");

if ($options == "") {
	$options['piwik_host'] = '';
	$options['piwik_baseurl'] = '/piwik/';
	$options['dltracking'] = true;
	update_option('PiwikAnalyticsPP',$options);
}

// adds the menu item to the admin interface
add_action('admin_menu', array('PA_Admin','add_config_page'));


// adds the footer so the javascript is loaded
add_action('wp_footer', array('PA_Filter','spool_analytics'));	

?>
