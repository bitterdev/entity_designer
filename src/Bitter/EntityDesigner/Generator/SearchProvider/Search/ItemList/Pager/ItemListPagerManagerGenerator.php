<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\SearchProvider\Search\ItemList\Pager;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class ItemListPagerManagerGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getSrcPath() . "/Search/ItemList/Pager/Manager/" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "ListPagerManager.php";

        $code = "<?php\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " *\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * " . t("This file was build with the Entity Designer add-on.") . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " *\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * https://www.concrete5.org/marketplace/addons/entity-designer\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " *\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection DuplicatedCode */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . "\\Search\\ItemList\\Pager\\Manager;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\ColumnSet\\Available;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\ItemList\Pager\PagerProviderInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\ItemList\Pager\Manager\AbstractPagerManager;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\EntityManagerInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Application;\n";

        $tableAlias = $this->getTableAlias($this->textService->camelcase($this->entity->getHandle()));

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "ListPagerManager extends AbstractPagerManager\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** \n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . $this->textService->camelcase($this->entity->getHandle()) . " \$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return int \n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getCursorStartValue(\$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . ")\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . "->getId();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getCursorObject(\$cursor)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$app = Application::getFacadeApplication();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var EntityManagerInterface \$entityManager */\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entityManager = \$app->make(EntityManagerInterface::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$entityManager->getRepository(" . $this->textService->camelcase($this->entity->getHandle()) . "::class)->findOneBy([\"id\" => \$cursor]);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getAvailableColumnSet()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return new Available();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function sortListByCursor(PagerProviderInterface \$itemList, \$direction)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @noinspection PhpUndefinedMethodInspection */\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$itemList->getQueryObject()->addOrderBy('" . $tableAlias . ".id', \$direction);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}