<?php

namespace Bojaghi\SeedObjects\Tests;

use Bojaghi\SeedObjects\CommentSeeds;
use WP_Comment;
use WP_UnitTestCase;

class TestCommentSeeds extends WP_UnitTestCase
{
    public function testAddRemove(): void
    {
        $seeds = new CommentSeeds(
            [
                [
                    'comment_author'       => 'tester1',
                    'comment_author_email' => 'tester1@email.com',
                    'comment_author_IP'    => '127.0.0.1',
                    'comment_content'      => 'test comment #1',
                ],
                [
                    'comment_author'       => 'tester2',
                    'comment_author_email' => 'tester2@email.com',
                    'comment_author_IP'    => '127.0.0.1',
                    'comment_content'      => 'test comment #2',
                ],
            ],
        );

        // Test add()
        $seeds->add();

        $comments = get_comments(
            [
                'type'    => CommentSeeds::COMMENT_TYPE,
                'orderby' => 'comment_ID',
                'order'   => 'ASC',
            ],
        );

        $this->assertIsArray($comments);
        $this->assertCount(2, $comments);

        $this->assertInstanceOf(WP_Comment::class, $comments[0]);
        $this->assertEquals('tester1@email.com', $comments[0]->comment_author_email);
        $this->assertEquals('test comment #1', $comments[0]->comment_content);

        $this->assertInstanceOf(WP_Comment::class, $comments[1]);
        $this->assertEquals('tester2@email.com', $comments[1]->comment_author_email);
        $this->assertEquals('test comment #2', $comments[1]->comment_content);

        // Test remove()
        $seeds->remove();

        $comments = get_comments(
            [
                'type'    => CommentSeeds::COMMENT_TYPE,
                'orderby' => 'comment_ID',
                'order'   => 'ASC',
            ],
        );
        $this->assertIsArray($comments);
        $this->assertCount(0, $comments);
    }
}