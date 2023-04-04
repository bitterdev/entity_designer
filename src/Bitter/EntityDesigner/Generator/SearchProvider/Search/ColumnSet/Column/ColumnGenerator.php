<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\SearchProvider\Search\ColumnSet\Column;

use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class ColumnGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $generatorItems = [];

        $tableAlias = $this->getTableAlias($this->textService->camelcase($this->entity->getHandle()));

        $fileName = $this->getSrcPath() . "/Search/" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "/ColumnSet/Column/IdColumn.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . "\\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\ColumnSet\\Column;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Column\Column;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Column\PagerColumnInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\ItemList\Pager\PagerProviderInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "List;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class IdColumn extends Column implements PagerColumnInterface\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getColumnKey()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return '" . $tableAlias . ".id';\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getColumnName()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('Id');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getColumnCallback()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return 'getId';\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @param " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "List \$itemList\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @param \$mixed " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @noinspection PhpDocSignatureInspection\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function filterListAtOffset(PagerProviderInterface \$itemList, \$mixed)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$query = \$itemList->getQueryObject();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$sort = \$this->getColumnSortDirection() == 'desc' ? '<' : '>';\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$where = sprintf('" . $tableAlias . ".id %s :id', \$sort);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$query->setParameter('id', \$mixed->getId());\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$query->andWhere(\$where);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        $generatorItems[] = new GeneratorItem($fileName, $code);

        foreach ($this->entity->getFields() as $field) {
            $fileName = $this->getSrcPath() . "/Search/" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "/ColumnSet/Column/" . ucfirst($this->textService->camelcase($field->getHandle())) . "Column.php";

            $requiresManager = false;

            if ($field->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST ||
                $field->getType() === FieldType::FIELD_TYPE_RADIO_BUTTONS ||
                $field->getType() === FieldType::FIELD_TYPE_SELECT_BOX ||
                $field->getType() === FieldType::FIELD_TYPE_FILE ||
                $field->getType() === FieldType::FIELD_TYPE_PAGE_SELECTOR ||
                $field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {

                $requiresManager = true;
            }


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
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . "\\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\ColumnSet\\Column;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Column\Column;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Column\PagerColumnInterface;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\ItemList\Pager\PagerProviderInterface;\n";

            if ($requiresManager) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\Field\\Manager;\n";
            }

            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "List;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class " . ucfirst($this->textService->camelcase($field->getHandle())) . "Column extends Column implements PagerColumnInterface\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getColumnKey()\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";

            if ($field->getType() === FieldType::FIELD_TYPE_FILE) {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return '" . $tableAlias . ".fID';\n";
            } else {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return '" . $tableAlias . "." . lcfirst($this->textService->camelcase($field->getHandle())) . "';\n";
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

            if ($field->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST || $field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function isColumnSortable()\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return false;\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getColumnName()\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('" . h($field->getLabel()) . "');\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getColumnCallback()\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";

            if ($requiresManager) {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return [Manager::class, 'get" . ucfirst($this->textService->camelcase($field->getHandle())) . "'];\n";
            } else {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return 'get" . ucfirst($this->textService->camelcase($field->getHandle())) . "';\n";
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @param " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "List \$itemList\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @param \$mixed " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @noinspection PhpDocSignatureInspection\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function filterListAtOffset(PagerProviderInterface \$itemList, \$mixed)\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$query = \$itemList->getQueryObject();\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$sort = \$this->getColumnSortDirection() == 'desc' ? '<' : '>';\n";

            if ($field->getType() === FieldType::FIELD_TYPE_FILE) {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$where = sprintf('" . $tableAlias . ".fID %s :" . $field->getHandle() . "', \$sort);\n";
            } else {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$where = sprintf('" . $tableAlias . "." . $field->getHandle() . " %s :" . $field->getHandle() . "', \$sort);\n";
            }

            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$query->setParameter('" . $field->getHandle() . "', \$mixed->get" . ucfirst($this->textService->camelcase($field->getHandle())) . "());\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$query->andWhere(\$where);\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

            $generatorItems[] = new GeneratorItem($fileName, $code);
        }

        return $generatorItems;
    }
}