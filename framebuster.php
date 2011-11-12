<?php

/*
Plugin Name: Frame Buster
Plugin URI: http://www.jamesmckay.net/code/wp-framebuster/
Description: Breaks out of frame sets. Compatible with WordPress 2.0's preview feature, and includes an administration page which allows the user to exempt certain domains from the frame buster.
Version: 1.0.4
Author: James McKay
Author URI: http://www.jamesmckay.net/
*/

/* ========================================================================== */

/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


class jm_framebuster
{
	/* ====== Constructor ====== */

	/**
	 * Creates a new instance of the framebuster and registers the relevant filters.
	 */

	function jm_framebuster()
	{
		if (function_exists('add_filter')) {
			add_filter('wp_head', array(&$this, 'render_breakout'));
			add_action('admin_menu', array(&$this, 'add_config_page'));
		}
	}

	/* ====== getopts ====== */

	/**
	 * Gets the options as saved in the database.
	 * The options are lazy loaded only as and when necessary.
	 * @returns An array with two entries: whether to apply the framebuster,
	 *   and which sites to override.
	 */

	function getopts()
	{
		if (!isset($this->opts)) {
			$this->opts = get_option('jammycakes_framebuster');
			if (FALSE === $this->opts) {
				$this->opts = array(true, '');
				add_option('jammycakes_framebuster', $this->opts);
			}
		}
	}

	/* ====== should_breakout ====== */

	/**
	 * Determines whether or not we should break out of framesets here.
	 * @returns true to break out, false to forget it.
	 */

	function should_breakout()
	{
		// Don't include the frame buster if there is no referrer
		if (!isset($_SERVER['HTTP_REFERER'])) return false;

		// or if we're coming from our own website
		// (this is needed to allow compatibility with the WP 2.0 preview feature)
		$ref = parse_url($_SERVER['HTTP_REFERER']);
		$host = $ref['host'];
		$port = $ref['port'];
		$host_and_port = $host . ':' . $port;

		if (strcasecmp($host, $_SERVER['HTTP_HOST']) == 0) return false;

		// Check host and port:
		if (strcasecmp($host_and_port, $_SERVER['HTTP_HOST']) == 0) return false;

		// or if we're not overriding frame sets
		$this->getopts();
		if (!$this->opts[0]) return false;

		// otherwise, check all the domains
		$domains = explode("\n", $this->opts[1]);
		foreach ($domains as $domain) {
			if ($this->does_host_match($domain, $host)) return false;
			if ($this->does_host_match($domain, $host_and_port)) return false;
		}
		return true;
	}

	/* ====== does_host_match ====== */

	/**
	 * Determines whether a host matches a specific test domain
	 * @domain The domain specified in the configuration
	 * @host The hostname from the referrer. May include a port number.
	 * @returns true if the domain and host name match, false otherwise.
	 */

	function does_host_match($domain, $host) {
		$domain = trim($domain);
		if (strcasecmp($domain, $host) == 0) return true;
		// Does it start with *. -- in this case, check for subdomains
		if (strpos($domain, '*.') === 0) {
			if (strcasecmp(substr($domain, 2), $host) == 0) return true;
			if (strcasecmp(substr($domain, 1), substr($host, 1-strlen($domain))) == 0) return true;
		}
		return false;
	}

	/* ====== render_breakout ====== */

	/**
	 * Renders the Javascript code to break out of framesets.
	 */

	function render_breakout()
	{
		echo "\r\n<!-- This site uses the Frame Buster WordPress plugin version 1.0.4 \r\n";
		echo "     Get it from http://www.jamesmckay.net/code/wp-framebuster/ -->\r\n";

		if ($this->should_breakout()) {
			echo '<script language="javascript">';
			echo '/* <![CDATA[ */ ';
			echo 'if (self != top) top.location = self.location;';
			echo ' /* ]]> */';
			echo '</script>';
		}
	}

	/* ====== Admin section ====== */

	/**
	 * Registers the configuration page.
	 */

	function add_config_page()
	{
//		if ( function_exists('add_submenu_page') )
			add_submenu_page('options-general.php', __('Framebuster'), __('Framebuster'), 1, __FILE__, array(&$this, 'config_page'));
	}

	function save_config_options()
	{
		$this->opts[0] = isset($_POST['fbEnabled']);
		$this->opts[1] = $_POST['fbExceptions'];
		update_option('jammycakes_framebuster', $this->opts);

		?>
			<div id='framebuster-saved' class='updated fade-ffff00'><p><strong> <?php _e('Options saved.') ?></strong></p></div>
		<?php
	}

	function config_page()
	{
		$this->getopts();
		if ('POST' == $_SERVER['REQUEST_METHOD']) {
			$this->save_config_options();
		}
		?>
			<div class="wrap">
				<h2><?php _e('Framebuster Options'); ?></h2>
				<form action="" method="post" id="framebuster-conf">

					<p>
						<label>
							<input type="checkbox" <?php echo $this->opts[0] ? 'checked="checked" ' : ''; ?> name="fbEnabled" />
							<?php _e('Break out of frame sets'); ?>
						</label>
					</p>
					<h3><?php _e('Exceptions') ?></h3>
					<p>
						<?php _e('If you would like to disable the frame buster script for certain sites, to allow them to include your blog in a frame set, you can list them here.'); ?>
						<?php _e('Enter each domain name on a separate line.'); ?>
					</p>
					<textarea name="fbExceptions" style="width: 100%" rows="10"><?php echo $this->opts[1]; ?></textarea>
					<p class="submit"><input type="submit" name="submit" value="<?php _e('Update options &raquo;'); ?>" /></p>
				</form>
			</div>
		<?php
	}
}

$myframebuster = new jm_framebuster();

?>