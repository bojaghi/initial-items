<?php

namespace Bojaghi\SeedObjects;

use Bojaghi\Contract\Module;
use Bojaghi\Helper\Helper;

class SeedsObjects implements Module
{
    private string|array $comments;
    private string|array $posts;
    private string|array $terms;
    private string|array $users;

    /**
     * @param string|array $args configuration array.
     *
     * - isPlugin:             true when plugin is used, false when theme is used.
     * - removeOnDeactivation: remove seed objects when deactivated (or switched to another theme).
     * - mainFile:             required when isPlugin=true. Enter your valid plugin main file path.
     * - comments:
     * - posts:
     * - terms:
     * - users:                each object configuration file or array.
     *
     * @see wp_insert_comment()
     * @see wp_insert_post()
     * @see wp_insert_term()
     * @see wp_insert_user()
     */
    public function __construct(string|array $args = '')
    {
        $defaults = [
            'isPlugin'             => true,
            'removeOnDeactivation' => false,
            'mainFile'             => '',
            'comments'             => '',
            'posts'                => '',
            'terms'                => '',
            'users'                => '',
        ];

        $args = Helper::loadConfig($args);
        $args = wp_parse_args($args, $defaults);

        $this->comments = $args['comments'];
        $this->posts    = $args['posts'];
        $this->terms    = $args['terms'];
        $this->users    = $args['users'];

        $mainFile = $args['mainFile'];
        $isPlugin = (bool)$args['isPlugin'];
        $remove   = (bool)$args['removeOnDeactivation'];

        if ($isPlugin && $mainFile) {
            register_activation_hook($mainFile, [$this, 'activation']);
            if ($remove) {
                register_deactivation_hook($mainFile, [$this, 'deactivation']);
            }
        } else {
            // Theme
            add_action('after_switch_theme', [$this, 'activation'], 10, 2);
            if ($remove) {
                add_action('switch_theme', [$this, 'deactivation'], 10, 3);
            }
        }
    }

    public function activation(): void
    {
        if ($this->comments) {
            $seeds = new CommentSeeds($this->comments);
            $seeds->add();
        }

        if ($this->posts) {
            $seeds = new PostSeeds($this->posts);
            $seeds->add();
        }

        if ($this->terms) {
            $seeds = new TermSeeds($this->terms);
            $seeds->add();
        }

        if ($this->users) {
            $seeds = new UserSeeds($this->users);
            $seeds->add();
        }
    }

    public function deactivation(): void
    {
        if ($this->comments) {
            $seeds = new CommentSeeds($this->comments);
            $seeds->remove();
        }

        if ($this->posts) {
            $seeds = new PostSeeds($this->posts);
            $seeds->remove();
        }

        if ($this->terms) {
            $seeds = new TermSeeds($this->terms);
            $seeds->remove();
        }

        if ($this->users) {
            $seeds = new UserSeeds($this->users);
            $seeds->remove();
        }
    }
}
