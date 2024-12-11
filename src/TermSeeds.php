<?php

namespace Bojaghi\SeedObjects;

use WP_Term;

/**
 * Insert seed terms
 *
 * By slug field, no duplicated terms are created.
 *
 * **IMPORTANT**
 * 'parent' field may be slug string of parent term.
 *
 * @see wp_insert_term()
 */
class TermSeeds extends Seeds
{
    public function add(): void
    {
        foreach ($this->items as $item) {
            $taxonomy = $item['taxonomy'] ?? '';
            $name     = $item['name'] ?? '';
            $slug     = $item['slug'] ?? '';

            if (!$taxonomy || !$name || term_exists($taxonomy, $name)) {
                continue;
            }

            // convert 'parent' slug to term_id
            if (isset($items['parent']) && !is_int($items['parent'])) {
                $pt     = get_term_by('slug', $items['parent'], $taxonomy);
                $parent = $pt instanceof WP_Term ? $pt->term_id : 0;
            } else {
                $parent = 0;
            }

            wp_insert_term(
                $name,
                $taxonomy,
                [
                    'alias_of'    => $item['alias_of'] ?? '',
                    'description' => $item['description'] ?? '',
                    'parent'      => $parent,
                    'slug'        => $slug,
                ],
            );
        }
    }

    public function remove(): void
    {
        foreach ($this->items as $item) {
            $taxonomy = $item['taxonomy'] ?? '';
            $name     = $item['name'] ?? '';
            if (!$taxonomy || !$name) {
                continue;
            }

            $term = get_term_by('name', $name, $taxonomy);
            if (!$term) {
                continue;
            }

            wp_delete_term($term->term_id, $taxonomy);
        }
    }
}