<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\SearchProvider\Search\Field;

use Bitter\EntityDesigner\Enumeration\AssociationType;
use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class ManagerGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getSrcPath() . "/Search/" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "/Field/Manager.php";

        $hasFileFields = false;
        $hasPageFields = false;
        $hasDoctrineCollections = false;
        $associatedEntities = [];

        foreach ($this->entity->getFields() as $field) {
            if ($field->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                $associatedEntities[] = $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($field->getHandle()) . "Options";
            } else if ($field->getType() === FieldType::FIELD_TYPE_FILE) {
                $hasFileFields = true;
            } else if ($field->getType() === FieldType::FIELD_TYPE_PAGE_SELECTOR) {
                $hasPageFields = true;
            } else if ($field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                if ($field->getAssociationType() === AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY ||
                    $field->getAssociationType() === AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY) {

                    $hasDoctrineCollections = true;

                }

                $associatedEntities[] = $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle());
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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . "\\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\Field;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Field\Manager as FieldManager;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";

        if ($hasFileFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Entity\File\File;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Entity\File\Version;\n";
        }

        if ($hasDoctrineCollections) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\PersistentCollection;\n";
        }

        if ($hasPageFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Page\Page;\n";
        }

        foreach ($associatedEntities as $associatedEntity) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $associatedEntity . ";\n";
        }

        foreach ($this->entity->getFields() as $field) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\Field\\Field\\" . ucfirst($this->textService->camelcase($field->getHandle())) . "Field;\n";
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class Manager extends FieldManager\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        foreach ($this->entity->getFields() as $field) {
            if ($field->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . $this->textService->camelcase($this->entity->getHandle()) . " \$mixed\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return string\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function get" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$mixed) {\n";

                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$values = [\n";

                foreach ($field->getOptions() as $option) {
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"" . h($option->getValue()) . "\" => t(\"" . t($option->getLabel()) . "\"),\n";
                }

                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "];\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$selectedValues = [];\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var " . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($field->getHandle()) . "Options \$listItem */\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "foreach(\$mixed->get" . ucfirst($this->textService->camelcase($field->getHandle())) . "() as \$listItem) {\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (isset(\$values[\$listItem->getValue()])) {\n";
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$selectedValues[] = \$values[\$listItem->getValue()];\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (count(\$selectedValues)>  0) {\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return implode(\", \", \$selectedValues);\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "} else {\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return '';\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";


            } else if ($field->getType() === FieldType::FIELD_TYPE_RADIO_BUTTONS ||
                $field->getType() === FieldType::FIELD_TYPE_SELECT_BOX) {


                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . $this->textService->camelcase($this->entity->getHandle()) . " \$mixed\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return string\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function get" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$mixed) {\n";

                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$values = [\n";

                foreach ($field->getOptions() as $option) {
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"" . h($option->getValue()) . "\" => t(\"" . t($option->getLabel()) . "\"),\n";
                }

                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "];\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$values[\$mixed->get" . ucfirst($this->textService->camelcase($field->getHandle())) . "()];\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";


            } else if ($field->getType() === FieldType::FIELD_TYPE_FILE) {

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . $this->textService->camelcase($this->entity->getHandle()) . " \$mixed\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return string\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function get" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$mixed)\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$file = \$mixed->get" . ucfirst($this->textService->camelcase($field->getHandle())) . "();\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$file instanceof File) {\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$approvedVersion = \$file->getApprovedVersion();\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (\$approvedVersion instanceof Version) {\n";
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "return \$approvedVersion->getFileName();\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return '';\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

            } else if ($field->getType() === FieldType::FIELD_TYPE_PAGE_SELECTOR) {

                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . $this->textService->camelcase($this->entity->getHandle()) . " \$mixed\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return string\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function get" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$mixed) {\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$page = Page::getByID(\$mixed->get" . ucfirst($this->textService->camelcase($field->getHandle())) . "());\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (!\$page->isError()) {\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return \$page->getCollectionName();\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "} else {\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return '';\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

            } else if ($field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {

                if ($field->getAssociationType() === AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE) {

                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . $this->textService->camelcase($this->entity->getHandle()) . " \$mixed\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return string\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function get" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$mixed)\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entity = \$mixed->get" . ucfirst($this->textService->camelcase($field->getHandle())) . "();\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$entity instanceof " . ucfirst($this->textService->camelcase($field->getAssociatedEntity()->getHandle())) . ") {\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return \$entity->getDisplayValue();\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "} else {\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return '';\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

                } else {

                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . $this->textService->camelcase($this->entity->getHandle()) . " \$mixed\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return string\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function get" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$mixed)\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";

                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$selectedValues = [];\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entities = \$mixed->get" . ucfirst($this->textService->camelcase($field->getHandle())) . "();\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (is_array(\$entities) || \$entities instanceof PersistentCollection) {\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "foreach(\$entities as \$entity) {\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (\$entity instanceof " . ucfirst($this->textService->camelcase($field->getAssociatedEntity()->getHandle())) . ") {\n";
                    $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$selectedValues[] =  \$entity->getDisplayValue();\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (count(\$selectedValues)>  0) {\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return implode(\", \", \$selectedValues);\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "} else {\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return '';\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";

                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

                }

            }
        }

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function __construct()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$properties = [\n";

        foreach ($this->entity->getFields() as $field) {
            if ($field->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST || $field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                // @todo: add support for checkbox lists and associations
            } else {
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "new " . ucfirst($this->textService->camelcase($field->getHandle())) . "Field(),\n";
            }
        }

        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "];\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->addGroup(t('Core Properties'), \$properties);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}