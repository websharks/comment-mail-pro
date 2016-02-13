<?php
/**
 * Stats Pinger
 *
 * @since 150708 Adding stats pinger.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace comment_mail
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	if(!class_exists('\\'.__NAMESPACE__.'\\stats_pinger'))
	{
		/**
		 * Stats Pinger
		 *
		 * @since 150708 Adding stats pinger.
		 */
		class stats_pinger extends abs_base
		{
			/**
			 * Class constructor.
			 *
			 * @since 150708 Adding stats pinger.
			 */
			public function __construct()
			{
				parent::__construct();

				$this->maybe_ping();
			}

			/**
			 * Maybe ping stats logger.
			 *
			 * @since 150708 Adding stats pinger.
			 */
			protected function maybe_ping()
			{
				if (!apply_filters(__CLASS__.'_enable', true))
					return; // Stats collection off.

				if ($this->plugin->options['last_pro_stats_log'] >= strtotime('-1 week'))
					return; // No reason to keep pinging.

				$this->plugin->options_quick_save(array('last_pro_stats_log' => (string)time()));

				$stats_api_url      = 'https://stats.wpsharks.io/log';
				$stats_api_url_args = array(
					'os'              => PHP_OS,
					'php_version'     => PHP_VERSION,
					'mysql_version'   => $this->plugin->utils_db->wp->db_version(),
					'wp_version'      => get_bloginfo('version'),
					'product_version' => $this->plugin->version,
					'product'         => $this->plugin->slug.($this->plugin->is_pro ? '-pro' : ''),
				);
				$stats_api_url = add_query_arg(urlencode_deep($stats_api_url_args), $stats_api_url);

				wp_remote_get ($stats_api_url, array(
						'blocking'  => false,
						'sslverify' => false,
					)
				);
			}
		}
	}
}
