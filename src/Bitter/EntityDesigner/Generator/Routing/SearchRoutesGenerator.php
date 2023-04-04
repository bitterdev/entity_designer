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

class SearchRoutesGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/routes/search/" . $this->entity->getHandle() . ".php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "defined('C5_EXECUTE') or die('Access Denied.');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * @var \Concrete\Core\Routing\Router \$router\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * Base path: /ccm/system/search/" . $this->entity->getHandle() . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * Namespace: " . $this->getNamespace() . "\Controller\Search\\\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/basic', '" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "::searchBasic');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/current', '" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "::searchCurrent');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/preset/{presetID}', '" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "::searchPreset');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/clear', '" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "::clearSearch');\n";

        return [new GeneratorItem($fileName, $code)];
    }
}