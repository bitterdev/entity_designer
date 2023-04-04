<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\ServiceProvider;

use Bitter\EntityDesigner\Entity\Entity;
use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class EntityDesignerServiceProviderGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getSrcPath() . "/EntityDesignerServiceProvider.php";

        /** @var Entity[] $entities */

        if ($this->entity->hasValidPackageHandle()) {
            $entities = $this->entityManager->getRepository(Entity::class)->findBy(["packageHandle" => $this->entity->getPackageHandle(), "isTemp" => false]);
        } else {
            $entities = $this->entityManager->getRepository(Entity::class)->findBy(["packageHandle" => "", "isTemp" => false]);
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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @noinspection PhpUnused */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace " . $this->getSrcNamespace() . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Foundation\Service\Provider as ServiceProvider;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class EntityDesignerServiceProvider extends ServiceProvider\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function register()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$serviceProviderClassNames = [\n";

        foreach ($entities as $entity) {
            $code .= str_repeat(" ", ($indent + 3) * $indentSize) . ucfirst($this->textService->camelcase($entity->getHandle())) . "ServiceProvider::class,\n";
        }

        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "];\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "foreach (\$serviceProviderClassNames as \$serviceProviderClassName) {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "/** @var ServiceProvider \$serviceProvider */\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$serviceProvider = \$this->app->make(\$serviceProviderClassName);\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$serviceProvider->register();\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}