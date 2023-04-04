<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\BlockType\DetailBlock;

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
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_detail/view.php";

        $hasFileFields = false;
        $hasPageSelectorFields = false;
        $hasCheckboxFields = false;
        $hasManyCollections = false;

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_FILE) {
                $hasFileFields = true;
            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_PAGE_SELECTOR) {
                $hasPageSelectorFields = true;
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

        if ($hasFileFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Entity\File\Version;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Entity\File\File;\n";
        }

        if ($hasPageSelectorFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Page\Page;\n";
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

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var " . $this->textService->camelcase($this->entity->getHandle()) . " \$entry */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?php if (!\$entry instanceof " . $this->textService->camelcase($this->entity->getHandle()) . "): ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<p>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo t('No entry selected.'); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</p>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?php else: ?>\n";

        foreach ($this->entity->getFields() as $fieldEntity) {

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div class=\"form-group\">\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$form->label(\n";
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"" . h($fieldEntity->getHandle()) . "\",\n";
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "t('" . h($fieldEntity->getLabel()) . "'),\n";
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "[\n";
            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\"class\" => \"control-label\"\n";
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "]\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "); ?>\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div>\n";

            switch ($fieldEntity->getType()) {
                case  FieldType::FIELD_TYPE_CHECKBOX_LIST:
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof Collection) {\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$selectedValues = [];\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "foreach(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->getValues() as \$optionValue) {\n";
                    $code .= str_repeat(" ", ($indent + 60) * $indentSize) . "\$selectedValues[] = \$optionValue->getValue();\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "echo implode(\", \", \$selectedValues);\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "} else {\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "echo t('(Empty)');\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "?>\n";
                    break;

                case FieldType::FIELD_TYPE_FILE:
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$fileName = t('(Empty)');\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof File) {\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$approvedVersion = \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->getApprovedVersion();\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "if (\$approvedVersion instanceof Version) {\n";
                    $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$fileName = \$approvedVersion->getFileName();\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "echo \$fileName;\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "?>\n";
                    break;

                case FieldType::FIELD_TYPE_PAGE_SELECTOR:
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$pageName = t('(Empty)');\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$page = Page::getByID(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "());\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "/** @noinspection PhpUndefinedMethodInspection */\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (\$page instanceof Page && !\$page->isError()) {\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$pageName = \$page->getCollectionName();\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "echo \$pageName;\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "?>\n";
                    break;

                case  FieldType::FIELD_TYPE_ASSOCIATION:
                    switch($fieldEntity->getAssociationType()) {
                        case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                        case AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY:
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof PersistentCollection) {\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$selectedValues = [];\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "foreach(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->getValues() as \$optionValue) {\n";
                            $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "/** @var " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . " \$optionValue */\n";
                            $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$selectedValues[] = \$optionValue->getDisplayValue();\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "}\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "echo implode(\", \", \$selectedValues);\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "} else {\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "echo t('(Empty)');\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "?>\n";
                            break;

                        case AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE:
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php \n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . ") {\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "echo \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->getDisplayValue();\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "} else {\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "echo t(\"(Empty)\");\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "?>\n";
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
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (strlen(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()) > 0) {\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "echo \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "();\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "} else {\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "echo t('(Empty)');\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "?>\n";
                    break;

                default:
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "(); ?>\n";
                    break;

            }

            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</div>\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?php endif; ?>";

        return [new GeneratorItem($fileName, $code)];
    }
}