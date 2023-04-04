<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\Element;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class HeaderElementGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/elements/" . $this->entity->getHandle() . "/header/search.php";

        $code = "";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?php\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "defined('C5_EXECUTE') or die(\"Access Denied.\");\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Form\Service\Form;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Application;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Url;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$headerSearchAction */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$app = Application::getFacadeApplication();\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var Form \$form */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$form = \$app->make(Form::class);\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div class=\"ccm-header-search-form ccm-ui\" data-header=\"user-manager\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<form method=\"get\" class=\"row row-cols-auto g-0 align-items-center\" action=\"<?php echo \$headerSearchAction ?>\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div class=\"ccm-header-search-form-input input-group\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php if (isset(\$query)): ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<a href=\"javascript:void(0);\"\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "data-launch-dialog=\"advanced-search\"\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "class=\"ccm-header-launch-advanced-search\"\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "data-advanced-search-dialog-url=\"<?php echo Url::to('/ccm/system/dialogs/" . $this->entity->getHandle() . "/advanced_search') ?>\"\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "data-advanced-search-query=\"advanced-search-query\">\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<?php echo t('Advanced') ?>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<script type=\"text/concrete-query\" data-query=\"advanced-search-query\">\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<?php echo \$query ?>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "</script>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</a>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php else: ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<a href=\"javascript:void(0);\"\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "data-launch-dialog=\"advanced-search\"\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "class=\"ccm-header-launch-advanced-search\"\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "data-advanced-search-dialog-url=\"<?php echo Url::to('/ccm/system/dialogs/" . $this->entity->getHandle() . "/advanced_search') ?>\">\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<?php echo t('Advanced') ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</a>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php endif; ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "echo \$form->search('keywords', [\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "'placeholder' => t('Search'),\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "'class' => 'form-control border-end-0',\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "'autocomplete' => 'off'\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "]);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<button type=\"submit\" class=\"input-group-icon\">\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<svg width=\"16\" height=\"16\">\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<use xlink:href=\"#icon-search\"/>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</svg>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</button>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</form>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<script>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "(function (\$) {\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$(function () {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "ConcreteEvent.subscribe('SavedSearchCreated', function () {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "window.location.reload();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "});\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "ConcreteEvent.subscribe('SavedPresetSubmit', function (e, url) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "window.location.href = url;\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "});\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "});\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "})(jQuery);\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</script>\n";

        return [new GeneratorItem($fileName, $code)];
    }
}