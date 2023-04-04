<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\BlockType\ListBlock;

use Bitter\EntityDesigner\Enumeration\FieldType;
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
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_list/controller.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getNamespace() . "\Block\\" . $this->textService->camelcase($this->entity->getHandle()) . "List;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . " as " . $this->textService->camelcase($this->entity->getHandle()) . "Entity;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Block\BlockController;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Error\ErrorList\ErrorList;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Http\ResponseFactory;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Legacy\Pagination;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\EntityManagerInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class Controller extends BlockController\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$btTable = \"bt" . $this->textService->camelcase($this->entity->getHandle()) . "List\";\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$btInterfaceWidth = 400;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$btInterfaceHeight = 500;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var int */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$displayLimit;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var EntityManagerInterface */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$entityManager;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @var ResponseFactory */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$responseFactory;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function on_start()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "parent::on_start();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->responseFactory = \$this->app->make(ResponseFactory::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->entityManager = \$this->app->make(EntityManagerInterface::class);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getBlockTypeDescription()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('Add a entry list to a page.');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getBlockTypeName()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('" . h($this->entity->getName()) . " List');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getBlockTypeInSetName()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('List');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function add()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->displayLimit = 20;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function validate(\$data)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var ErrorList \$errorList */\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$errorList = \$this->app->make(ErrorList::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (!isset(\$data[\"detailPage\"]) || empty(\$data[\"detailPage\"])) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$errorList->add(t(\"You need to select a detail page.\"));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (!isset(\$data[\"displayLimit\"]) || (int)\$data[\"displayLimit\"] <= 0) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$errorList->add(t(\"The display limit must be greater then zero.\"));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$errorList;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/** @noinspection DuplicatedCode */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function view()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$page = (int)\$this->request->query->get(\"ccm_paging_p\", 1);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$orderByDefault = \"e.id\";\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$orderBy = \$this->request->query->get(\"ccm_order_by\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$orderByDirectionDefault = \"ASC\";\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$orderByDirection = \$this->request->query->get(\"ccm_order_by_direction\", \"ASC\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$allowedFields = [\n";

        foreach ($this->entity->getFields() as $fieldEntity) {
            if ($fieldEntity->isDisplayInListView() && $fieldEntity->getType() !== FieldType::FIELD_TYPE_ASSOCIATION) {
                $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"e." . lcfirst($this->textService->camelcase($fieldEntity->getHandle())) . "\",\n";
            }
        }

        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "];\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$allowedDirections = [\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"ASC\",\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\"DESC\"\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "];\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (!in_array(\$orderBy, \$allowedFields)) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$orderBy = \$orderByDefault;\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (!in_array(\$orderByDirection, \$allowedDirections)) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$orderByDirection = \$orderByDirectionDefault;\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$queryBuilder = \$this->entityManager->getRepository(" . $this->textService->camelcase($this->entity->getHandle()) . "Entity::class)->createQueryBuilder(\"e\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entries = \$queryBuilder\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->addOrderBy(\$orderBy, \$orderByDirection)\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->getQuery()\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->setFirstResult(\$this->displayLimit * (\$page - 1))\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->setMaxResults(\$this->displayLimit)\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->execute();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$totalItems = \$queryBuilder\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->select(\"COUNT(e)\")\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->getQuery()\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->getSingleScalarResult();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var Pagination \$pagination */\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$pagination = \$this->app->make(Pagination::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$pagination->init(\$page, \$totalItems, \"\", \$this->displayLimit);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"displayPagination\", \$totalItems > \$this->displayLimit);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"pagination\", \$pagination);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"entries\", \$entries);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"orderBy\", \$orderBy);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set(\"orderByDirection\", \$orderByDirection);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}