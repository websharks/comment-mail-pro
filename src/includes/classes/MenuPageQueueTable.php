<?php
/**
 * Menu Page Queue Table
 *
 * @since     141111 First documented version.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license   GNU General Public License, version 3
 */
namespace WebSharks\CommentMail\Pro;

/**
 * Menu Page Queue Table
 *
 * @since 141111 First documented version.
 */
class MenuPageQueueTable extends MenuPageTableBase
{
    /*
     * Class constructor.
     */

    /**
     * Class constructor.
     *
     * @since 141111 First documented version.
     */
    public function __construct()
    {
        $plugin = plugin(); // Needed below.

        $args = [
          'singular_name'  => 'queued_notification',
          'plural_name'    => 'queued_notifications',
          'singular_label' => __('queued notification', $plugin->text_domain),
          'plural_label'   => __('queued notifications', $plugin->text_domain),
          'screen'         => $plugin->menu_page_hooks[GLOBAL_NS.'_queue'],
        ];
        parent::__construct($args); // Parent constructor.
    }

    /*
     * Public column-related methods.
     */

    /**
     * Table columns.
     *
     * @since 141111 First documented version.
     *
     * @return array An array of all table columns.
     */
    public static function getTheColumns()
    {
        $plugin = plugin(); // Plugin class instance.

        return [
          'cb'                => '1', // Include checkboxes.
          'ID'                => __('ID', $plugin->text_domain),
          'insertion_time'    => __('Time', $plugin->text_domain),
          'sub_id'            => __('Subscr. ID', $plugin->text_domain),
          'user_id'           => __('WP User ID', $plugin->text_domain),
          'post_id'           => __('Subscr. to Post ID', $plugin->text_domain),
          'comment_parent_id' => __('Subscr. to Comment ID', $plugin->text_domain),
          'comment_id'        => __('Regarding Comment ID', $plugin->text_domain),
          'last_update_time'  => __('Last Update', $plugin->text_domain),
          'hold_until_time'   => __('Holding Until', $plugin->text_domain),
        ];
    }

    /**
     * Hidden table columns.
     *
     * @since 141111 First documented version.
     *
     * @return array An array of all hidden table columns.
     */
    public static function getTheHiddenColumns()
    {
        return [
          'user_id',
          'comment_parent_id',
          'comment_id',
          'last_update_time',
        ];
    }

    /**
     * Searchable fulltext table columns.
     *
     * @since 141111 First documented version.
     *
     * @return array An array of all fulltext searchables.
     */
    public static function getTheFtSearchableColumns()
    {
        return [];
    }

    /**
     * Searchable table columns.
     *
     * @since 141111 First documented version.
     *
     * @return array An array of all searchables.
     */
    public static function getTheSearchableColumns()
    {
        return [
          'ID',
        ];
    }

    /**
     * Unsortable table columns.
     *
     * @since 141111 First documented version.
     *
     * @return array An array of all unsortable table columns.
     */
    public static function getTheUnsortableColumns()
    {
        return [];
    }

    /*
     * Public filter-related methods.
     */

    /**
     * Navigable table filters.
     *
     * @since 141111 First documented version.
     *
     * @return array An array of all navigable table filters.
     */
    public static function getTheNavigableFilters()
    {
        return [];
    }

    /*
     * Protected column-related methods.
     */

    /**
     * Table column handler.
     *
     * @since 141111 First documented version.
     *
     * @param \stdClass $item Item object; i.e. a row from the DB.
     *
     * @return string HTML markup for this table column.
     */
    protected function column_ID(\stdClass $item)
    {
        $id_info = '<i class="fa fa-envelope-o"></i>'. // Notification icon w/ ID.
                   ' <span style="font-weight:bold;">#'.esc_html($item->ID).'</span>';

        $delete_url = $this->plugin->utils_url->table_bulk_action($this->plural_name, [$item->ID], 'delete');

        $row_actions = [
          'delete' => '<a href="#"'.  // Depends on `menu-pages.js`.
                      ' data-pmp-action="'.esc_attr($delete_url).'"'. // The action URL.
                      ' data-pmp-confirmation="'.esc_attr(__('Delete queued notification? Are you sure?', $this->plugin->text_domain)).'"'.
                      ' title="'.esc_attr(__('Delete Queued Notification', $this->plugin->text_domain)).'">'.
                      '  <i class="fa fa-times-circle"></i> '.__('Delete', $this->plugin->text_domain).
                      '</a>',
        ];
        return $id_info.$this->row_actions($row_actions);
    }

    /*
     * Public query-related methods.
     */

    /**
     * Runs DB query; sets pagination args.
     *
     * @since 141111 First documented version.
     */
    public function prepare_items() // The heart of this class.
    {
        $per_page                    = $this->getPerPage();
        $current_offset              = $this->getCurrentOffset();
        $clean_search_query          = $this->getCleanSearchQuery();
        $sub_ids_in_search_query     = $this->getSubIdsInSearchQuery();
        $sub_emails_in_search_query  = $this->getSubEmailsInSearchQuery();
        $user_ids_in_search_query    = $this->getUserIdsInSearchQuery();
        $post_ids_in_search_query    = $this->getPostIdsInSearchQuery();
        $comment_ids_in_search_query = $this->getCommentIdsInSearchQuery();
        $statuses_in_search_query    = $this->getStatusesInSearchQuery();
        $events_in_search_query      = $this->getEventsInSearchQuery();
        $is_and_search_query         = $this->isAndSearchQuery();
        $orderby                     = $this->getOrderby();
        $order                       = $this->getOrder();

        $and_or = $is_and_search_query ? 'AND' : 'OR';

        $sql = "SELECT SQL_CALC_FOUND_ROWS *". // w/ calc enabled.

               " FROM `".esc_sql($this->plugin->utils_db->prefix().'queue')."`".

               " WHERE 1=1". // Default where clause.

               ($sub_ids_in_search_query /* || $sub_emails_in_search_query */ || $user_ids_in_search_query || $post_ids_in_search_query || $comment_ids_in_search_query
                 ? " AND (".$this->plugin->utils_string->trim( // Trim the following...

                   ($sub_ids_in_search_query ? " ".$and_or." `sub_id` IN('".implode("','", array_map('esc_sql', $sub_ids_in_search_query))."')" : '').
                   // ($sub_emails_in_search_query ? " ".$and_or." `email` IN('".implode("','", array_map('esc_sql', $sub_emails_in_search_query))."')" : '').
                   ($user_ids_in_search_query ? " ".$and_or." `user_id` IN('".implode("','", array_map('esc_sql', $user_ids_in_search_query))."')" : '').
                   ($post_ids_in_search_query ? " ".$and_or." `post_id` IN('".implode("','", array_map('esc_sql', $post_ids_in_search_query))."')" : '').

                   ($comment_ids_in_search_query // Search both fields here.
                     ? " ".$and_or." (`comment_parent_id` IN('".implode("','", array_map('esc_sql', $comment_ids_in_search_query))."')".
                       "              OR `comment_id` IN('".implode("','", array_map('esc_sql', $comment_ids_in_search_query))."'))" : '')

                   , '', 'AND OR'
                 ).")" : ''). // Trims `AND OR` leftover after concatenation occurs.

               ($clean_search_query // A search query?
                 ? " AND (".$this->prepareSearchableOrCols().")"
                 : ''). // Otherwise, we can simply exclude this.

               ($orderby // Ordering by a specific column, or relevance?
                 ? " ORDER BY `".esc_sql($orderby)."`".($order ? " ".esc_sql($order) : '')
                 : ''). // Otherwise, we can simply exclude this.

               " LIMIT ".esc_sql($current_offset).",".esc_sql($per_page);

        if (($results = $this->plugin->utils_db->wp->get_results($sql))) {
            $this->setItems($results = $this->plugin->utils_db->typify_deep($results));
            $this->setTotalItemsAvailable((integer)$this->plugin->utils_db->wp->get_var("SELECT FOUND_ROWS()"));

            $this->prepareItemsMergeSubProperties(); // Merge additional properties.
            $this->prepareItemsMergeUserProperties(); // Merge additional properties.
            $this->prepareItemsMergePostProperties(); // Merge additional properties.
            $this->prepareItemsMergeCommentProperties(); // Merge additional properties.
        }
    }

    /**
     * Get default orderby value.
     *
     * @since 141111 First documented version.
     *
     * @return string The default orderby value.
     */
    protected function getDefaultOrderby()
    {
        return 'insertion_time'; // Default orderby.
    }

    /**
     * Get default order value.
     *
     * @since 141111 First documented version.
     *
     * @return string The default order value.
     */
    protected function getDefaultOrder()
    {
        return 'asc'; // Default order.
    }

    /*
     * Protected action-related methods.
     */

    /**
     * Bulk actions for this table.
     *
     * @since 141111 First documented version.
     *
     * @return array An array of all bulk actions.
     */
    protected function get_bulk_actions()
    {
        return [
          'delete' => __('Delete', $this->plugin->text_domain),
        ];
    }

    /**
     * Bulk action handler for this table.
     *
     * @since 141111 First documented version.
     *
     * @param string $bulk_action The bulk action to process.
     * @param array  $ids         The bulk action IDs to process.
     *
     * @return integer Number of actions processed successfully.
     */
    protected function processBulkAction($bulk_action, array $ids)
    {
        switch ($bulk_action) // Bulk action handler.
        {
            case 'delete': // Deleting queued notifications?
                $counter = $this->plugin->utils_queue->bulk_delete($ids);
                break; // Break switch handler.
        }
        return !empty($counter) ? (integer)$counter : 0;
    }
}
