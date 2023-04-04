<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\SinglePage;

use Bitter\EntityDesigner\Enumeration\AssociationType;
use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class ListViewGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/single_pages" . $this->getRelPath() . "/" . $this->entity->getHandle() . ".php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "defined('C5_EXECUTE') or die('Access denied');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection DuplicatedCode */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Application\UserInterface\ContextMenu\MenuInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Url;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Search\\" . $this->textService->camelcase($this->entity->getHandle()) . "\Result\Column;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Search\\" . $this->textService->camelcase($this->entity->getHandle()) . "\Result\Item;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Search\\" . $this->textService->camelcase($this->entity->getHandle()) . "\Result\ItemColumn;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Search\\" . $this->textService->camelcase($this->entity->getHandle()) . "\Result\Result;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "Menu;\n";

        if (strlen($this->entity->getListViewHelpText()) > 0) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Application;\n";
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string|null \$class */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var MenuInterface \$menu */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var Result \$result */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";

        if (strlen($this->entity->getListViewHelpText()) > 0) {
            $code .= str_repeat(" ", $indent * $indentSize) . "\$app = Application::getFacadeApplication();\n";
            $code .= str_repeat(" ", $indent * $indentSize) . "\$app->make('help')->display(t('" . nl2br(h($this->entity->getListViewHelpText())) . "'));\n";
            $code .= str_repeat(" ", $indent * $indentSize) . "\n";
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div id=\"ccm-search-results-table\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<table class=\"ccm-search-results-table\" data-search-results=\"" . $this->entity->getHandle() . "s\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<thead>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<tr>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<th class=\"ccm-search-results-bulk-selector\">\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<div class=\"btn-group dropdown\">\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<span class=\"btn btn-secondary\" data-search-checkbox-button=\"select-all\">\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<!--suppress HtmlFormInputWithoutLabel -->\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<input type=\"checkbox\" data-search-checkbox=\"select-all\"/>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "</span>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<button\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "type=\"button\"\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "disabled=\"disabled\"\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "data-search-checkbox-button=\"dropdown\"\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "class=\"btn btn-secondary dropdown-toggle dropdown-toggle-split\"\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "data-bs-toggle=\"dropdown\"\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "data-reference=\"parent\">\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<span class=\"sr-only\">\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<?php echo t(\"Toggle Dropdown\"); ?>\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "</span>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "</button>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</th>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php foreach (\$result->getColumns() as \$column): ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php /** @var Column \$column */ ?>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<th class=\"<?php echo \$column->getColumnStyleClass() ?>\">\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<?php if (\$column->isColumnSortable()): ?>\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<a href=\"<?php echo \$column->getColumnSortURL() ?>\">\n";
        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "<?php echo \$column->getColumnTitle() ?>\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "</a>\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<?php else: ?>\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<span>\n";
        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "<?php echo \$column->getColumnTitle() ?>\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "</span>\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<?php endif; ?>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "</th>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php endforeach; ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</tr>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</thead>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<tbody>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php foreach (\$result->getItems() as \$item) { ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "/** @var Item \$item */\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "/** @var " . $this->textService->camelcase($this->entity->getHandle()) . " \$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . " */\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . " = \$item->getItem();\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<tr data-details-url=\"javascript:void(0)\">\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<td class=\"ccm-search-results-checkbox\">\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<?php if (\$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . " instanceof " . $this->textService->camelcase($this->entity->getHandle()) . ") { ?>\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<!--suppress HtmlFormInputWithoutLabel -->\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<input data-search-checkbox=\"individual\"\n";
        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . " type=\"checkbox\"\n";
        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . " data-item-id=\"<?php echo \$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . "->getId() ?>\"/>\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<?php } ?>\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "</td>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<?php foreach (\$item->getColumns() as \$column) { ?>\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<?php /** @var ItemColumn \$column */ ?>\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<td class=\"<?php echo \$class ? \$class : '' ?>\">\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<?php echo \$column->getColumnValue(); ?>\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "</td>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<?php } ?>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<?php \$menu = new " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "Menu(\$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . "); ?>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<?php if (\$menu) { ?>\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<td class=\"ccm-search-results-menu-launcher\">\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<div class=\"dropdown\" data-menu=\"search-result\">\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<button class=\"btn btn-icon\"\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "data-boundary=\"viewport\"\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "type=\"button\"\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "data-bs-toggle=\"dropdown\"\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "aria-haspopup=\"true\"\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "aria-expanded=\"false\">\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<svg width=\"16\" height=\"4\">\n";
        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "<use xlink:href=\"#icon-menu-launcher\"/>\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "</svg>\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "</button>\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<?php echo \$menu->getMenuElement(); ?>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</td>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php } ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</tr>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php } ?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</tbody>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</table>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<script>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "(function (\$) {\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$(function () {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "let searchResultsTable = new window.ConcreteSearchResultsTable(\$(\"#ccm-search-results-table\"));\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "searchResultsTable.setupBulkActions();\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$('#ccm-search-results-table').on('click', 'a[data-id]', function () {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "window.location.href = '<?=rtrim(URL::to('" . $this->getRelPath() . "/" . $this->entity->getHandle() . "', 'edit'), '/')?>/' + \$(this).attr('data-id');\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "return false;\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "});\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "});\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "})(jQuery);\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</script>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?php echo \$result->getPagination()->renderView('dashboard'); ?>\n";

        return [new GeneratorItem($fileName, $code)];
    }
}