<?php

namespace Bojaghi\SeedObjects;

use Bojaghi\Helper\Helper;

abstract class Seeds
{
    protected array $items;

    /**
     * @param string|array $config Path to seed comments array or comments array itself.
     */
    public function __construct(string|array $config)
    {
        $this->items = Helper::loadConfig($config);
    }

    abstract public function add(): void;

    abstract public function remove(): void;
}
