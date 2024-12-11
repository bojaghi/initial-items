<?php

namespace Bojaghi\SeedObjects;

use Bojaghi\Helper\Helper;

/**
 * Insert seed comments
 *
 * Note that every seed comments will change its comment_type to '_seed_comment'.
 * Therefore, DO NOT assign 'comment_type' field value.
 *
 * **IMPORTANT**
 * When it is activated, all seed comments are deleted and inserted again
 *  since comments do not have unique identifiers such as post_name, user_login, and slug.
 *
 * @see wp_insert_comment()
 */
class CommentSeeds extends Seeds
{
    public const COMMENT_TYPE = '_seed_comment';

    /**
     * Add initial seed comments from config.
     *
     * @return void
     */
    public function add(): void
    {
        foreach ($this->items as $item) {
            // Force converting comment_type
            $item['comment_type'] = self::COMMENT_TYPE;
            wp_insert_comment($item);
        }
    }

    public function remove(): void
    {
        global $wpdb;

        // Find all seed comments.
        $query      = $wpdb->prepare("SELECT comment_ID FROM $wpdb->comments WHERE comment_type=%s", self::COMMENT_TYPE);
        $commentIds = $wpdb->get_col($query);

        if ($commentIds) {
            foreach ($commentIds as $commentId) {
                wp_delete_comment($commentId, true);
            }
        }
    }
}
