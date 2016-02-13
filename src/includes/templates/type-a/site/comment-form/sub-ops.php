<?php
namespace comment_mail;
/**
 * @var plugin    $plugin Plugin class.
 * @var template  $template Template class.
 *
 * Other variables made available in this template file:
 *
 * @var integer   $post_id Current post ID; where this is being displayed.
 *
 * @var \stdClass $current An object w/ `sub_email`, `sub_type`, and `sub_deliver`.
 *    These properties are also provided as variables below; same thing.
 *
 * @var string    $sub_email The current subscriber's email address; if available.
 *    Note: a link to the summary page (i.e. the My Subscriptions page) should not be displayed if this is empty.
 *
 * @var string    $sub_type The current subscriber's last known option for the subscription type select menu.
 *    Or, if we don't know for sure, this will be filled w/ the default value configured in plugin options.
 *
 * @var string    $sub_deliver The current subscriber's last known option for the deliver option select menu.
 *    Or, if we don't know for sure, this will be filled w/ the default value configured in plugin options.
 *
 * @var string    $sub_type_id The `id=""` value for the subscription type select menu.
 * @var string    $sub_type_name The `name=""` value for the subscription type select menu.
 *
 * @var string    $sub_deliver_id The `id=""` value for the subscription delivery option select menu.
 * @var string    $sub_deliver_name The `name=""` value for the subscription delivery option select menu.
 *
 * @var string    $sub_list_id The `id=""` value for the subscription list checkbox.
 * @var string    $sub_list_name The `name=""` value for the subscription list checkbox.
 *
 * @var string    $sub_summary_url A URL leading the subscription summary page (i.e. the My Subscriptions page).
 *    A link to the summary page (i.e. the My Subscriptions page) should not be displayed if `$sub_email` is empty.
 *
 * @var string    $sub_new_url A URL leading to the "Add Subscription" page. This allows a visitor to subscribe w/o commenting even.
 *
 * @var string    $inline_icon_svg Inline SVG icon that inherits the color and width of it's container automatically.
 *    Note, this is a scalable vector graphic that will look great at any size >= 16x16 pixels.
 *
 * -------------------------------------------------------------------
 * @note In addition to plugin-specific variables & functionality,
 *    you may also use any WordPress functions that you like.
 */
?>

<div class="comment-sub-ops" data-auto="position">

	<label for="<?php echo esc_attr($sub_type_id); ?>" class="cso-sub-type">
		<span class="cso-icon"><?php echo $inline_icon_svg; ?></span>
		<?php echo __('Receive Email Notifications?', $plugin->text_domain); ?>
	</label>

	<select id="<?php echo esc_attr($sub_type_id); ?>" name="<?php echo esc_attr($sub_type_name); ?>" class="cso-sub-type form-control" title="<?php echo __('Receive Notifications?', $plugin->text_domain); ?>">
		<option value=""<?php selected('', $current->sub_type); ?>><?php echo __('no, do not subscribe', $plugin->text_domain); ?></option>
		<option value="comment"<?php selected('comment', $current->sub_type); ?>><?php echo __('yes, replies to my comment', $plugin->text_domain); ?></option>
		<option value="comments"<?php selected('comments', $current->sub_type); ?>><?php echo __('yes, all comments/replies', $plugin->text_domain); ?></option>
	</select>

	<?php // TIP: this is optional. If you exclude this select menu, the value will automatically default to `asap`. ?>
	<select id="<?php echo esc_attr($sub_deliver_id); ?>" name="<?php echo esc_attr($sub_deliver_name); ?>" class="cso-sub-deliver form-control" title="<?php echo __('Notify Me', $plugin->text_domain); ?>">
		<option value="asap"<?php selected('asap', $current->sub_deliver); ?>><?php echo __('instantly', $plugin->text_domain); ?></option>
		<option value="hourly"<?php selected('hourly', $current->sub_deliver); ?>><?php echo __('hourly digest', $plugin->text_domain); ?></option>
		<option value="daily"<?php selected('daily', $current->sub_deliver); ?>><?php echo __('daily digest', $plugin->text_domain); ?></option>
		<option value="weekly"<?php selected('weekly', $current->sub_deliver); ?>><?php echo __('weekly digest', $plugin->text_domain); ?></option>
	</select>

  <div class="cso-sub-list">
		<?php if($plugin->options['list_server_enable'] && $plugin->options['list_server']): ?>
	    <input type="checkbox" id="<?php echo esc_attr($sub_list_id); ?>" name="<?php echo esc_attr($sub_list_name); ?>" value="1" /> <?php echo __('Yes, I want to receive blog updates also.', $plugin->text_domain); ?>
	  <?php endif; ?>
	</div>

	<div class="cso-links">
		<span class="cso-link-new"><?php echo sprintf(__('Or, you can <a href="%1$s">subscribe without commenting</a>.', $plugin->text_domain), esc_attr($sub_new_url)); ?></span>
		<?php if($current->sub_email): // TIP: this is optional. If you exclude this, subscribers can still view their summary via emails they receive. ?>
			<span class="cso-link-summary">~ <a href="<?php echo esc_attr($sub_summary_url); ?>"><?php echo __('manage my subscriptions', $plugin->text_domain); ?></a></span>
		<?php endif; ?>
	</div>

</div>

<?php // Styles used in this template. ?>

<style type="text/css">
	.comment-sub-ops
	{
		margin : 1em 0 1em 0;
	}
	.comment-sub-ops label
	{
		display : block;
	}
	.comment-sub-ops select
	{
		box-sizing : border-box;
		display    : inline-block;
	}
	.comment-sub-ops select.cso-sub-type
	{
		width : 70%;
		float : left;
	}
	.comment-sub-ops select.cso-sub-deliver
	{
		width : 28%;
		float : right;
	}
	.comment-sub-ops select.cso-sub-deliver[disabled]
	{
		opacity : 0.3;
	}
	.comment-sub-ops .cso-links
	{
		font-size   : 80%;
		line-height : 1.5em;
		margin      : 0 0 0 .5em;
		clear       : both;
	}
	.comment-sub-ops .cso-links .cso-link-summary
	{
		display     : block;
		line-height : 1em;
	}
</style>
