<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\BlockType\FormBlock;

use Bitter\EntityDesigner\Enumeration\AssociationType;
use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Enumeration\FieldValidation;
use Bitter\EntityDesigner\Enumeration\FileType;
use Bitter\EntityDesigner\Enumeration\PageSelectorStyle;
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
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_form/view.php";

        $hasFileFields = false;
        $hasPageSelectorFields = false;
        $hasCollections = false;

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_FILE) {
                $hasFileFields = true;
            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_PAGE_SELECTOR) {
                $hasPageSelectorFields = true;
            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $hasCollections = true;
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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection DuplicatedCode */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "defined('C5_EXECUTE') or die('Access denied');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getNamespace() . "\Block\\" . $this->textService->camelcase($this->entity->getHandle()) . "Form\Controller;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Captcha\CaptchaInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Form\Service\Form;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Validation\CSRF\Token;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Application;\n";

        if ($hasFileFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Application\Service\FileManager;\n";
        }

        if ($hasPageSelectorFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Form\Service\Widget\PageSelector;\n";
        }

        if ($hasCollections) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\Common\Collections\Collection;\n";

            foreach ($this->entity->getFields() as $fieldEntity) {
                if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                    $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . ";\n";
                }
            }
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use HtmlObject\Element;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Error\ErrorList\ErrorList;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_SELECT_BOX) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var array \$" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "Values\" */\n";
            }
        }

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var array \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values */\n";
            }
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var ErrorList \$error */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$success */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var int \$displayCaptcha */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var CaptchaInterface \$captcha */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var int \$bID */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$submitLabel */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var Form \$form */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var Token \$token */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var Controller \$controller */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$app = Application::getFacadeApplication();\n";

        if ($hasFileFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var FileManager \$fileManager */\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$fileManager = \$app->make(FileManager::class);\n";
        }

        if ($hasPageSelectorFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var PageSelector \$pageSelector */\n";
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$pageSelector = \$app->make(PageSelector::class);\n";
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "if (\$error->has()) {\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "foreach (\$error->getList() as \$errorMessage) {\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$errorBox = new Element(\"div\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$errorBox->addClass(\"alert\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$errorBox->addClass(\"alert-danger\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$errorBox->setValue(\$errorMessage);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "echo (string)\$errorBox;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "} else if (strlen(\$success) > 0) {\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\$successBox = new Element(\"div\");\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\$successBox->addClass(\"alert\");\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\$successBox->addClass(\"alert-success\");\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\$successBox->setValue(\$success);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "echo (string)\$successBox;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<form action=\"<?" . "php echo \$controller->getActionURL('submit_form') . '#formblock' . \$bID; ?>\" method=\"post\"\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "id=\"<?php echo \$bID; ?>\" enctype=\"multipart/form-data\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
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
                            $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "null,\n";
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
                case FieldType::FIELD_TYPE_CHECKBOX_LIST:

                    foreach ($fieldEntity->getOptions() as $optionEntity) {
                        $optionEntity->getValue();

                        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div class=\"" . $fieldEntity->getType() . "\">\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<label>\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php echo \$form->" . $fieldEntity->getType() . "(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . ($fieldEntity->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST ? "[]" : "") . "\", \"" . h($optionEntity->getValue()) . "\"); ?>\n";
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
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->image(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "')); ?>\n";
                            break;

                        case FileType::FILE_TYPE_VIDEO:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->video(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "')); ?>\n";
                            break;

                        case FileType::FILE_TYPE_TEXT:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->text(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "')); ?>\n";
                            break;

                        case FileType::FILE_TYPE_AUDIO:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->audio(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "')); ?>\n";
                            break;

                        case FileType::FILE_TYPE_DOC:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->doc(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "')); ?>\n";
                            break;

                        case FileType::FILE_TYPE_APP:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$fileManager->app(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", \"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\", t('" . h($placeholder) . "')); ?>\n";
                            break;
                    }

                    break;

                case FieldType::FIELD_TYPE_PAGE_SELECTOR:
                    switch ($fieldEntity->getPageSelectorStyle()) {
                        case PageSelectorStyle::PAGE_SELECTOR_STYLE_QUICK_SELECT:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$pageSelector->quickSelect(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"); ?>\n";
                            break;

                        case PageSelectorStyle::PAGE_SELECTOR_STYLE_SELECT_PAGE:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$pageSelector->selectPage(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"); ?>\n";
                            break;

                        case PageSelectorStyle::PAGE_SELECTOR_STYLE_SELECT_FROM_SITEMAP:
                            $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$pageSelector->selectFromSitemap(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"); ?>\n";
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

                    $code .= str_repeat(" ", ($indent + 3 + $extraIndent) * $indentSize) . "null,\n";
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

            if ($fieldEntity->hasHelpText()) {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<p class=\"help-block\">\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo t('" . h($fieldEntity->getHelpText()) . "'); ?>\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</p>\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
            }

            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
            $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        }

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php if (\$displayCaptcha && \$captcha): ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<div class=\"form-group captcha\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php if (!empty(\$captcha->label())): ?>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<label class=\"control-label\"><?php echo \$captcha->label(); ?></label>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php endif; ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<div>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php \$captcha->display(); ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<div>\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "<?php \$captcha->showInput(); ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php endif; ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<button type=\"submit\" class=\"btn btn-primary\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$submitLabel; ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</button>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</form>\n";

        return [new GeneratorItem($fileName, $code)];
    }
}