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

class EditViewGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_list/edit.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Form\Service\Form;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Form\Service\Widget\PageSelector;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Application;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var BlockView \$view */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var int \$displayLimit */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var int \$detailPage */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$app = Application::getFacadeApplication();\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var PageSelector \$pageSelector */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$pageSelector = \$app->make(PageSelector::class);\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var Form \$form */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$form = \$app->make(Form::class);\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div class=\"form-group\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$form->label(\"displayLimit\", t('Display Limit')); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$form->number(\"displayLimit\", \$displayLimit, [\"step\" => 1, \"min\" => 0]); ?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div class=\"form-group\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$form->label(\"detailPage\", t('Detail Page')); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$pageSelector->selectPage(\"detailPage\", \$detailPage); ?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";

        return [new GeneratorItem($fileName, $code)];
    }
}