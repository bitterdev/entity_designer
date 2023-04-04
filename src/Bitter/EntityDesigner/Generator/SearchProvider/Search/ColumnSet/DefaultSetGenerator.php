<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\SearchProvider\Search\ColumnSet;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class DefaultSetGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getSrcPath() . "/Search/" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "/ColumnSet/DefaultSet.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . "\\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\ColumnSet;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\ColumnSet\\Column\\IdColumn;\n";

        $tableAlias = $this->getTableAlias($this->textService->camelcase($this->entity->getHandle()));

        foreach ($this->entity->getFields() as $field) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\ColumnSet\\Column\\" . ucfirst($this->textService->camelcase($field->getHandle())) . "Column;\n";
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class DefaultSet extends ColumnSet\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$attributeClass = 'CollectionAttributeKey';\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function __construct()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->addColumn(new IdColumn());\n";

        foreach ($this->entity->getFields() as $field) {
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->addColumn(new " . ucfirst($this->textService->camelcase($field->getHandle())) . "Column());\n";
        }

        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$id = \$this->getColumnByKey('" . $tableAlias . ".id');\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->setDefaultSortColumn(\$id, 'desc');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}