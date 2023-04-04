<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator;

interface GeneratorInterface
{
    /**
     * @param int $indent
     * @param int $indentSize
     * @return GeneratorItem[]
     */
    public function build($indent, $indentSize);

    public function getEntity();

    public function setEntity($entity);
}