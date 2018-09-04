<?php

namespace drupol\htmltag;

/**
 * Class AbstractBaseHtmlTagObject
 */
abstract class AbstractBaseHtmlTagObject
{
    /**
     * Escape values.
     *
     * @param array $values
     *   The values to escape.
     *
     * @return array
     *   The values escaped.
     */
    protected function escapeValues(array $values)
    {
        return \array_map(
            [$this, 'escape'],
            $values
        );
    }

    /**
     * Transform a multidimensional array into a flat array.
     *
     * We could use a iterator_to_array() with a custom RecursiveArrayIterator.
     * But it seems to be even slower.
     *
     * @see http://php.net/manual/en/class.recursivearrayiterator.php#106519
     *
     * @param mixed[] $array
     *   The input array.
     *
     * @return mixed[]
     *   The array with only one dimension.
     */
    protected function ensureFlatArray(array $array)
    {
        $flat = array();

        while ($array) {
            $value = array_shift($array);

            if (is_array($value)) {
                $array = array_merge($value, $array);
                continue;
            }

            $flat[] = $value;
        }

        return $flat;
    }

    /**
     * Make sure the value is an array.
     *
     * @param mixed $data
     *   The input value.
     *
     * @return string|null
     *   The input value in an array.
     */
    protected function ensureString($data)
    {
        $return = null;

        switch (\gettype($data)) {
            case 'string':
            case 'integer':
            case 'double':
                $return = $data;
                break;
            case 'object':
                if (\method_exists($data, '__toString')) {
                    $return = $data->__toString();
                }
                break;
            case 'boolean':
            case 'array':
            default:
                $return = null;
                break;
        }

        return $return;
    }

    /**
     * Preprocess values before they are returned to user.
     *
     * @param array $values
     *   The raw values.
     * @param string $name
     *   The name of the object.
     *
     * @return array|\drupol\htmltag\Attribute\AttributeInterface[]
     *   The values.
     */
    protected function preprocess(array $values, $name = null)
    {
        return $values;
    }

    /**
     * Sanitize a value.
     *
     * @param string|mixed $value
     *   The value to sanitize
     *
     * @return string|mixed
     *   The value sanitized.
     */
    abstract protected function escape($value);
}
