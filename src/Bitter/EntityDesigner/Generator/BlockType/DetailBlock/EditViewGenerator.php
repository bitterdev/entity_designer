<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\BlockType\DetailBlock;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class EditViewGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_detail/edit.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "defined('C5_EXECUTE') or die('Access denied');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Block\View\BlockView;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var BlockView \$view */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var array \$entryModes */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$entryMode */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$specificEntryId */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var array \$entries */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div class=\"form-group\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$form->label(\"entryMode\", t('Details')); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$form->select(\"entryMode\", \$entryModes, \$entryMode); ?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div id=\"specificEntryIdContainer\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div class=\"form-group\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$form->label(\"specificEntryId\", t('Entry')); ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$form->select(\"specificEntryId\", \$entries, \$specificEntryId); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<!--suppress JSUnresolvedVariable -->\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<script>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "(function (\$) {\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$(function () {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$(\"select[name=entryMode]\").bind(\"change\", function (e) {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "e.preventDefault();\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (\$(\"select[name=entryMode]\").val() === \"S\") {\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$(\"#specificEntryIdContainer\").removeClass(\"d-none\");\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$(\"#specificEntryIdContainer\").addClass(\"d-none\");\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "return false;\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}).trigger(\"change\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "});\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "})(jQuery);\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</script>";

        return [new GeneratorItem($fileName, $code)];
    }
}