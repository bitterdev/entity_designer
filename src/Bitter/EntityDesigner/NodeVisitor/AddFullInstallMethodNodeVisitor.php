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

class AddFullInstallMethodNodeVisitor extends NodeVisitorAbstract
{
    protected $fileName;

    public function __construct(
        $fileName
    )
    {
        $this->fileName = $fileName;
    }

    function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_ && $node->name == "Controller") {
            $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
            $installStmts = $parser->parse("<?php function install() { parent::install();\$this->installContentFile('" . $this->fileName . "');\$this->app->make(\Concrete\Core\Application\UserInterface\Dashboard\Navigation\NavigationCache::class)->clear(); }?>");
            $node->stmts = array_merge($node->stmts, $installStmts);
        }
    }
}