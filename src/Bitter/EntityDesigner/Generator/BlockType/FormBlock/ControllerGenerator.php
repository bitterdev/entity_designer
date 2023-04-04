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
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class ControllerGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_form/controller.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getNamespace() . "\Block\\" . $this->textService->camelcase($this->entity->getHandle()) . "Form;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Antispam\Service as AntispamService;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Mail\Service as MailService;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Block\BlockController;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Captcha\CaptchaInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Config\Repository\Repository;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Error\ErrorList\ErrorList;\n";

        if ($hasFileFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\File\File;\n";
        }

        if ($hasCheckboxFields) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\Common\Collections\Collection;\n";

            foreach ($this->entity->getFields() as $fieldEntity) {
                if ($fieldEntity->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                    $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($fieldEntity->getHandle()) . "Options;\n";
                }
            }
        }

        if ($hasManyCollections) {
            $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\Common\Collections\ArrayCollection;\n";
        }

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . ";\n";
            }
        }

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Http\Request;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Http\Response;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Http\ResponseFactory;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Page\Page;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Url;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\User\UserInfoRepository;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Validation\CSRF\Token;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\EntityManagerInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Exception;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class Controller extends BlockController\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$btTable = \"bt" . $this->textService->camelcase($this->entity->getHandle()) . "Form\";\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$btInterfaceWidth = 400;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$btInterfaceHeight = 500;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var Token */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$token;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var EntityManagerInterface */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$entityManager;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var ResponseFactory */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$responseFactory;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var string */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$submitLabel;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var string */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$thankYouMessage;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var int */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$notifyMeOnSubmission;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var string */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$recipientEmail;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var int */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$displayCaptcha;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var int */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$redirectCID;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var Request */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$request;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var ErrorList */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$error;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var CaptchaInterface */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$captcha;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var Repository */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$config;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var AntispamService */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$antispam;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var MailService */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$mail;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function on_start()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "parent::on_start();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->token = \$this->app->make(Token::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->entityManager = \$this->app->make(EntityManagerInterface::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->responseFactory = \$this->app->make(ResponseFactory::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->request = \$this->app->make(Request::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->error = \$this->app->make(ErrorList::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->captcha = \$this->app->make(CaptchaInterface::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->config = \$this->app->make(Repository::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->antispam = \$this->app->make(AntispamService::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->mail = \$this->app->make(MailService::class);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getBlockTypeDescription()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('Build simple entry forms.');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getBlockTypeName()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('" . h($this->entity->getName()) . " Form');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getBlockTypeInSetName()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('Form');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "private function setDefaults()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_SELECT_BOX) {
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "Values = [\n";

                foreach ($fieldEntity->getOptions() as $optionEntity) {
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"" . h($optionEntity->getValue()) . "\" => t('" . h($optionEntity->getLabel()) . "'),\n";
                }

                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "];\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "Values\", \$" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "Values);\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";

            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {

                if ($fieldEntity->getAssociationType() === AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE) {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values = [\"\" => t(\"** None\")];\n";
                } else {
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values = [];\n";
                }

                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . "[] \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entities */\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entities = \$this->entityManager->getRepository(" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . "::class)->findAll();\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "foreach(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entities as \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity) {\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values[\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity->getId()] = \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity->getDisplayValue();\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values\", \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Values);\n";
            }
        }

        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"token\", \$this->token);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"captcha\", \$this->captcha);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"displayCaptcha\", \$this->displayCaptcha);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"submitLabel\", \$this->submitLabel);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"error\", \$this->error);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->requireAsset(\"css\", \"bootstrap\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->requireAsset('css', 'core/frontend/errors');\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if ((int)\$this->displayCaptcha === 1) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->requireAsset('css', 'core/frontend/captcha');\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @noinspection DuplicatedCode\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @noinspection PhpUnused\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @throws Exception\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function action_submit_form()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->setDefaults();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$this->token->validate(\"save_" . $this->entity->getHandle() . "_entity\")) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$data = \$this->request->request->all();\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            switch ($fieldEntity->getValidation()) {
                case FieldValidation::FIELD_VALIDATION_REQUIRED:
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (!isset(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"]) || empty(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"]))\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "{\n";
                    $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->error->add(t(\"The field \\\"" . $fieldEntity->getLabel() . "\\\" is required.\"));\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                    break;
            }
        }

        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (\$this->displayCaptcha && !\$this->captcha->check()) {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->error->add(t(\"Incorrect captcha code.\"));\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (!\$this->error->has()) {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$entry = new " . $this->textService->camelcase($this->entity->getHandle()) . "();\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";


        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_FILE) {
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$entry->set" . $this->textService->camelcase($fieldEntity->getHandle()) . "(File::getById(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"]));\n";

            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "foreach (\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"] as \$selectedValue) {\n";
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$optionEntry = new " . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($fieldEntity->getHandle()) . "Options();\n";
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$optionEntry->setEntry(\$entry);\n";
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$optionEntry->setValue(\$selectedValue);\n";
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->entityManager->persist(\$optionEntry);\n";
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "if (\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof Collection) {\n";
                $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->add(\$optionEntry);\n";
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";

            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                switch ($fieldEntity->getAssociationType()) {
                    case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "foreach(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() as \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity) {\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity->set" . $this->textService->camelcase($this->getEntity()->getHandle()) . "(null);\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->entityManager->persist(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->clear();\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection = new ArrayCollection();\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (is_array(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"])) {\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "foreach (\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"] as \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Id) {\n";
                        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "/** @var " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . " \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity */\n";
                        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity = \$this->entityManager->getRepository(" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . "::class)->findOneBy([\"id\" => \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Id]);\n";
                        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection->add(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$entry->set" . $this->textService->camelcase($fieldEntity->getHandle()) . "(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                        break;

                    case AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY:
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "foreach(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() as \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity) {\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity->set" . $this->textService->camelcase($this->getEntity()->getHandle()) . "(null);\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->entityManager->persist(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->clear();\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection = new ArrayCollection();\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (is_array(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"])) {\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "foreach (\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"] as \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Id) {\n";
                        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "/** @var " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . " \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity */\n";
                        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity = \$this->entityManager->getRepository(" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . "::class)->findOneBy([\"id\" => \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Id]);\n";
                        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection->add(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity->set" . $this->textService->camelcase($this->getEntity()->getHandle()) . "(\$entry);\n";
                        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$this->entityManager->persist(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$entry->set" . $this->textService->camelcase($fieldEntity->getHandle()) . "(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                        break;

                    case AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE:
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "/** @var " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . " \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity */\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity = \$this->entityManager->getRepository(" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . "::class)->findOneBy([\"id\" => \$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"]]);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity instanceof " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . ") {\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity->set" . $this->textService->camelcase($fieldEntity->getEntity()->getHandle()) . "(\$entry);\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->entityManager->persist(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$entry->set" . $this->textService->camelcase($fieldEntity->getHandle()) . "(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        break;
                }

            } else {
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$entry->set" . $this->textService->camelcase($fieldEntity->getHandle()) . "(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"]);\n";
            }
        }

        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->entityManager->persist(\$entry);\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->entityManager->flush();\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "/*\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "* Send mail notification\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$labelMapping = [\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\" => t('" . h($fieldEntity->getLabel()) . "'),\n";
        }

        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "];\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$messageBody = '';\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "foreach (\$data as \$key => \$value) {\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$messageBody .= \$labelMapping[\$key] . \": \" . \$value . \"\\r\\n\" . \"\\r\\n\";\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if ((int)\$this->notifyMeOnSubmission === 1 && \$this->antispam->check(\$messageBody, 'form_block')) {\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "if (\$this->config->get('concrete.email.form_block.address') &&\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "strstr(\$this->config->get('concrete.email.form_block.address'), '@')) {\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$fromAddress = \$this->config->get('concrete.email.form_block.address');\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "/** @var UserInfoRepository \$userInfo */\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$userInfo = \$this->app->make(UserInfoRepository::class);\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$adminUserInfo = \$userInfo->getByID(USER_SUPER_ID);\n";
        $code .= str_repeat(" ", ($indent + 6) * $indentSize) . "\$fromAddress = \$adminUserInfo->getUserEmail();\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->mail->to(\$this->recipientEmail);\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->mail->from(\$fromAddress);\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->mail->setSubject(t('Form Submission'));\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->mail->setBody(\$messageBody);\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->mail->sendMail();\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "/*\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "* Redirect to success page or output success message\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$redirectPage = Page::getByID(\$this->redirectCID);\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "/** @noinspection PhpUndefinedMethodInspection */\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (\$redirectPage instanceof Page && !\$redirectPage->isError()) {\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->responseFactory->redirect(Url::to(\$redirectPage), Response::HTTP_TEMPORARY_REDIRECT)->send();\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->app->shutdown();\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->set(\"success\", \$this->thankYouMessage);\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->error->add(t(\"Invalid token.\"));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function validate(\$data)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var ErrorList \$errorList */\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$errorList = \$this->app->make(ErrorList::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (isset(\$data[\"notifyMeOnSubmission\"]) && (int)\$data[\"notifyMeOnSubmission\"] === 1) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (!isset(\$data[\"recipientEmail\"]) || empty(\$data[\"recipientEmail\"])) {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$errorList->add(t(\"You need to enter a email address.\"));\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (!isset(\$data[\"submitLabel\"]) || empty(\$data[\"submitLabel\"])) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$errorList->add(t(\"You need to enter a submit label.\"));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$errorList;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function view()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->setDefaults();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}