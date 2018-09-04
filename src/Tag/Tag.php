<?php

namespace drupol\htmltag\Tag;

use drupol\htmltag\AbstractBaseHtmlTagObject;
use drupol\htmltag\Attributes\AttributesInterface;
use drupol\htmltag\StringableInterface;

/**
 * Class Tag.
 */
class Tag extends AbstractBaseHtmlTagObject implements TagInterface
{
    /**
     * The tag name.
     *
     * @var string
     */
    private $tag;

    /**
     * The tag attributes.
     *
     * @var \drupol\htmltag\Attributes\AttributesInterface
     */
    private $attributes;

    /**
     * The tag content.
     *
     * @var mixed[]|null
     */
    private $content;

    /**
     * Tag constructor.
     *
     * @param \drupol\htmltag\Attributes\AttributesInterface $attributes
     *   The attributes object.
     * @param string $name
     *   The tag name.
     * @param mixed $content
     *   The content.
     */
    public function __construct(AttributesInterface $attributes, $name, $content = false)
    {
        $this->tag = $name;
        $this->attributes = $attributes;
        $this->content($content);
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return \drupol\htmltag\Tag\TagInterface
     */
    public static function __callStatic($name, array $arguments = [])
    {
        return new static($arguments[0], $name);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * {@inheritdoc}
     */
    public function attr($name = null, ...$value)
    {
        if (null === $name) {
            return $this->attributes->render();
        }

        if ([] == $value) {
            return $this->attributes[$name];
        }

        return $this->attributes[$name]->set($value);
    }

    /**
     * {@inheritdoc}
     */
    public function content(...$data)
    {
        if ([] !== $data) {
            if (\reset($data) === false) {
                $data = null;
            }

            $this->content = $data;
        }

        return $this->renderContent();
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return null === $this->content ?
            \sprintf('<%s%s/>', $this->tag, $this->attributes->render()):
            \sprintf('<%s%s>%s</%s>', $this->tag, $this->attributes->render(), $this->renderContent(), $this->tag);
    }

    /**
     * Render the tag content.
     *
     * @return string
     */
    protected function renderContent()
    {
        return \implode(
            '',
            $this->escapeValues(
                $this->preprocess(
                    $this->ensureFlatArray((array) $this->content)
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return \serialize([
            'tag' => $this->tag,
            'attributes' => $this->attributes->getValuesAsArray(),
            'content' => $this->renderContent(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $unserialize = \unserialize($serialized);

        $this->tag = $unserialize['tag'];
        $this->attributes = $this->attributes->import($unserialize['attributes']);
        $this->content = $unserialize['content'];
    }

    /**
     * Alter the values of an object.
     *
     * @param callable ...$closures
     *   A closure.
     *
     * @return object
     *   The object.
     */
    /**
     * {@inheritdoc}
     */
    public function alter(callable ...$closures)
    {
        foreach ($closures as $closure) {
            $this->content = $closure(
                $this->ensureFlatArray((array) $this->content)
            );
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function escape($value)
    {
        $return = $this->ensureString($value);

        if ($value instanceof StringableInterface) {
            return $return;
        }

        return null === $return ?
            $return:
            \htmlentities($return);
    }
}
