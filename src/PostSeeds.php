<?php

namespace Bojaghi\SeedObjects;

use WP_Query;

/**
 * Insert seed posts
 *
 * Having every post has a unique identifier post_name,
 * It does not create duplicated posts.
 *
 * @see wp_insert_post()
 */
class PostSeeds extends Seeds
{
    public function add(): void
    {
        $insertedPosts = $this->getInsertedPosts();

        foreach ($this->items as $item) {
            $postName = $item['post_name'] ?? '';
            if (!$postName || isset($insertedPosts[$postName])) {
                continue;
            }
            wp_insert_post($item);
        }
    }

    public function remove(): void
    {
        $insertedPosts = $this->getInsertedPosts();
        foreach ($insertedPosts as $postId) {
            wp_delete_post($postId);
        }
    }

    private function getInsertedPosts(): array
    {
        global $wpdb;

        $output    = [];
        $postNames = wp_list_pluck($this->items, 'post_name');

        if ($postNames) {
            $placeholders = implode(', ', array_fill(0, count($postNames), '%s'));
            $query        = $wpdb->prepare(
                "SELECT ID, post_name FROM $wpdb->posts WHERE post_name IN ($placeholders)",
                $postNames,
            );
            $results      = $wpdb->get_results($query);

            foreach ($results as $result) {
                $output[$result->post_name] = (int)$result->ID;
            }
        }

        return $output;
    }
}
