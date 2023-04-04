<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\Controller\Search;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class SearchControllerGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/controllers/search/" . $this->entity->getHandle() . ".php";

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
        $code .= str_repeat(" ", $indent * $indentSize) . "namespace " . $this->getNamespace() . "\Controller\Search;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\Search\\Saved" . $this->textService->camelcase($this->entity->getHandle()) . "Search;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Search\Field\Field\KeywordsField;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Doctrine\ORM\EntityManagerInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Controller\Search\Standard;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Permission\Key\Key;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getNamespace() . "\\Controller\\Dialog\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\AdvancedSearch;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . " extends Standard\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return \Concrete\Controller\Dialog\Search\AdvancedSearch\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function getAdvancedSearchDialogController()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$this->app->make(AdvancedSearch::class);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @param int \$presetID\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " *\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return Saved" . $this->textService->camelcase($this->entity->getHandle()) . "Search|null\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function getSavedSearchPreset(\$presetID)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$em = \$this->app->make(EntityManagerInterface::class);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$em->find(Saved" . $this->textService->camelcase($this->entity->getHandle()) . "Search::class, \$presetID);\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return KeywordsField[]\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function getBasicSearchFieldsFromRequest()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$fields = parent::getBasicSearchFieldsFromRequest();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$keywords = htmlentities(\$this->request->get('cKeywords'), ENT_QUOTES, APP_CHARSET);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (\$keywords) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$fields[] = new KeywordsField(\$keywords);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$fields;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " * @return bool\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected function canAccess()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$permissionKey = Key::getByHandle(\"read_" . $this->entity->getHandle() . "_entries\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \$permissionKey->validate();\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}