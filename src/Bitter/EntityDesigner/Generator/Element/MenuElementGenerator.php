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

class MenuElementGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/elements/" . $this->entity->getHandle() . "/header/menu.php";

        $code = "";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?php\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "defined('C5_EXECUTE') or die(\"Access Denied.\");\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Url;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var \$urlHelper Url */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div class=\"row row-cols-auto align-items-center\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php if (!empty(\$itemsPerPageOptions)) { ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div class=\"dropdown\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<button\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "type=\"button\"\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "class=\"btn btn-secondary p-2 dropdown-toggle\"\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "data-bs-toggle=\"dropdown\"\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "aria-haspopup=\"true\"\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "aria-expanded=\"false\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<span id=\"selected-option\">\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo \$itemsPerPage; ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</span>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</button>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<ul class=\"dropdown-menu\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<li class=\"dropdown-header\">\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo t('Items per page') ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</li>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php foreach (\$itemsPerPageOptions as \$itemsPerPageOption) { ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$url = \$urlHelper->setVariable([\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "'itemsPerPage' => \$itemsPerPageOption\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "]);\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<li data-items-per-page=\"<?php echo \$itemsPerPageOption; ?>\">\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<a class=\"dropdown-item <?php echo (\$itemsPerPageOption === \$itemsPerPage) ? 'active' : ''; ?>\"\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "href=\"<?php echo \$url ?>\">\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<?php echo \$itemsPerPageOption; ?>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "</a>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</li>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php } ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</ul>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php } ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<ul class=\"ccm-dashboard-header-icons\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<li>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<a class=\"ccm-hover-icon\" title=\"<?php echo h(t('New Entry')) ?>\" href=\"<?php echo Url::to(\"" .  $this->getRelPath() . "/" . $this->entity->getHandle() . "/add\");?>\">\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<i class=\"fas fa-plus\" aria-hidden=\"true\"></i>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</a>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</li>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</ul>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";

        return [new GeneratorItem($fileName, $code)];
    }
}