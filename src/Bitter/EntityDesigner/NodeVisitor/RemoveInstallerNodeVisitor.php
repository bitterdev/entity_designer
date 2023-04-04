<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

/** @noinspection PhpInconsistentReturnPointsInspection */
/** @noinspection PhpUndefinedClassInspection */
namespace Bitter\EntityDesigner\NodeVisitor;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

class RemoveInstallerNodeVisitor extends NodeVisitorAbstract
{
    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
    function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Expression &&
            $node->expr instanceof Node\Expr\MethodCall &&
            $node->expr->var instanceof Node\Expr\Variable &&
            $node->expr->var->name === "this" &&
            $node->expr->name instanceof Node\Identifier &&
            $node->expr->name->name === "installContentFile" &&
            is_array($node->expr->args) &&
            isset($node->expr->args[0]) &&
            $node->expr->args[0] instanceof Node\Arg &&
            $node->expr->args[0]->value instanceof Node\Scalar\String_ &&
            substr($node->expr->args[0]->value->value, 0, 8) === "install_" &&
            strlen($node->expr->args[0]->value->value) === 24
        ) {
            return NodeTraverser::REMOVE_NODE;
        }
    }
}