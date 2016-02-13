<?php
/**
 * i18n Utilities
 *
 * @since 141111 First documented version.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace comment_mail // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	if(!class_exists('\\'.__NAMESPACE__.'\\utils_i18n'))
	{
		/**
		 * i18n Utilities
		 *
		 * @since 141111 First documented version.
		 */
		class utils_i18n extends abs_base
		{
			/**
			 * Action past tense translation.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param string $action An action; e.g. `confirm`, `delete`, `unconfirm`, etc.
			 * @param string $transform Defaults to `lower`.
			 *
			 * @return string The string translation for the given `$action`.
			 */
			public function action_ed($action, $transform = 'lower')
			{
				$action = $i18n = strtolower(trim((string)$action));

				switch($action) // Convert to past tense.
				{
					case 'reconfirm':
						$i18n = __('reconfirmed', $this->plugin->text_domain);
						break;

					case 'confirm':
						$i18n = __('confirmed', $this->plugin->text_domain);
						break;

					case 'unconfirm':
						$i18n = __('unconfirmed', $this->plugin->text_domain);
						break;

					case 'suspend':
						$i18n = __('suspended', $this->plugin->text_domain);
						break;

					case 'trash':
						$i18n = __('trashed', $this->plugin->text_domain);
						break;

					case 'update':
						$i18n = __('updated', $this->plugin->text_domain);
						break;

					case 'delete':
						$i18n = __('deleted', $this->plugin->text_domain);
						break;

					default: // Default case handler.
						if($action) // Only if it's not empty.
							$i18n = __(rtrim($action, 'ed').'ed', $this->plugin->text_domain);
						break;
				}
				if(ctype_alnum($i18n)) switch($transform) // No special chars?
					{
						case 'lower':
							$i18n = strtolower($i18n);
							break;

						case 'upper':
							$i18n = strtoupper($i18n);
							break;

						case 'ucwords':
							$i18n = ucwords($i18n);
							break;
					}
				return $i18n;
			}

			/**
			 * Status label translation.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param string $status A status e.g. `approve`, `hold`, `unconfirmed`, etc.
			 * @param string $transform Defaults to `lower`.
			 *
			 * @return string The string translation for the given `$status`.
			 */
			public function status_label($status, $transform = 'lower')
			{
				$status = $i18n = strtolower(trim((string)$status));

				switch($status) // Convert to label.
				{
					case 'approve':
						$i18n = __('approved', $this->plugin->text_domain);
						break;

					case 'hold':
						$i18n = __('pending', $this->plugin->text_domain);
						break;

					case 'trash':
						$i18n = __('trashed', $this->plugin->text_domain);
						break;

					case 'spam':
						$i18n = __('spammy', $this->plugin->text_domain);
						break;

					case 'delete':
						$i18n = __('deleted', $this->plugin->text_domain);
						break;

					case 'open':
						$i18n = __('open', $this->plugin->text_domain);
						break;

					case 'closed':
						$i18n = __('closed', $this->plugin->text_domain);
						break;

					case 'unconfirmed':
						$i18n = __('unconfirmed', $this->plugin->text_domain);
						break;

					case 'subscribed':
						$i18n = __('subscribed', $this->plugin->text_domain);
						break;

					case 'suspended':
						$i18n = __('suspended', $this->plugin->text_domain);
						break;

					case 'trashed':
						$i18n = __('trashed', $this->plugin->text_domain);
						break;

					default: // Default case handler.
						if($status) // Only if it's not empty.
							$i18n = __(rtrim($status, 'ed').'ed', $this->plugin->text_domain);
						break;
				}
				if(ctype_alnum($i18n)) switch($transform) // No special chars?
					{
						case 'lower':
							$i18n = strtolower($i18n);
							break;

						case 'upper':
							$i18n = strtoupper($i18n);
							break;

						case 'ucwords':
							$i18n = ucwords($i18n);
							break;
					}
				return $i18n;
			}

			/**
			 * Event label translation.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param string $event An event e.g. `inserted`, `updated`, `deleted`, etc.
			 * @param string $transform Defaults to `lower`.
			 *
			 * @return string The string translation for the given `$event`.
			 */
			public function event_label($event, $transform = 'lower')
			{
				$event = $i18n =  strtolower(trim((string)$event));

				switch($event) // Convert to label.
				{
					case 'inserted':
						$i18n = __('inserted', $this->plugin->text_domain);
						break;

					case 'updated':
						$i18n = __('updated', $this->plugin->text_domain);
						break;

					case 'overwritten':
						$i18n = __('overwritten', $this->plugin->text_domain);
						break;

					case 'purged':
						$i18n = __('purged', $this->plugin->text_domain);
						break;

					case 'cleaned':
						$i18n = __('cleaned', $this->plugin->text_domain);
						break;

					case 'deleted':
						$i18n = __('deleted', $this->plugin->text_domain);
						break;

					case 'invalidated':
						$i18n = __('invalidated', $this->plugin->text_domain);
						break;

					case 'notified':
						$i18n = __('notified', $this->plugin->text_domain);
						break;

					default: // Default case handler.
						if($event) // Only if it's not empty.
							$i18n = __(rtrim($event, 'ed').'ed', $this->plugin->text_domain);
						break;
				}
				if(ctype_alnum($i18n)) switch($transform) // No special chars?
					{
						case 'lower':
							$i18n = strtolower($i18n);
							break;

						case 'upper':
							$i18n = strtoupper($i18n);
							break;

						case 'ucwords':
							$i18n = ucwords($i18n);
							break;
					}
				return $i18n;
			}

			/**
			 * Deliver option label translation.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param string $deliver A delivery option; e.g. `asap`, `hourly`, etc.
			 * @param string $transform Defaults to `lower`.
			 *
			 * @return string The string translation for the given `$deliver` option.
			 */
			public function deliver_label($deliver, $transform = 'lower')
			{
				$deliver = $i18n =  strtolower(trim((string)$deliver));

				switch($deliver) // Convert to label.
				{
					case 'asap':
						$i18n = __('instantly', $this->plugin->text_domain);
						break;

					case 'hourly':
						$i18n = __('hourly', $this->plugin->text_domain);
						break;

					case 'daily':
						$i18n = __('daily', $this->plugin->text_domain);
						break;

					case 'weekly':
						$i18n = __('weekly', $this->plugin->text_domain);
						break;

					default: // Default case handler.
						if($deliver) // Only if it's not empty.
							$i18n = __(rtrim($deliver, 'ed').'ed', $this->plugin->text_domain);
						break;
				}
				if(ctype_alnum($i18n)) switch($transform) // No special chars?
					{
						case 'lower':
							$i18n = strtolower($i18n);
							break;

						case 'upper':
							$i18n = strtoupper($i18n);
							break;

						case 'ucwords':
							$i18n = ucwords($i18n);
							break;
					}
				return $i18n;
			}

			/**
			 * Sub. type label translation.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param string $sub_type A sub. type; i.e. `comments`, `comment`.
			 * @param string $transform Defaults to `lower`.
			 *
			 * @return string The string translation for the given `$sub_type`.
			 */
			public function sub_type_label($sub_type, $transform = 'lower')
			{
				$sub_type = $i18n =  strtolower(trim((string)$sub_type));

				switch($sub_type) // Convert to label.
				{
					case 'comments':
						$i18n = __('all comments', $this->plugin->text_domain);
						break;

					case 'comment':
						$i18n = __('replies only', $this->plugin->text_domain);
						break;

					default: // Default case handler.
						if($action) // Only if it's not empty.
							$i18n = __(rtrim($action, 'ed').'ed', $this->plugin->text_domain);
						break;
				}
				if(ctype_alnum($i18n)) switch($transform) // No special chars?
					{
						case 'lower':
							$i18n = strtolower($i18n);
							break;

						case 'upper':
							$i18n = strtoupper($i18n);
							break;

						case 'ucwords':
							$i18n = ucwords($i18n);
							break;
					}
				return $i18n;
			}

			/**
			 * `X subscription` or `X subscriptions`.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param integer $counter Total subscriptions; i.e. a counter value.
			 *
			 * @return string The phrase `X subscription` or `X subscriptions`.
			 */
			public function subscriptions($counter)
			{
				$counter = (integer)$counter; // Force integer.

				return sprintf(_n('%1$s subscription', '%1$s subscriptions', $counter, $this->plugin->text_domain), $counter);
			}

			/**
			 * `X sub. event log entry` or `X sub. event log entries`.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param integer $counter Total sub. event log entries; i.e. a counter value.
			 *
			 * @return string The phrase `X sub. event log entry` or `X sub. event log entries`.
			 */
			public function sub_event_log_entries($counter)
			{
				$counter = (integer)$counter; // Force integer.

				return sprintf(_n('%1$s sub. event log entry', '%1$s sub. event log entries', $counter, $this->plugin->text_domain), $counter);
			}

			/**
			 * `X queued notification` or `X queued notifications`.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param integer $counter Total queued notifications; i.e. a counter value.
			 *
			 * @return string The phrase `X queued notification` or `X queued notifications`.
			 */
			public function queued_notifications($counter)
			{
				$counter = (integer)$counter; // Force integer.

				return sprintf(_n('%1$s queued notification', '%1$s queued notifications', $counter, $this->plugin->text_domain), $counter);
			}

			/**
			 * `X queue event log entry` or `X queue event log entries`.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param integer $counter Total queue event log entries; i.e. a counter value.
			 *
			 * @return string The phrase `X queue event log entry` or `X queue event log entries`.
			 */
			public function queue_event_log_entries($counter)
			{
				$counter = (integer)$counter; // Force integer.

				return sprintf(_n('%1$s queue event log entry', '%1$s queue event log entries', $counter, $this->plugin->text_domain), $counter);
			}

			/**
			 * A confirmation/warning regarding log entry deletions.
			 *
			 * @since 141111 First documented version.
			 *
			 * @return string Confirmation/warning regarding log entry deletions.
			 */
			public function log_entry_js_deletion_confirmation_warning()
			{
				return __('Delete permanently? Are you sure?', $this->plugin->text_domain)."\n\n".
					   __('WARNING: Deleting log entries is not recommended, as this will have an impact on statistical reporting.', $this->plugin->text_domain)."\n\n".
					   __('If you want statistical reports to remain accurate, please leave ALL log entries intact.', $this->plugin->text_domain);
			}
		}
	}
}
