<?php

namespace Bojaghi\SeedObjects\Tests;

use Bojaghi\SeedObjects\TermSeeds;
use WP_Term;
use WP_UnitTestCase;

class TestTermSeeds extends WP_UnitTestCase
{
    public function testAddRemove(): void
    {
        global $wpdb;

        if (!taxonomy_exists('test_taxonomy')) {
            register_taxonomy('test_taxonomy', 'post');
        }

        $items = [
            [
                'name'        => 'Test Term #1',
                'taxonomy'    => 'test_taxonomy',
                'description' => 'Test term number 1',
                'slug'        => 'test-term-1',
            ],
            [
                'name'        => 'Test Term #2',
                'taxonomy'    => 'test_taxonomy',
                'description' => 'Test term number 2',
                'slug'        => 'test-term-2',
            ],
        ];

        $seeds = new TermSeeds($items);

        // Test add()
        $seeds->add();

        $terms = get_terms('taxonomy=test_taxonomy&orderby=term_id&order=ASC&hide_empty=0');
        $this->assertIsArray($terms);
        $this->assertCount(2, $terms);

        $this->assertInstanceOf(WP_Term::class, $terms[0]);
        $this->assertEquals('Test Term #1', $terms[0]->name);
        $this->assertEquals('test-term-1', $terms[0]->slug);

        $this->assertInstanceOf(WP_Term::class, $terms[1]);
        $this->assertEquals('Test Term #2', $terms[1]->name);
        $this->assertEquals('test-term-2', $terms[1]->slug);

        // Repeated add() calls do not affect the total number of seed terms.
        $seeds->add();
        $seeds->add();

        // Get count by raw query, refusing to be affected by cache.
        $query = "SELECT COUNT(t.term_id) FROM $wpdb->terms AS t" .
            " INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id" .
            " WHERE tt.taxonomy='test_taxonomy'";
        $count = (int)$wpdb->get_var($query);
        $this->assertEquals(2, $count);

        // Test remove().
        $seeds->remove();

        $terms = get_terms('taxonomy=test_taxonomy&orderby=term_id&order=ASC&hide_empty=0');
        $this->assertIsArray($terms);
        $this->assertCount(0, $terms);
    }
}
