<?php

namespace Bojaghi\SeedObjects\Tests;

use Bojaghi\SeedObjects\PostSeeds;
use WP_Post;
use WP_UnitTestCase;

class TestPostSeeds extends WP_UnitTestCase
{
    public function testAddRemove()
    {
        global $wpdb;

        if (!post_type_exists('test_post')) {
            register_post_type('test_post', ['hierarchical' => false, 'public' => false]);
        }

        $items = [
            [
                'post_author'  => '1',
                'post_content' => 'Test post 1 content',
                'post_title'   => 'Test Post #1',
                'post_status'  => 'publish',
                'post_type'    => 'test_post',
                'post_name'    => 'test-post-1'
            ],
            [
                'post_author'  => '1',
                'post_content' => 'Test post 2 content',
                'post_title'   => 'Test Post #2',
                'post_status'  => 'publish',
                'post_type'    => 'test_post',
                'post_name'    => 'test-post-2'
            ],
        ];

        $seeds = new PostSeeds($items);

        // Test add()
        $seeds->add();

        $posts = get_posts("post_type=test_post&orderby=ID&order=ASC");
        $this->assertIsArray($posts);
        $this->assertCount(2, $posts);

        $this->assertInstanceOf(WP_Post::class, $posts[0]);
        $this->assertEquals('Test Post #1', $posts[0]->post_title);
        $this->assertEquals('Test post 1 content', $posts[0]->post_content);

        $this->assertInstanceOf(WP_Post::class, $posts[1]);
        $this->assertEquals('Test Post #2', $posts[1]->post_title);
        $this->assertEquals('Test post 2 content', $posts[1]->post_content);

        // Repeated add() calls do not affect the total number of seed posts.
        $seeds->add();
        $seeds->add();

        // Get count by raw query, refusing to be affected by cache.
        $query = "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type='test_post'";
        $count = (int)$wpdb->get_var($query);
        $this->assertEquals(2, $count);

        // Test remove().
        $seeds->remove();

        $posts = get_posts("post_type=test_post&orderby=ID&order=ASC");
        $this->assertIsArray($posts);
        $this->assertCount(0, $posts);
    }
}