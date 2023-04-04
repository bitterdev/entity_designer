<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\Package;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class PackageControllerGenerator extends Generator implements GeneratorInterface
{

    protected $packageHandle;
    /** @var string */
    protected $packageName;
    /** @var string */
    protected $packageDescription;

    /**
     * @return mixed
     */
    public function getPackageHandle()
    {
        return $this->packageHandle;
    }

    /**
     * @param mixed $packageHandle
     * @return PackageControllerGenerator
     */
    public function setPackageHandle($packageHandle)
    {
        $this->packageHandle = $packageHandle;
        return $this;
    }

    /**
     * @return string
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * @param string $packageName
     * @return PackageControllerGenerator
     */
    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPackageDescription()
    {
        return $this->packageDescription;
    }

    /**
     * @param string $packageDescription
     * @return PackageControllerGenerator
     */
    public function setPackageDescription($packageDescription)
    {
        $this->packageDescription = $packageDescription;
        return $this;
    }

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = DIR_PACKAGES . "/" . $this->getPackageHandle() . "/controller.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "namespace Concrete\Package\\" . $this->textService->camelcase($this->getPackageHandle()) . ";\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Package\Package;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "class Controller extends Package\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$pkgHandle = '" . $this->getPackageHandle() . "';\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$pkgVersion = '0.1';\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$appVersionRequired = '9.0.0';\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "protected \$pkgAutoloaderRegistries = ['src' => '" . $this->textService->camelcase($this->getPackageHandle()) . "'];\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getPackageDescription()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('" . h($this->getPackageDescription()) . "');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "public function getPackageName()\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "{\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "return t('" . h($this->getPackageName()) . "');\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "}\n";

        return [new GeneratorItem($fileName, $code)];
    }
}