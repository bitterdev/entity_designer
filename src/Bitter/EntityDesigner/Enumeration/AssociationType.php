<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Enumeration;

abstract class AssociationType
{
    const ASSOCIATION_TYPE_ONE_TO_ONE = 'one_to_one';
    const ASSOCIATION_TYPE_ONE_TO_MANY = 'one_to_many';
    const ASSOCIATION_TYPE_MANY_TO_MANY = 'many_to_many';
}