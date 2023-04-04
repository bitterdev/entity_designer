<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\SearchProvider;

use Bitter\EntityDesigner\Enumeration\FieldType;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class ItemListGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getSrcPath() . "/" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "List.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection PhpUnused */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Search\ItemList\Pager\Manager\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "ListPagerManager;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\ItemList\Database\ItemList;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\ItemList\Pager\PagerProviderInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\ItemList\Pager\QueryString\VariableFactory;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Pagination\PaginationProviderInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Application;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Permission\Key\Key;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\EntityManagerInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\DBAL\Query\QueryBuilder;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Pagerfanta\Adapter\DoctrineDbalAdapter;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Closure;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "List extends ItemList implements PagerProviderInterface, PaginationProviderInterface\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";

        $tableAlias = $this->getTableAlias($this->textService->camelcase($this->entity->getHandle()));

        $sortColumns = '';

        foreach ($this->entity->getFields() as $field) {
            $sortColumns .= (strlen($sortColumns) > 0 ? ", " : "") . "'" . $tableAlias . "." . $this->textService->camelcase($field->getHandle()) . "'";
        }

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$isFulltextSearch = false;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$autoSortColumns = [" . $sortColumns . "];\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$permissionsChecker = -1;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function createQuery()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->select('" . $tableAlias . ".*')\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->from(\"" . $this->textService->camelcase($this->entity->getHandle()) . "\", \"" . $tableAlias . "\");\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function finalizeQuery(QueryBuilder \$query)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$query;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";


        $keywordsQuery = $tableAlias . ".`id` LIKE :keywords";;

        foreach ($this->entity->getFields() as $field) {
            switch ($field->getType()) {
                case FieldType::FIELD_TYPE_TEXT:
                case FieldType::FIELD_TYPE_EMAIL:
                case FieldType::FIELD_TYPE_TELEPHONE:
                case FieldType::FIELD_TYPE_PASSWORD:
                case FieldType::FIELD_TYPE_WEB_ADDRESS:
                case FieldType::FIELD_TYPE_TEXT_AREA:
                case FieldType::FIELD_TYPE_RADIO_BUTTONS:
                case FieldType::FIELD_TYPE_SELECT_BOX:
                    $keywordsQuery .= " OR " . $tableAlias . ".`" . lcfirst($this->textService->camelcase($field->getHandle())) . "` LIKE :keywords";
                    break;
            }
        }

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param string \$keywords\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function filterByKeywords(\$keywords)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->andWhere('(" . $keywordsQuery. ")');\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->setParameter('keywords', '%' . \$keywords . '%');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";

        foreach ($this->entity->getFields() as $field) {
            switch($field->getType()) {
                case FieldType::FIELD_TYPE_FILE:
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param \Concrete\Core\Entity\File\File \$" . lcfirst($this->textService->camelcase($field->getHandle())) . "\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function filterBy" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$" . lcfirst($this->textService->camelcase($field->getHandle())) . ")\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->andWhere('" . $tableAlias . ".fID = :" . lcfirst($this->textService->camelcase($field->getHandle())) . "');\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->setParameter('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$" . lcfirst($this->textService->camelcase($field->getHandle())) . "->getFileId());\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                    break;

                case FieldType::FIELD_TYPE_TEXT:
                case FieldType::FIELD_TYPE_EMAIL:
                case FieldType::FIELD_TYPE_TELEPHONE:
                case FieldType::FIELD_TYPE_PASSWORD:
                case FieldType::FIELD_TYPE_WEB_ADDRESS:
                case FieldType::FIELD_TYPE_TEXT_AREA:
                case FieldType::FIELD_TYPE_RADIO_BUTTONS:
                case FieldType::FIELD_TYPE_SELECT_BOX:
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param string \$" . lcfirst($this->textService->camelcase($field->getHandle())) . "\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function filterBy" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$" . lcfirst($this->textService->camelcase($field->getHandle())) . ")\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->andWhere('" . $tableAlias . ".`" . lcfirst($this->textService->camelcase($field->getHandle())) . "` LIKE :" . lcfirst($this->textService->camelcase($field->getHandle())) . "');\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->setParameter('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', '%' . \$" . lcfirst($this->textService->camelcase($field->getHandle())) . " . '%');\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                    break;

                case FieldType::FIELD_TYPE_NUMBER:
                case FieldType::FIELD_TYPE_PAGE_SELECTOR:
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param int \$" . lcfirst($this->textService->camelcase($field->getHandle())) . "\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function filterBy" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$" . lcfirst($this->textService->camelcase($field->getHandle())) . ")\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->andWhere('" . $tableAlias . ".`" . lcfirst($this->textService->camelcase($field->getHandle())) . "` = :" . lcfirst($this->textService->camelcase($field->getHandle())) . "');\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->setParameter('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$" . lcfirst($this->textService->camelcase($field->getHandle())) . ");\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                    break;

                case FieldType::FIELD_TYPE_ASSOCIATION:
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param \\" . $this->getSrcNamespace($field->getEntity()) . "\\Entity\\" . $this->textService->camelcase($field->getAssociatedEntity()->getHandle()) . " \$" . lcfirst($this->textService->camelcase($field->getHandle())) . "\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function filterBy" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$" . lcfirst($this->textService->camelcase($field->getHandle())) . ")\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->andWhere('" . $tableAlias . ".`" . lcfirst($this->textService->camelcase($field->getHandle())) . "` = :" . lcfirst($this->textService->camelcase($field->getHandle())) . "');\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->setParameter('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$" . lcfirst($this->textService->camelcase($field->getHandle())) . "->getId());\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                    break;

                case FieldType::FIELD_TYPE_CHECKBOX_LIST:
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param \\" . $this->getSrcNamespace() . "\\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . $this->textService->camelcase($field->getHandle())  . "Options \$" . lcfirst($this->textService->camelcase($field->getHandle())) . "\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function filterBy" . ucfirst($this->textService->camelcase($field->getHandle())) . "(\$" . lcfirst($this->textService->camelcase($field->getHandle())) . ")\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->andWhere('" . $tableAlias . ".`" . lcfirst($this->textService->camelcase($field->getHandle())) . "` = :" . lcfirst($this->textService->camelcase($field->getHandle())) . "');\n";
                    $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query->setParameter('" . lcfirst($this->textService->camelcase($field->getHandle())) . "', \$" . lcfirst($this->textService->camelcase($field->getHandle())) . "->getId());\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
                    $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
                    break;
            }
        }

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @param array \$queryRow\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "* @return " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getResult(\$queryRow)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$app = Application::getFacadeApplication();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @var EntityManagerInterface \$entityManager */\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$entityManager = \$app->make(EntityManagerInterface::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "/** @noinspection PhpIncompatibleReturnTypeInspection */\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$entityManager->getRepository(" .  ucfirst($this->textService->camelcase($this->entity->getHandle()))  . "::class)->findOneBy([\"id\" => \$queryRow[\"id\"]]);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getTotalResults()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$this->permissionsChecker === -1) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return \$this->deliverQueryObject()\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "->resetQueryParts(['groupBy', 'orderBy'])\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "->select('count(distinct " . $tableAlias . ".id)')\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "->setMaxResults(1)\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "->execute()\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "->fetchColumn();\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return -1; // unknown\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getPagerManager()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return new " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "ListPagerManager(\$this);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getPagerVariableFactory()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return new VariableFactory(\$this, \$this->getSearchRequest());\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getPaginationAdapter()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return new DoctrineDbalAdapter(\$this->deliverQueryObject(), function (\$query) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$query->resetQueryParts(['groupBy', 'orderBy'])\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "->select('count(distinct " . $tableAlias . ".id)')\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "->setMaxResults(1);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "});\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function checkPermissions(\$mixed)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (isset(\$this->permissionsChecker)) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "if (\$this->permissionsChecker === -1) {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "return true;\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "/** @noinspection PhpParamsInspection */\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "return call_user_func_array(\$this->permissionsChecker, [\$mixed]);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$permissionKey = Key::getByHandle(\"read_" . $this->entity->getHandle() . "_entries\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$permissionKey->validate();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function setPermissionsChecker(Closure \$checker = null)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->permissionsChecker = \$checker;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function ignorePermissions()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->permissionsChecker = -1;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getPermissionsChecker()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->permissionsChecker;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function enablePermissions()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "unset(\$this->permissionsChecker);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function isFulltextSearch()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->isFulltextSearch;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}