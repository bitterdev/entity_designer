<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\SearchProvider\Search\Field\Field;

use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Enumeration\FileType;
use Bitter\EntityDesigner\Enumeration\PageSelectorStyle;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class FieldGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $generatorItems = [];

        foreach ($this->entity->getFields() as $field) {
            $fileName = $this->getSrcPath() . "/Search/" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "/Field/Field/" . ucfirst($this->textService->camelcase($field->getHandle())) . "Field.php";

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
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . "\\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\Field\\Field;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";

            if ($field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\EntityManagerInterface;\n";
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . ";\n";
            }

            if ($field->getType() === FieldType::FIELD_TYPE_PAGE_SELECTOR) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Form\Service\Widget\PageSelector;\n";
            } else if ($field->getType() === FieldType::FIELD_TYPE_FILE) {
                $code .= str_repeat(" ", $indent * $indentSize) . "use Concrete\Core\Application\Service\FileManager;\n";
            } else {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Form\Service\Form;\n";
            }

            if ($field->getType() === FieldType::FIELD_TYPE_FILE) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\File\File;\n";
            }

            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Field\AbstractField;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\ItemList\ItemList;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Application;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "List;\n";


            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class " . ucfirst($this->textService->camelcase($field->getHandle())) . "Field extends AbstractField\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$requestVariables = [\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "'" . lcfirst($this->textService->camelcase($field->getHandle())) . "'\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "];\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getKey()\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return '" . lcfirst($this->textService->camelcase($field->getHandle())) . "';\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getDisplayName()\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('" . t($field->getLabel()) . "');\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "List \$list\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @noinspection PhpDocSignatureInspection\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function filterList(ItemList \$list)\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";

            if ($field->getType() === FieldType::FIELD_TYPE_FILE) {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$list->filterBy" . ucfirst($this->textService->camelcase($field->getHandle())) . "(File::getById(\$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']));\n";
            } else {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$list->filterBy" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function renderSearchField()\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$app = Application::getFacadeApplication();\n";

            if ($field->getType() === FieldType::FIELD_TYPE_PAGE_SELECTOR) {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var \$pageSelector PageSelector */\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$pageSelector = \$app->make(PageSelector::class);\n";

                switch ($field->getPageSelectorStyle()) {
                    case PageSelectorStyle::PAGE_SELECTOR_STYLE_QUICK_SELECT:
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$pageSelector->quickSelect(\"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                        break;

                    case PageSelectorStyle::PAGE_SELECTOR_STYLE_SELECT_PAGE:
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$pageSelector->selectPage(\"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                        break;

                    case PageSelectorStyle::PAGE_SELECTOR_STYLE_SELECT_FROM_SITEMAP:
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$pageSelector->selectFromSitemap(\"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                        break;
                }

            } else if ($field->getType() === FieldType::FIELD_TYPE_FILE) {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var \$fileManager FileManager */\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$fileManager = \$app->make(FileManager::class);\n";

                $placeholder = "Please choose";

                if (strlen($field->getPlaceholder()) > 0) {
                    $placeholder = $field->getPlaceholder();
                }

                switch ($field->getAllowedFileType()) {
                    case FileType::FILE_TYPE_ALL:
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$fileManager->image(\"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", t('" . h($placeholder) . "'), \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                        break;

                    case FileType::FILE_TYPE_VIDEO:
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$fileManager->video(\"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", t('" . h($placeholder) . "'), \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                        break;

                    case FileType::FILE_TYPE_TEXT:
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$fileManager->text(\"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", t('" . h($placeholder) . "'), \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                        break;

                    case FileType::FILE_TYPE_AUDIO:
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$fileManager->audio(\"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", t('" . h($placeholder) . "'), \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                        break;

                    case FileType::FILE_TYPE_DOC:
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$fileManager->doc(\"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", t('" . h($placeholder) . "'), \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                        break;

                    case FileType::FILE_TYPE_APP:
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$fileManager->app(\"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($field->getHandle())) . "\", t('" . h($placeholder) . "'), \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                        break;
                }

            } else if ($field->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var EntityManagerInterface \$entityManager */\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entityManager = \$app->make(EntityManagerInterface::class);\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var Form \$form */\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$form = \$app->make(Form::class);\n";

                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entries = [];\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "foreach (\$entityManager->getRepository(" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . "::class)->findAll() as \$entry) {\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "/** @var " . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . " \$entry */\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$entries[\$entry->getId()] = \$entry->getDisplayValue();\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";

                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$form->select('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$entries, \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";

            } else {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var Form \$form */\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$form = \$app->make(Form::class);\n";

                if ($field->getType() === FieldType::FIELD_TYPE_TEXT) {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$form->text('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                } else if ($field->getType() === FieldType::FIELD_TYPE_EMAIL) {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$form->email('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                } else if ($field->getType() === FieldType::FIELD_TYPE_TELEPHONE) {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$form->telephone('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                } else if ($field->getType() === FieldType::FIELD_TYPE_PASSWORD) {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$form->password('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                } else if ($field->getType() === FieldType::FIELD_TYPE_WEB_ADDRESS) {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$form->url('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                } else if ($field->getType() === FieldType::FIELD_TYPE_TEXT_AREA) {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$form->textarea('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                } else if ($field->getType() === FieldType::FIELD_TYPE_NUMBER) {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$form->number('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                } else if ($field->getType() === FieldType::FIELD_TYPE_RADIO_BUTTONS ||
                    $field->getType() === FieldType::FIELD_TYPE_SELECT_BOX ||
                    $field->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {

                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entries = [\n";

                    foreach ($field->getOptions() as $option) {
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"" . h($option->getValue()) . "\" => t(\"" . t($option->getLabel()) . "\"),\n";
                    }

                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "];\n";

                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$form->select('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$entries, \$this->data['" . lcfirst($this->textService->camelcase($field->getHandle())) . "']);\n";
                }
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

            $generatorItems[] = new GeneratorItem($fileName, $code);
        }

        return $generatorItems;
    }
}