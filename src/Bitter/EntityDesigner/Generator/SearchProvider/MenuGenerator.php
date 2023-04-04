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

class MenuGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getSrcPath() . "/" . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "Menu.php";

        $code = "";

        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<?php\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/**\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "*\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "* This file was build with the Entity Designer add-on.\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "*\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "* https://www.concrete5.org/marketplace/addons/entity-designer\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "*\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "*/\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection DuplicatedCode */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection PhpUnused */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Application\UserInterface\ContextMenu\DropdownMenu;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Application\UserInterface\ContextMenu\Item\LinkItem;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Url;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use " . $this->getSrcNamespace() . "\Entity\\" . $this->textService->camelcase($this->entity->getHandle()) . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class " . ucfirst($this->textService->camelcase($this->entity->getHandle())) . "Menu extends DropdownMenu\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$menuAttributes = ['class' => 'ccm-popover-page-menu'];\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function __construct(" . $this->textService->camelcase($this->entity->getHandle()) . " \$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . ")\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "parent::__construct();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->addItem(\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "new LinkItem(\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "(string)Url::to(\"" . $this->getRelPath() . "/" . $this->entity->getHandle() . "/edit\", \$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . "->getId()),\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "t('Edit')\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . ")\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . ");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$this->addItem(\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "new LinkItem(\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "(string)Url::to(\"" . $this->getRelPath() . "/" . $this->entity->getHandle() . "/remove\", \$" . lcfirst($this->textService->camelcase($this->entity->getHandle())) . "->getId()),\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "t('Remove')\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . ")\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . ");\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}