<?php

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

/** @noinspection PhpUndefinedClassInspection */

namespace Bitter\EntityDesigner\NodeVisitor;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

class AddPackageOnStartNodeVisitor extends NodeVisitorAbstract
{
    protected $className;

    public function __construct(
        $className
    )
    {
        $this->className = $className;
    }

    function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\ClassMethod) {
            if ($node->name->name === "on_start") {
                $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
                $installStmts = $parser->parse("<?php \$entityDesignerServiceProvider = \$this->app->make(" . $this->className . "::class); \$entityDesignerServiceProvider->register(); ?>");
                $node->stmts = array_merge($node->stmts, $installStmts);
            }
        }
    }
}