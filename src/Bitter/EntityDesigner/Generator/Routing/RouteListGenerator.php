<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\Routing;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class RouteListGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getSrcPath() . "/Routing/" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "RouteList.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . "\\Routing;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Routing\RouteListInterface;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Routing\Router;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "RouteList implements RouteListInterface\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function loadRoutes(Router \$router)\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$router->buildGroup()->setNamespace('" . $this->getNamespace() . "\Controller\Dialog\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "')\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->setPrefix('/ccm/system/dialogs/" . $this->entity->getHandle() . "')\n";

        if ($this->entity->hasValidPackageHandle()) {
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->routes('dialogs/" . $this->entity->getHandle() . ".php', '" . $this->entity->getPackageHandle() . "');\n";
        } else {
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->routes('dialogs/" . $this->entity->getHandle() . ".php');\n";
        }

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$router->buildGroup()->setNamespace('" . $this->getNamespace() . "\Controller\Search')\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->setPrefix('/ccm/system/search/" . $this->entity->getHandle() . "')\n";

        if ($this->entity->hasValidPackageHandle()) {
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->routes('search/" . $this->entity->getHandle() . ".php', '" . $this->entity->getPackageHandle() . "');\n";
        } else {
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "->routes('search/" . $this->entity->getHandle() . ".php');\n";
        }

        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}