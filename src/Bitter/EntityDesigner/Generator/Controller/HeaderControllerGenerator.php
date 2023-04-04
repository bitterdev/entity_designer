<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\Controller;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class HeaderControllerGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/controllers/element/" . $this->entity->getHandle() . "/header/search.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace Concrete\Package\\" . $this->getSrcNamespace() . "\\Controller\\Element\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\\Header;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Controller\ElementController;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Entity\Search\Query;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Foundation\Serializer\JsonSerializer;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class Search extends ElementController\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$headerSearchAction;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$query;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$pkgHandle = \"" . $this->entity->getPackageHandle() . "\";\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getElement()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return \"" . $this->entity->getHandle() . "/header/search\";\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function setQuery(Query \$query = null): void\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->query = \$query;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function setHeaderSearchAction(string \$headerSearchAction): void\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->headerSearchAction = \$headerSearchAction;\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function view()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set('form', \$this->app->make('helper/form'));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->set('token', \$this->app->make('token'));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (isset(\$this->headerSearchAction)) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->set('headerSearchAction', \$this->headerSearchAction);\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->set('headerSearchAction', \$this->app->make('url')->to('" .  $this->getRelPath() . "/" . $this->entity->getHandle() . "'));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "if (isset(\$this->query)) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$this->set('query', \$this->app->make(JsonSerializer::class)->serialize(\$this->query, 'json'));\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}