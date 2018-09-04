<?php

namespace drupol\htmltag;

use drupol\htmltag\Element\Comment;
use drupol\htmltag\Tag\TagFactory;

/**
 * Class HtmlBuilder
 */
final class HtmlBuilder
{
    /**
     * @var \drupol\htmltag\Tag\TagInterface|null
     */
    private $scope;

    /**
     * @var \drupol\htmltag\Tag\TagInterface[]|string[]
     */
    private $storage;

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return $this
     */
    public function __call($name, array $arguments = [])
    {
        if ('_' === $name) {
            $this->scope = null;

            return $this;
        }

        $tag = TagFactory::build($name, ...$arguments);

        if (null !== $this->scope) {
            $this->scope->content($this->scope->content(), $tag);
            $this->scope = $tag;
        } else {
            $this->scope = $tag;
            $this->storage[] = $this->scope;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $output = '';

        foreach ($this->storage as $item) {
            $output .= $item;
        }

        return $output;
    }
}
