<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\BlockType\ListBlock;

use Bitter\EntityDesigner\Enumeration\AssociationType;
use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class ViewGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_list/view.php";

        $hasFileFields = false;
        $hasCheckboxFields = false;
        $hasManyCollections = false;

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_FILE) {
                $hasFileFields = true;
            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                $hasCheckboxFields = true;
            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                if ($fieldEntity->getAssociationType() === AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY ||
                    $fieldEntity->getAssociationType() === AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY) {
                    $hasManyCollections = true;
                }
            }
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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "defined('C5_EXECUTE') or die('Access denied');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Legacy\Pagination;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Url;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use HtmlObject\Element;\n";

        if ($hasFileFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Entity\File\Version;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Entity\File\File;\n";
        }

        if ($hasCheckboxFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\Common\Collections\Collection;\n";
        }

        if ($hasManyCollections) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\PersistentCollection;\n";
        }

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . ";\n";
            }
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Page\Page;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var " . $this->textService->camelcase($this->entity->getHandle()) . "[] \$entries */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var Pagination \$pagination */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var int \$detailPage */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var int \$totalItems */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var bool \$displayPagination */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$orderBy */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$orderByDirection */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?php if (empty(\$entries)): ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div class=\"alert alert-warning\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo t('Currently there are no items available.'); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?php else: ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<table class=\"table table-striped\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<thead>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<tr>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$columns = [\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->isDisplayInListView()) {
                $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\"e." . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\" => t('" . h($fieldEntity->getLabel()) . "'),\n";
            }
        }

        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "];\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$curPage = Page::getCurrentPage();\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "foreach (\$columns as \$sortId => \$label) {\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$sortDirection = \"ASC\";\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$columnClassName = \"\";\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "if (\$orderBy === \$sortId) {\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "if (\$orderByDirection === \"ASC\") {\n";
        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\$columnClassName = \"ccm-results-list-active-sort-asc\";\n";
        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\$sortDirection = \"DESC\";\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\$columnClassName = \"ccm-results-list-active-sort-desc\";\n";
        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\$sortDirection = \"ASC\";\n";
        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$sortUrl = (string)Url::to(\$curPage)->setQuery([\"ccm_order_by\" => \$sortId, \"ccm_order_by_direction\" => \$sortDirection]);\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$link = new Element(\"a\");\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$link->setAttribute(\"href\", \$sortUrl);\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$link->setValue(\$label);\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$th = new Element(\"th\");\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$th->addClass(\$columnClassName);\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$th->appendChild(\$link);\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "echo \$th;\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</tr>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</thead>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<tbody>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php foreach (\$entries as \$entry): ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$detailPageObject = Page::getByID(\$detailPage);\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "/** @noinspection PhpUndefinedMethodInspection */\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "if (\$detailPageObject instanceof Page && !\$detailPageObject->isError()) {\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$detailPageUrl = (string)Url::to(\$detailPageObject, \"display_entry\", \$entry->getId());\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$detailPageUrl = \"javascript:void();\";\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<tr>\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->isDisplayInListView()) {
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<td>\n";
                $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "<a href=\"<?" . "php echo \$detailPageUrl; ?>\">\n";

                switch ($fieldEntity->getType()) {
                    case  FieldType::FIELD_TYPE_CHECKBOX_LIST:
                        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<?php\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "if (\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof Collection) {\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "\$selectedValues = [];\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "foreach(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->getValues() as \$optionValue) {\n";
                        $code .= str_repeat(" ", ($indent + 10) * $indentSize) . "\$selectedValues[] = \$optionValue->getValue();\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "echo implode(\", \", \$selectedValues);\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "} else {\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "echo t('(Empty)');\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "?>\n";
                        break;

                    case FieldType::FIELD_TYPE_FILE:
                        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<?php\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\$fileName = t('(Empty)');\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "if (\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof File) {\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "\$approvedVersion = \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->getApprovedVersion();\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 10) * $indentSize) . "if (\$approvedVersion instanceof Version) {\n";
                        $code .= str_repeat(" ", ($indent + 11) * $indentSize) . "\$fileName = \$approvedVersion->getFileName();\n";
                        $code .= str_repeat(" ", ($indent + 10) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "echo \$fileName;\n";
                        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "?>\n";
                        break;

                    case FieldType::FIELD_TYPE_PAGE_SELECTOR:
                        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<?php\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\$pageName = t('(Empty)');\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\$page = Page::getByID(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "());\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "/** @noinspection PhpUndefinedMethodInspection */\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "if (\$page instanceof Page && !\$page->isError()) {\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "\$pageName = \$page->getCollectionName();\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "echo \$pageName;\n";
                        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "?>\n";
                        break;

                    case  FieldType::FIELD_TYPE_ASSOCIATION:
                        switch($fieldEntity->getAssociationType()) {
                            case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                            case AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY:
                                $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<?php\n";
                                $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "if (\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof PersistentCollection) {\n";
                                $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "\$selectedValues = [];\n";
                                $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "\n";
                                $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "foreach(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->getValues() as \$optionValue) {\n";
                                $code .= str_repeat(" ", ($indent + 10) * $indentSize) . "/** @var " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . " \$optionValue */\n";
                                $code .= str_repeat(" ", ($indent + 10) * $indentSize) . "\$selectedValues[] = \$optionValue->getDisplayValue();\n";
                                $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "}\n";
                                $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "\n";
                                $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "echo implode(\", \", \$selectedValues);\n";
                                $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "} else {\n";
                                $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "echo t('(Empty)');\n";
                                $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "}\n";
                                $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "?>\n";
                                break;

                            case AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE:
                                $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<?php \n";
                                $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "if (\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . ") {\n";
                                $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "echo \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->getDisplayValue();\n";
                                $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "} else {\n";
                                $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "echo t(\"(Empty)\");\n";
                                $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "}\n";
                                $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "?>\n";
                                break;
                        }

                        break;

                    case FieldType::FIELD_TYPE_TEXT:
                    case FieldType::FIELD_TYPE_EMAIL:
                    case FieldType::FIELD_TYPE_TELEPHONE:
                    case FieldType::FIELD_TYPE_PASSWORD:
                    case FieldType::FIELD_TYPE_WEB_ADDRESS:
                    case FieldType::FIELD_TYPE_RADIO_BUTTONS:
                    case FieldType::FIELD_TYPE_SELECT_BOX:
                        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<?php\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "if (strlen(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()) > 0) {\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "echo \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "();\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "} else {\n";
                        $code .= str_repeat(" ", ($indent + 9) * $indentSize) . "echo t('(Empty)');\n";
                        $code .= str_repeat(" ", ($indent + 8) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "?>\n";
                        break;

                    default:
                        $code .= str_repeat(" ", ($indent + 7) * $indentSize) . "<?php echo \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "(); ?>\n";
                        break;

                }

                $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "</a>\n";
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "</td>\n";
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
            }
        }

        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</tr>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php endforeach; ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</tbody>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</table>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php if (\$displayPagination): ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div class=\"text-center\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<ul class=\"pagination\">\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<li>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<?php echo \$pagination->getPrevious(); ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</li>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo \$pagination->getPages(\"li\"); ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<li>\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<?php echo \$pagination->getNext(); ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</li>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</ul>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php endif; ?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?php endif; ?>\n";

        return [new GeneratorItem($fileName, $code)];
    }
}