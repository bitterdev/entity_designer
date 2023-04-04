<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\BlockType\ListBlock;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class DatabaseSchemaGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_list/db.xml";

        $code = "";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<schema xmlns=\"http://www.concrete5.org/doctrine-xml/0.5\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "xsi:schemaLocation=\"http://www.concrete5.org/doctrine-xml/0.5 http://concrete5.github.io/doctrine-xml/doctrine-xml-0.5.xsd\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<table name=\"bt" . $this->textService->camelcase($this->entity->getHandle()) . "List\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<field name=\"bID\" type=\"integer\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<unsigned/>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<key/>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</field>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<field name=\"displayLimit\" type=\"integer\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<default value=\"20\"/>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</field>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<field name=\"detailPage\" type=\"integer\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<unsigned/>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<default value=\"0\"/>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<notnull/>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</field>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</table>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</schema>\n";

        $table = "bt" . $this->textService->camelcase($this->entity->getHandle())  . "List";

        return [new GeneratorItem($fileName, $code, $table)];
    }
}