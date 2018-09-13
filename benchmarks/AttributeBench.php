<?php

namespace drupol\htmltag\benchmarks;

use drupol\htmltag\Attribute\Attribute;

/**
 * @Groups({"Attribute"})
 */
class AttributeBench extends AbstractBench
{
    /**
     * @Revs({1, 100, 1000})
     * @Iterations(5)
     */
    public function benchAttributeRender()
    {
        foreach ($this->getAttributes() as $name => $value) {
            $attribute = new Attribute($name, $value);
            $attribute->render();
        }
    }
}
