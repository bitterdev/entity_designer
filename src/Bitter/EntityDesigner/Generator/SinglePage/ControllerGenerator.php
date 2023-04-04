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
        $fileName = $this->getPath() . "/controllers/single_page" . $this->getRelPath() . "/" . $this->entity->getHandle() . ".php";

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
        $code .= str_repeat(" ", $indent * $indentSize) . "namespace " . $this->getNamespace() . "\Controller\SinglePage\Dashboard" . $this->getRelNamespace() . ";\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "use Concrete\Core\Page\Controller\DashboardPageController;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Entity\Search\Query;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Filesystem\Element;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Filesystem\ElementManager;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Http\Response;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Http\ResponseFactory;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Http\Request;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Page\Page;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Field\Field\KeywordsField;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Query\Modifier\AutoSortColumnRequestModifier;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Query\Modifier\ItemsPerPageRequestModifier;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Query\QueryModifier;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Result\Result;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Result\ResultFactory;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Url;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Query\QueryFactory;\n";

        if ($hasFileFields) {
            $code .= str_repeat(" ", $indent * $indentSize) . "use Concrete\Core\File\File;\n";
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

        $code .= str_repeat(" ", $indent * $indentSize) . "use " . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . " as " . $this->textService->camelcase($this->entity->getHandle()) . "Entity;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\Search\Saved" . $this->textService->camelcase($this->entity->getHandle()) . "Search;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Search\\" . $this->textService->camelcase($this->entity->getHandle()) . "\SearchProvider;\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "class " . $this->textService->camelcase($this->entity->getHandle()) . " extends DashboardPageController\n";
        $code .= str_repeat(" ", $indent * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @var Element\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$headerMenu;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @var Element\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$headerSearch;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var ResponseFactory */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$responseFactory;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var Request */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$request;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * write the on_start method
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function on_start()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "parent::on_start();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->responseFactory = \$this->app->make(ResponseFactory::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->request = \$this->app->make(Request::class);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * write the save method
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @noinspection PhpInconsistentReturnPointsInspection\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param " . $this->textService->camelcase($this->entity->getHandle()) . "Entity \$entry\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return Response\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "private function save(\$entry)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$data = \$this->request->request->all();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$this->validate(\$data)) {\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->getType() === FieldType::FIELD_TYPE_FILE) {
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$entry->set" . $this->textService->camelcase($fieldEntity->getHandle()) . "(File::getById(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"]));\n";

            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_CHECKBOX_LIST) {
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() instanceof Collection) {\n";
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "foreach(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() as \$optionEntry) {\n";
                $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->entityManager->remove(\$optionEntry);\n";
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->entityManager->flush();\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (is_array(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"])) {\n";
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
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";

            } else if ($fieldEntity->getType() === FieldType::FIELD_TYPE_ASSOCIATION) {
                switch ($fieldEntity->getAssociationType()) {
                    case AssociationType::ASSOCIATION_TYPE_MANY_TO_MANY:
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "foreach(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() as \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity) {\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity->set" . $this->textService->camelcase($this->getEntity()->getHandle()) . "(null);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->entityManager->persist(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->clear();\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection = new ArrayCollection();\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (is_array(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"])) {\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "foreach (\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"] as \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Id) {\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "/** @var " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . " \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity */\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity = \$this->entityManager->getRepository(" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . "::class)->findOneBy([\"id\" => \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Id]);\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection->add(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$entry->set" . $this->textService->camelcase($fieldEntity->getHandle()) . "(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection);\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                        break;

                    case AssociationType::ASSOCIATION_TYPE_ONE_TO_MANY:
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "foreach(\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "() as \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity) {\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity->set" . $this->textService->camelcase($this->getEntity()->getHandle()) . "(null);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->entityManager->persist(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$entry->get" . $this->textService->camelcase($fieldEntity->getHandle()) . "()->clear();\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection = new ArrayCollection();\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (is_array(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"])) {\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "foreach (\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"] as \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Id) {\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "/** @var " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . " \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity */\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity = \$this->entityManager->getRepository(" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . "::class)->findOneBy([\"id\" => \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Id]);\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection->add(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity->set" . $this->textService->camelcase($this->getEntity()->getHandle()) . "(\$entry);\n";
                        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$this->entityManager->persist(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$entry->set" . $this->textService->camelcase($fieldEntity->getHandle()) . "(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Collection);\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                        break;

                    case AssociationType::ASSOCIATION_TYPE_ONE_TO_ONE:
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "/** @var " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . " \$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity */\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity = \$this->entityManager->getRepository(" . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . "::class)->findOneBy([\"id\" => \$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"]]);\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity instanceof " . $this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle()) . ") {\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity->set" . $this->textService->camelcase($fieldEntity->getEntity()->getHandle()) . "(\$entry);\n";
                        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->entityManager->persist(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
                        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$entry->set" . $this->textService->camelcase($fieldEntity->getHandle()) . "(\$" . lcfirst($this->textService->camelcase($fieldEntity->getAssociatedEntity()->getHandle())) . "Entity);\n";
                        break;
                }

            } else {
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$entry->set" . $this->textService->camelcase($fieldEntity->getHandle()) . "(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"]);\n";
            }
        }

        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->entityManager->persist(\$entry);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->entityManager->flush();\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return \$this->responseFactory->redirect(Url::to(\"" . $this->getRelPath() . "/" . $this->entity->getHandle() . "/saved\"), Response::HTTP_TEMPORARY_REDIRECT);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * write the setDefaults method
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "private function setDefaults(\$entry = null)\n";
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

        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"entry\", \$entry);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->render(\"" . $this->getRelPath() . "/" . $this->entity->getHandle() . "/edit\");\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * write the removed method
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function removed()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"success\", t('The item has been successfully removed.'));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->view();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * write the saved method
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function saved()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"success\", t('The item has been successfully updated.'));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->view();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * write the validate method
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @noinspection PhpUnusedParameterInspection\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param array \$data\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return bool\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function validate(\$data = null)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            switch ($fieldEntity->getValidation()) {
                case FieldValidation::FIELD_VALIDATION_REQUIRED:
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (!isset(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"]) || empty(\$data[\"" . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\"]))\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "{\n";
                    $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->error->add(t(\"The field \\\"" . $fieldEntity->getLabel() . "\\\" is required.\"));\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
                    break;
            }
        }

        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return !\$this->error->has();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * the add method
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @noinspection PhpInconsistentReturnPointsInspection\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function add()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entry = new " . $this->textService->camelcase($this->entity->getHandle()) . "Entity();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$this->token->validate(\"save_" . $this->entity->getHandle() . "_entity\")) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return \$this->save(\$entry);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->setDefaults(\$entry);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * write the edit method
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @noinspection PhpInconsistentReturnPointsInspection\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function edit(\$id = null)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var " . $this->textService->camelcase($this->entity->getHandle()) . "Entity \$entry */\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entry = \$this->entityManager->getRepository(" . $this->textService->camelcase($this->entity->getHandle()) . "Entity::class)->findOneBy([\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"id\" => \$id\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "]);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$entry instanceof " . $this->textService->camelcase($this->entity->getHandle()) . "Entity) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (\$this->token->validate(\"save_" . $this->entity->getHandle() . "_entity\")) {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "return \$this->save(\$entry);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->setDefaults(\$entry);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->responseFactory->notFound(null)->send();\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->app->shutdown();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * write the remove method
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @noinspection PhpInconsistentReturnPointsInspection\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function remove(\$id = null)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var " . $this->textService->camelcase($this->entity->getHandle()) . "Entity \$entry */\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entry = \$this->entityManager->getRepository(" . $this->textService->camelcase($this->entity->getHandle()) . "Entity::class)->findOneBy([\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"id\" => \$id\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "]);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$entry instanceof " . $this->textService->camelcase($this->entity->getHandle()) . "Entity) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->entityManager->remove(\$entry);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->entityManager->flush();\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return \$this->responseFactory->redirect(Url::to(\"" . $this->getRelPath() . "/" . $this->entity->getHandle() . "/removed\"), Response::HTTP_TEMPORARY_REDIRECT);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->responseFactory->notFound(null)->send();\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->app->shutdown();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * write the view method
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function view()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$query = \$this->getQueryFactory()->createQuery(\$this->getSearchProvider(), [\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->getSearchKeywordsField()\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "]);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$result = \$this->createSearchResult(\$query);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->renderSearchResult(\$result);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->headerSearch->getElementController()->setQuery(null);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        /*
         * write other methods required for version 9
         */

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function getHeaderMenu()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (!isset(\$this->headerMenu)) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "/** @var ElementManager \$elementManager */\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$elementManager = \$this->app->make(ElementManager::class);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->headerMenu = \$elementManager->get('" . $this->entity->getHandle() . "/header/menu', Page::getCurrentPage(), [], '" . $this->getEntity()->getPackageHandle() . "');\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->headerMenu;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function getHeaderSearch()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (!isset(\$this->headerSearch)) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "/** @var ElementManager \$elementManager */\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$elementManager = \$this->app->make(ElementManager::class);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->headerSearch = \$elementManager->get('" . $this->entity->getHandle() . "/header/search', Page::getCurrentPage(), [], '" . $this->getEntity()->getPackageHandle() . "');\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->headerSearch;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";


        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @return QueryFactory\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function getQueryFactory()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->app->make(QueryFactory::class);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @return SearchProvider\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function getSearchProvider()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->app->make(SearchProvider::class);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";


        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @param Result \$result\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function renderSearchResult(Result \$result)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$headerMenu = \$this->getHeaderMenu();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$headerSearch = \$this->getHeaderSearch();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$headerMenu->getElementController()->setQuery(\$result->getQuery());\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$headerSearch->getElementController()->setQuery(\$result->getQuery());\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$exportArgs = [\$this->getPageObject()->getCollectionPath(), 'csv_export'];\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$this->getAction() == 'advanced_search') {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$exportArgs[] = 'advanced_search';\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$exportURL = \$this->app->make('url/resolver/path')->resolve(\$exportArgs);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$query = \Concrete\Core\Url\Url::createFromServer(\$_SERVER)->getQuery();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$exportURL = \$exportURL->setQuery(\$query);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$headerMenu->getElementController()->setExportURL(\$exportURL);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set('result', \$result);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set('headerMenu', \$headerMenu);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set('headerSearch', \$headerSearch);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->setThemeViewTemplate('full.php');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";


        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @param Query \$query\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @return Result\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function createSearchResult(Query \$query)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$provider = \$this->app->make(SearchProvider::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$resultFactory = \$this->app->make(ResultFactory::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$queryModifier = \$this->app->make(QueryModifier::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$queryModifier->addModifier(new AutoSortColumnRequestModifier(\$provider, \$this->request, Request::METHOD_GET));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$queryModifier->addModifier(new ItemsPerPageRequestModifier(\$provider, \$this->request, Request::METHOD_GET));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$query = \$queryModifier->process(\$query);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$resultFactory->createFromQuery(\$provider, \$query);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";


        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function getSearchKeywordsField()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$keywords = null;\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$this->request->query->has('keywords')) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$keywords = \$this->request->query->get('keywords');\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return new KeywordsField(\$keywords);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function advanced_search()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$query = \$this->getQueryFactory()->createFromAdvancedSearchRequest(\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->getSearchProvider(), \$this->request, Request::METHOD_GET\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . ");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$result = \$this->createSearchResult(\$query);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->renderSearchResult(\$result);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";


        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function preset(\$presetID = null)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$presetID) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$preset = \$this->entityManager->find(Saved" . $this->textService->camelcase($this->entity->getHandle()) . "Search::class, \$presetID);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (\$preset) {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$query = \$this->getQueryFactory()->createFromSavedSearch(\$preset);\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$result = \$this->createSearchResult(\$query);\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\$this->renderSearchResult(\$result);\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "return;\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->view();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}