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

class RemovePackageOnStartNodeVisitor extends NodeVisitorAbstract
{
    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
    function leaveNode(Node $node)
    {
        // remove $entityDesignerServiceProvider = $this->app->make(\MyPackage\EntityDesignerServiceProvider::class);
        if ($node instanceof Node\Stmt\Expression &&
            $node->expr instanceof Node\Expr\Assign &&
            $node->expr->var instanceof Node\Expr\Variable &&
            $node->expr->var->name === "entityDesignerServiceProvider"
        ) {
            return NodeTraverser::REMOVE_NODE;
        }

        // remove $entityDesignerServiceProvider->register();
        if ($node instanceof Node\Stmt\Expression &&
            $node->expr instanceof Node\Expr\MethodCall &&
            $node->expr->var instanceof Node\Expr\Variable &&
            $node->expr->var->name === "entityDesignerServiceProvider"
        ) {
            return NodeTraverser::REMOVE_NODE;
        }
    }
}