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
use Bitter\EntityDesigner\Enumeration\FieldValidation;
use Bitter\EntityDesigner\Enumeration\FileType;
use Bitter\EntityDesigner\Enumeration\PageSelectorStyle;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class DetailViewGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName =  $this->getPath() . "/single_pages" . $this->getRelPath() . "/" . $this->entity->getHandle() . "/edit.php";

        $hasFileFields = false;
        $hasPageSelectorFields = false;
        $hasCheckboxFields = false;
        $hasCollections = false;

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_FILE) {
                $hasFileFields = true;
            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_PAGE_SELECTOR) {
                $hasPageSelectorFields = true;
            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                $hasCheckboxFields = true;
            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $hasCollections = true;
            }
        }

        $requireApplication = $hasFileFields ||
            $hasPageSelectorFields ||
            $hasCheckboxFields ||
            $hasCollections ||
            strlen($this->entity->getDetailViewHelpText()) > 0;

        $code = "<?php\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "/**\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " *\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " * " . t("This file was build with the Entity Designer add-on.") . "\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " *\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " * https://www.concrete5.org/marketplace/addons/entity-designer\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " *\n";
        $code .= str_repeat(" ", $indent * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection DuplicatedCode */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "defined('C5_EXECUTE') or die('Access denied');\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "use " . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "use Concrete\Core\Form\Service\Form;\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "use Concrete\Core\Support\Facade\Url;\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "use Concrete\Core\Validation\CSRF\Token;\n";

        if ($requireApplication) {
            $code .= str_repeat(" ", $indent * $indentSize) . "use Concrete\Core\Support\Facade\Application;\n";
        }

        if ($hasCheckboxFields || $hasCollections) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\EntityManagerInterface;\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\Common\Collections\Collection;\n";

            foreach ($this->entity->getFields() as $fieldEntity) {
                if ($fieldEntity->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                    $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($fieldEntity->getHandle()) . "Options;\n";
                }
            }
        }

        if ($hasFileFields) {
            $code .= str_repeat(" ", $indent * $indentSize) . "use Concrete\Core\Application\Service\FileManager;\n";
        }

        if ($hasPageSelectorFields) {
            $code .= str_repeat(" ", $indent * $indentSize) . "use Concrete\Core\Form\Service\Widget\PageSelector;\n";
        }

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . ";\n";
            }
        }

        $code .= str_repeat(" ", $indent * $indentSize) . "\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_SELECT_BOX) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var array \$" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "Values */\n";
            }
        }

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var array \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values */\n";
            }
        }

        $code .= str_repeat(" ", $indent * $indentSize) . "/** @var \$entry " . $this->textService->camelcase($this->entity->getHandle()) . " */\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "/** @var \$form Form */\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "/** @var \$token Token */\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "\n";

        if ($requireApplication) {
            $code .= str_repeat(" ", $indent * $indentSize) . "\$app = Application::getFacadeApplication();\n";
        }

        if ($hasFileFields) {
            $code .= str_repeat(" ", $indent * $indentSize) . "/** @var \$fileManager FileManager */\n";
            $code .= str_repeat(" ", $indent * $indentSize) . "\$fileManager = \$app->make(FileManager::class);\n";
            $code .= str_repeat(" ", $indent * $indentSize) . "\n";
        }

        if ($hasPageSelectorFields) {
            $code .= str_repeat(" ", $indent * $indentSize) . "/** @var \$pageSelector PageSelector */\n";
            $code .= str_repeat(" ", $indent * $indentSize) . "\$pageSelector = \$app->make(PageSelector::class);\n";
            $code .= str_repeat(" ", $indent * $indentSize) . "\n";
        }

        if ($hasCheckboxFields || $hasCollections) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var EntityManagerInterface \$entityManager */\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$entityManager = \$app->make(EntityManagerInterface::class);\n";
        }

        if (strlen($this->entity->getDetailViewHelpText()) > 0) {
            $code .= str_repeat(" ", $indent * $indentSize) . "\$app->make('help')->display(t('" . nl2br(h($this->entity->getDetailViewHelpText())) . "'));\n";
            $code .= str_repeat(" ", $indent * $indentSize) . "\n";
        }

        $code .= str_repeat(" ", $indent * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<form action=\"#\" method=\"post\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$token->output(\"save_" . $this->entity->getHandle() . "_entity\"); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            $labelClasses = ["control-label"];
            $inputFieldAttributes = ["class" => "form-control"];

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div class=\"form-group\">\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$form->label(\n";
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\",\n";
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "t(\"" . h($fieldEntity->getLabel()) . "\"),\n";
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "[\n";

            if ($fieldEntity->hasHelpText()) {
                $labelClasses[] = "launch-tooltip";

                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\"title\" => t('" . h($fieldEntity->getHelpText()) . "'),\n";
            }

            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\"class\" => \"" . implode(" ", $labelClasses) . "\"\n";
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "]\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "); ?>\n";
            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";

            if ($fieldEntity->getValidation() === FieldValidation::FIELD_VALIDATION_REQUIRED) {
                $inputFieldAttributes["required"] = "required";

                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<span class=\"text-muted small\">\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo t('Required') ?>\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</span>\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
            }

            switch ($fieldEntity->getType()) {
                case FieldType::FIELD_TYPE_ASSOCIATION:
                    switch($fieldEntity->getAssociationType()) {
                        case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                        case AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php if (empty(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values)): ?>\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<p>\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo t(\"No entries available.\"); ?>\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</p>\n";
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php else: ?>\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php foreach(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values as \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Value => \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Label): ?>\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<div class=\"form-check\">\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<?php echo \$form->checkbox(\n";
                            $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "[]\",\n";
                            $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Value,\n";
                            $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->contains(\$entityManager->getRepository(" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . "::class)->findOneBy([\"id\" => \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Value])),\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "[\"class\" => \"form-check-input\"]); ?>\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "<?php echo \$form->label(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Label, [\"class\" => \"form-check-label\"]); ?>\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "</div>\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php endforeach; ?>\n";
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php endif; ?>\n";
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                            break;

                        case AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php if (empty(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values)): ?>\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<p>\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo t(\"No entries available.\"); ?>\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</p>\n";
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php else: ?>\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo \$form->select(\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\",\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values,\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "(),\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "[\n";
                            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\"class\" => \"form-control\"\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "]\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "); ?>\n";
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php endif; ?>\n";
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                            break;
                    }

                    break;

                case FieldType::FIELD_TYPE_RADIO_BUTTONS:

                    foreach ($fieldEntity->getOptions() as $optionEntity) {
                        $optionEntity->getValue();

                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div class=\"" . $fieldEntity->getType() . "\">\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<label>\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo \$form->" . $fieldEntity->getType() . "(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . ($fieldEntity->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST ? "[]" : "") . "\", \"" . h($optionEntity->getValue()) . "\", \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()); ?>\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo t('" . h($optionEntity->getLabel()) . "'); ?>\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</label>\n";
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</div>\n";
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                    }

                    break;

                case FieldType::FIELD_TYPE_CHECKBOX_LIST:

                    foreach ($fieldEntity->getOptions() as $optionEntity) {
                        $optionEntity->getValue();

                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div class=\"" . $fieldEntity->getType() . "\">\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<label>\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo \$form->" . $fieldEntity->getType() . "(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . ($fieldEntity->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST ? "[]" : "") . "\", \"" . h($optionEntity->getValue()) . "\", \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof Collection && \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->contains(\$entityManager->getRepository(" . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($fieldEntity->getHandle()) . "Options::class)->findOneBy([\"value\" => \"" . h($optionEntity->getValue()) . "\", \"entry\" => \$entry]))); ?>\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo t('" . h($optionEntity->getLabel()) . "'); ?>\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</label>\n";
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</div>\n";
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                    }

                    break;

                case FieldType::FIELD_TYPE_FILE:

                    $placeholder = "Please choose";

                    if (strlen($fieldEntity->getPlaceholder()) > 0) {
                        $placeholder = $fieldEntity->getPlaceholder();
                    }

                    switch ($fieldEntity->getAllowedFileType()) {
                        case FileType::FILE_TYPE_ALL:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->image(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "'), \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()); ?>\n";
                            break;

                        case FileType::FILE_TYPE_VIDEO:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->video(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "'), \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()); ?>\n";
                            break;

                        case FileType::FILE_TYPE_TEXT:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->text(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "'), \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()); ?>\n";
                            break;

                        case FileType::FILE_TYPE_AUDIO:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->audio(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "'), \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()); ?>\n";
                            break;

                        case FileType::FILE_TYPE_DOC:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->doc(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "'), \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()); ?>\n";
                            break;

                        case FileType::FILE_TYPE_APP:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->app(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "'), \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()); ?>\n";
                            break;
                    }

                    break;

                case FieldType::FIELD_TYPE_PAGE_SELECTOR:
                    switch ($fieldEntity->getPageSelectorStyle()) {
                        case PageSelectorStyle::PAGE_SELECTOR_STYLE_QUICK_SELECT:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$pageSelector->quickSelect(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()); ?>\n";
                            break;

                        case PageSelectorStyle::PAGE_SELECTOR_STYLE_SELECT_PAGE:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$pageSelector->selectPage(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()); ?>\n";
                            break;

                        case PageSelectorStyle::PAGE_SELECTOR_STYLE_SELECT_FROM_SITEMAP:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$pageSelector->selectFromSitemap(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()); ?>\n";
                            break;
                    }

                    break;

                default:

                    if (strlen($fieldEntity->getValue()) > 0) {
                        $inputFieldAttributes["value"] = $fieldEntity->getValue();
                    }

                    if (strlen($fieldEntity->getPlaceholder()) > 0) {
                        $inputFieldAttributes["placeholder"] = $fieldEntity->getPlaceholder();
                    }

                    if ($fieldEntity->getMaxLength() > 0) {
                        $inputFieldAttributes["max-length"] = $fieldEntity->getMaxLength();
                    }

                    if ($fieldEntity->getType() === FieldType::FIELD_TYPE_NUMBER) {
                        if ($fieldEntity->getMin() > 0) {
                            $inputFieldAttributes["min"] = $fieldEntity->getMin();
                        }

                        if ($fieldEntity->getMax() > 0) {
                            $inputFieldAttributes["max"] = $fieldEntity->getMax();
                        }

                        if ($fieldEntity->getStep() > 0) {
                            $inputFieldAttributes["step"] = $fieldEntity->getStep();
                        }
                    }

                    $isInputGroup = (strlen($fieldEntity->getPrefix()) > 0 || strlen($fieldEntity->getSuffix()) > 0);
                    $extraIndent = $isInputGroup ? 1 : 0;

                    if ($isInputGroup) {
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div class=\"input-group\">\n";

                        if (strlen($fieldEntity->getPrefix()) > 0) {
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<span class=\"input-group-text\">\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo t('" . h($fieldEntity->getPrefix()) . "'); ?>\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</span>\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                        }
                    }

                    if ($fieldEntity->getType() === FieldType::FIELD_TYPE_SELECT_BOX) {
                        $code .= str_repeat(" ", ($indent + 2 + $extraIndent) * $indentSize) . "<?php echo \$form->" . $fieldEntity->getType() . "(\n";
                        $code .= str_repeat(" ", ($indent + 3 + $extraIndent) * $indentSize) . "\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\",\n";
                        $code .= str_repeat(" ", ($indent + 3 + $extraIndent) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "Values,\n";
                    } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                        $code .= str_repeat(" ", ($indent + 2 + $extraIndent) * $indentSize) . "<?php echo \$form->" . $fieldEntity->getType() . "(\n";
                        $code .= str_repeat(" ", ($indent + 3 + $extraIndent) * $indentSize) . "\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "[]\",\n";
                    } else {
                        $code .= str_repeat(" ", ($indent + 2 + $extraIndent) * $indentSize) . "<?php echo \$form->" . $fieldEntity->getType() . "(\n";
                        $code .= str_repeat(" ", ($indent + 3 + $extraIndent) * $indentSize) . "\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\",\n";
                    }

                    $code .= str_repeat(" ", ($indent + 3 + $extraIndent) * $indentSize) . "\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "(),\n";
                    $code .= str_repeat(" ", ($indent + 3 + $extraIndent) * $indentSize) . "[\n";

                    foreach ($inputFieldAttributes as $key => $value) {
                        $code .= str_repeat(" ", ($indent + 4 + $extraIndent) * $indentSize) . "\"" . h($key) . "\" => \"" . h($value) . "\",\n";
                    }

                    $code .= str_repeat(" ", ($indent + 3 + $extraIndent) * $indentSize) . "]\n";
                    $code .= str_repeat(" ", ($indent + 2 + $extraIndent) * $indentSize) . "); ?>\n";

                    if ($isInputGroup) {
                        if (strlen($fieldEntity->getSuffix()) > 0) {
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<span class=\"input-group-text\">\n";
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo t('" . h($fieldEntity->getSuffix()) . "'); ?>\n";
                            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</span>\n";
                        }
                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</div>\n";

                    }

                    break;
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        }

        /*
         * write the footer buttons
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div class=\"ccm-dashboard-form-actions-wrapper\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div class=\"ccm-dashboard-form-actions\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<a href=\"<?php echo Url::to(\"" . $this->getRelPath() .  "/" . $this->entity->getHandle() . "\"); ?>\" class=\"btn btn-default\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<i class=\"fa fa-chevron-left\"></i> <?php echo t('Back'); ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</a>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div class=\"float-end\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<button type=\"submit\" class=\"btn btn-primary\">\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<i class=\"fa fa-save\" aria-hidden=\"true\"></i> <?php echo t('Save'); ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</button>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</form>\n";

        return [new GeneratorItem($fileName, $code)];
    }
}