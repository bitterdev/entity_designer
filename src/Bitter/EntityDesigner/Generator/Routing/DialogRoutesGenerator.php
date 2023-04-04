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

class DialogRoutesGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/routes/dialogs/" . $this->entity->getHandle() . ".php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * Base path: /ccm/system/dialogs/" . $this->entity->getHandle() . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " * Namespace: " . $this->getNamespace() . "\Controller\Dialog\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . " */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/advanced_search', 'AdvancedSearch::view');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/advanced_search/add_field', 'AdvancedSearch::addField');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/advanced_search/submit', 'AdvancedSearch::submit');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/advanced_search/save_preset', 'AdvancedSearch::savePreset');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/advanced_search/preset/edit', 'Preset\Edit::view');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/advanced_search/preset/edit/edit_search_preset', 'Preset\Edit::edit_search_preset');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/advanced_search/preset/delete', 'Preset\Delete::view');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/advanced_search/preset/delete/remove_search_preset', 'Preset\Delete::remove_search_preset');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/ccm/system/search/" . $this->entity->getHandle() . "/basic', '\\" . $this->getNamespace() . "\Controller\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "::searchBasic');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/ccm/system/search/" . $this->entity->getHandle() . "/current', '\\" . $this->getNamespace() . "\Controller\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "::searchCurrent');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/ccm/system/search/" . $this->entity->getHandle() . "/preset/{presetID}', '\\" . $this->getNamespace() . "\Controller\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "::searchPreset');\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$router->all('/ccm/system/search/" . $this->entity->getHandle() . "/clear', '\\" . $this->getNamespace() . "\Controller\Search\\" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "::clearSearch');\n";

        return [new GeneratorItem($fileName, $code)];
    }
}