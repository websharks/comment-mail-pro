<?php
/**
 * Menu Page Actions
 *
 * @since 141111 First documented version.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace comment_mail // Root namespace.
{

	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	if(!class_exists('\\'.__NAMESPACE__.'\\menu_page_actions'))
	{
		/**
		 * Menu Page Actions
		 *
		 * @since 141111 First documented version.
		 */
		class menu_page_actions extends abs_base
		{
			/**
			 * @var array Valid actions.
			 *
			 * @since 141111 First documented version.
			 */
			protected $valid_actions;

			/**
			 * Class constructor.
			 *
			 * @since 141111 First documented version.
			 */
			public function __construct()
			{
				parent::__construct();

				$this->valid_actions
					= array(
					'save_options',
					'set_template_type',
					'restore_default_options',

					'dismiss_notice',

					'import',
					'export',

					'sub_form',
					'sub_form_comment_id_row_via_ajax',
					'sub_form_user_id_info_via_ajax',

					'stats_chart_data_via_ajax',

					'pro_update',
				);
				$this->maybe_handle();
			}

			/**
			 * Action handler.
			 *
			 * @since 141111 First documented version.
			 */
			protected function maybe_handle()
			{
				if(!is_admin())
					return; // Not applicable.

				if(empty($_REQUEST[__NAMESPACE__]))
					return; // Not applicable.

				if(!$this->plugin->utils_url->has_valid_nonce())
					return; // Unauthenticated; ignore.

				foreach((array)$_REQUEST[__NAMESPACE__] as $_action => $_request_args)
					if($_action && in_array($_action, $this->valid_actions, TRUE))
						$this->{$_action}($this->plugin->utils_string->trim_strip_deep($_request_args));
				unset($_action, $_request_args); // Housekeeping.
			}

			/**
			 * Saves options.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param mixed $request_args Input argument(s).
			 */
			protected function save_options($request_args)
			{
				$request_args = (array)$request_args;

				if(!current_user_can($this->plugin->cap))
					return; // Unauthenticated; ignore.

				$this->plugin->options_save($request_args);

				$notice_markup = // Notice regarding options having been updated successfully.
					sprintf(__('%1$s&trade; options updated successfully.', $this->plugin->text_domain), esc_html($this->plugin->name));
				$this->plugin->enqueue_user_notice($notice_markup, array('transient' => TRUE));

				if(!empty($request_args['mail_test']) && ($mail_test_to = trim((string)$request_args['mail_test'])))
				{
					$mail_test = $this->plugin->utils_mail->test(
						$mail_test_to, // To the address specificed in the request args.
						sprintf(__('Test Email Message sent by %1$s™', $this->plugin->text_domain), $this->plugin->name),
						sprintf(__('Test email message sent by %1$s&trade; from: <code>%2$s</code>.', $this->plugin->text_domain), esc_html($this->plugin->name), esc_html($this->plugin->utils_url->current_host_path()))
					);
					$this->plugin->enqueue_user_notice($mail_test->results_markup, array('transient' => TRUE));
				}
				if(!empty($request_args['mail_smtp_test']) && ($mail_smtp_test_to = trim((string)$request_args['mail_smtp_test'])))
				{
					$mail_smtp_test = $this->plugin->utils_mail->smtp_test(
						$mail_smtp_test_to, // To the address specificed in the request args.
						sprintf(__('Test Email Message sent by %1$s™', $this->plugin->text_domain), $this->plugin->name),
						sprintf(__('Test email message sent by %1$s&trade; from: <code>%2$s</code>.', $this->plugin->text_domain), esc_html($this->plugin->name), esc_html($this->plugin->utils_url->current_host_path()))
					);
					$this->plugin->enqueue_user_notice($mail_smtp_test->results_markup, array('transient' => TRUE));
				}
				wp_redirect($this->plugin->utils_url->options_updated()).exit();
			}

			/**
			 * Sets template type/mode.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param mixed $request_args Input argument(s).
			 */
			protected function set_template_type($request_args)
			{
				$template_type = (string)$request_args;

				if(!current_user_can($this->plugin->cap))
					return; // Unauthenticated; ignore.

				$this->plugin->options_save(compact('template_type'));

				$notice_markup = // Notice regarding options having been updated successfully.

					sprintf(__('Template mode updated to: <code>%2$s</code>.', $this->plugin->text_domain),
					        esc_html($this->plugin->name), $template_type === 'a' ? __('advanced', $this->plugin->text_domain) : __('simple', $this->plugin->text_domain)).

					' '.($template_type === 'a' // Provide an additional note; to help explain what just occured in this scenario.
						? 'A new set of templates has been loaded below. This mode uses advanced PHP-based templates. Recommended for advanced customization.</i>'
						: 'A new set of templates has been loaded below. This mode uses simple shortcode templates. Easiest to work with <i class="fa fa-smile-o"></i>');

				$this->plugin->enqueue_user_notice($notice_markup, array('transient' => TRUE));

				wp_redirect($this->plugin->utils_url->template_type_updated()).exit();
			}

			/**
			 * Restores defaults options.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param mixed $request_args Input argument(s).
			 */
			protected function restore_default_options($request_args)
			{
				$request_args = NULL; // Not used here.

				if(!current_user_can($this->plugin->cap))
					return; // Unauthenticated; ignore.

				delete_option(__NAMESPACE__.'_options');
				$this->plugin->options = $this->plugin->default_options;

				import_stcr::delete_post_meta_keys(); // Reset import tracking.

				$notice_markup = // Notice regarding options having been retored successfully.
					sprintf(__('%1$s&trade; default options restored successfully.', $this->plugin->text_domain), esc_html($this->plugin->name));
				$this->plugin->enqueue_user_notice($notice_markup, array('transient' => TRUE));

				wp_redirect($this->plugin->utils_url->default_options_restored()).exit();
			}

			/**
			 * Dismisses a persistent notice.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param mixed $request_args Input argument(s).
			 */
			protected function dismiss_notice($request_args)
			{
				$request_args = (array)$request_args;

				if(empty($request_args['notice_key']))
					return; // Not possible.

				if(!current_user_can($this->plugin->manage_cap))
					if(!current_user_can($this->plugin->cap))
						return; // Unauthenticated; ignore.

				$notices = get_option(__NAMESPACE__.'_notices');
				if(!is_array($notices)) $notices = array();

				unset($notices[$request_args['notice_key']]);
				update_option(__NAMESPACE__.'_notices', $notices);

				wp_redirect($this->plugin->utils_url->notice_dismissed()).exit();
			}

			/**
			 * Runs a specific import type.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param mixed $request_args Input argument(s).
			 */
			protected function import($request_args)
			{
				$request_args = (array)$request_args;

				if(empty($request_args['type']) || !is_string($request_args['type']))
					return; // Missing and/or invalid import type.

				if(!in_array($request_args['type'], array('subs', 'stcr', 'ops'), TRUE))
					return; // Invalid import type.

				if(!class_exists($class = '\\'.__NAMESPACE__.'\\import_'.$request_args['type']))
					return; // Invalid import type.

				if(!current_user_can($this->plugin->cap))
					return; // Unauthenticated; ignore.

				if(!empty($_FILES[__NAMESPACE__]['tmp_name']['import']['data_file']))
					$request_args['data_file'] = $_FILES[__NAMESPACE__]['tmp_name']['import']['data_file'];

				$importer = new $class($request_args); // Instantiate.
			}

			/**
			 * Runs a specific export type.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param mixed $request_args Input argument(s).
			 */
			protected function export($request_args)
			{
				$request_args = (array)$request_args;

				if(empty($request_args['type']) || !is_string($request_args['type']))
					return; // Missing and/or invalid import type.

				if(!in_array($request_args['type'], array('subs', 'ops'), TRUE))
					return; // Invalid import type.

				if(!class_exists($class = '\\'.__NAMESPACE__.'\\export_'.$request_args['type']))
					return; // Invalid import type.

				if(!current_user_can($this->plugin->cap))
					return; // Unauthenticated; ignore.

				$exporter = new $class($request_args); // Instantiate.
			}

			/**
			 * Processes sub. form inserts/updates.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param mixed $request_args Input argument(s).
			 *
			 * @see menu_page_sub_form_base::process()
			 */
			protected function sub_form($request_args)
			{
				if(!($request_args = (array)$request_args))
					return; // Empty request args.

				if(!current_user_can($this->plugin->manage_cap))
					if(!current_user_can($this->plugin->cap))
						return; // Unauthenticated; ignore.

				menu_page_sub_form_base::process($request_args);
			}

			/**
			 * Acquires comment ID row via AJAX.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param mixed $request_args Input argument(s).
			 *
			 * @see menu_page_sub_form_base::comment_id_row_via_ajax()
			 */
			protected function sub_form_comment_id_row_via_ajax($request_args)
			{
				$request_args = (array)$request_args;

				if(!isset($request_args['post_id']))
					exit; // Missing post ID.

				if(($post_id = (integer)$request_args['post_id']) < 0)
					exit; // Invalid post ID.

				if(!current_user_can($this->plugin->manage_cap))
					if(!current_user_can($this->plugin->cap))
						exit; // Unauthenticated; ignore.

				exit(menu_page_sub_form_base::comment_id_row_via_ajax($post_id));
			}

			/**
			 * Acquires user ID info via AJAX.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param mixed $request_args Input argument(s).
			 *
			 * @see menu_page_sub_form_base::user_id_info_via_ajax()
			 */
			protected function sub_form_user_id_info_via_ajax($request_args)
			{
				$request_args = (array)$request_args;

				if(!isset($request_args['user_id']))
					exit; // Missing user ID.

				if(($user_id = (integer)$request_args['user_id']) < 0)
					exit; // Invalid user ID.

				if(!current_user_can($this->plugin->manage_cap))
					if(!current_user_can($this->plugin->cap))
						exit; // Unauthenticated; ignore.

				header('Content-Type: application/json; charset=UTF-8');

				exit(menu_page_sub_form_base::user_id_info_via_ajax($user_id));
			}

			/**
			 * Outputs chart data for stats.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param mixed $request_args Input argument(s).
			 */
			protected function stats_chart_data_via_ajax($request_args)
			{
				$request_args = (array)$request_args;

				if(!current_user_can($this->plugin->manage_cap))
					if(!current_user_can($this->plugin->cap))
						exit; // Unauthenticated; ignore.

				header('Content-Type: application/json; charset=UTF-8');

				new chart_data($request_args); // With JSON output.

				exit(); // Stop after output; always.
			}

			/**
		     * Action handler.
			 *
			 * @since 141111 First documented version.
		     *
		     * @param mixed $request_args Input action argument(s).
		     */
		    protected function pro_update($request_args)
		    {
		        $request_args = (array)$request_args;

				if(!current_user_can($this->plugin->update_cap))
		            return; // Nothing to do.

		        $args = $this->plugin->utils_string->trim_strip_deep($request_args);

		        if(!isset($args['check']))
		            $args['check'] = $this->plugin->options['pro_update_check'];

		        if(empty($args['username']))
		            $args['username'] = $this->plugin->options['pro_update_username'];

		        if(empty($args['password']))
		            $args['password'] = $this->plugin->options['pro_update_password'];

		        $product_api_url        = $this->plugin->utils_url->product_page('https');
		        $product_api_input_vars = array(
		            'product_api' => array(
		                'action'   => 'latest_pro_update',
		                'username' => $args['username'],
		                'password' => $args['password'],
		            ),
		        );
		        $product_api_response = wp_remote_post($product_api_url, array('body' => $product_api_input_vars));
		        $product_api_response = json_decode(wp_remote_retrieve_body($product_api_response), true);

		        if (!is_array($product_api_response) || !empty($product_api_response['error'])
		           || empty($product_api_response['pro_version']) || empty($product_api_response['pro_zip'])
		        ) {
		            if (!empty($product_api_response['error']))
		                $error = (string)$product_api_response['error'];
		            else $error = __('Unknown error. Please wait 15 minutes and try again.', $this->plugin->text_domain);

					$this->plugin->enqueue_user_error($error); // For the current user only.

		            wp_redirect($this->plugin->utils_url->pro_updater_menu_page_only()).exit();
		        }
		        $this->plugin->options['last_pro_update_check'] = (string)time();
		        $this->plugin->options['pro_update_check']      = (string)$args['check'];
		        $this->plugin->options['pro_update_username']   = (string)$args['username'];
		        $this->plugin->options['pro_update_password']   = (string)$args['password'];

		        $this->plugin->options_quick_save($this->plugin->options);

		        foreach(($notices = is_array($notices = get_option(__NAMESPACE__.'_notices')) ? $notices : array()) as $_key => $_notice)
					if(!empty($_notice['persistent_id']) && $_notice['persistent_id'] === 'new-pro-version-available')
						unset($notices[$_key]); // Remove this one! :-)
				unset($_key, $_notice); // Housekeeping.

		        update_option(__NAMESPACE__.'_notices', $notices); // Update notices.

		        $redirect_to = self_admin_url('/update.php');
		        $query_args  = array(
		            'action'                            => 'upgrade-plugin',
		            'plugin'                            => plugin_basename($this->plugin->file),
		            '_wpnonce'                          => wp_create_nonce('upgrade-plugin_'.plugin_basename($this->plugin->file)),
		            __NAMESPACE__.'_update_pro_version' => $product_api_response['pro_version'],
		            __NAMESPACE__.'_update_pro_zip'     => base64_encode($product_api_response['pro_zip']),
		        );
		        $redirect_to = add_query_arg(urlencode_deep($query_args), $redirect_to);

		        wp_redirect($redirect_to).exit();
		    }
		}
	}
}
