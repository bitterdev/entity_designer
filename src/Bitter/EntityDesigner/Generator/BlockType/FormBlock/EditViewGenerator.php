<?php /** @noinspection DuplicatedCode */

/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

namespace Bitter\EntityDesigner\Generator\BlockType\FormBlock;

use Bitter\EntityDesigner\Generator\Generator;
use Bitter\EntityDesigner\Generator\GeneratorInterface;
use Bitter\EntityDesigner\Generator\GeneratorItem;

class EditViewGenerator extends Generator implements GeneratorInterface
{

    public function build(
        $indent,
        $indentSize
    )
    {
        $fileName = $this->getPath() . "/blocks/" . $this->entity->getHandle() . "_form/edit.php";

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
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "defined(\"C5_EXECUTE\") or die(\"Access denied\");\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Block\View\BlockView;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Form\Service\Form;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Form\Service\Widget\PageSelector;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "use Concrete\Core\Support\Facade\Application;\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var BlockView \$view */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$submitLabel */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$thankYouMessage */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$recipientEmail */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var string \$notifyMeOnSubmission */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var int \$displayCaptcha */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var int \$redirectCID */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$app = Application::getFacadeApplication();\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var PageSelector \$pageSelector */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$pageSelector = \$app->make(PageSelector::class);\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "/** @var Form \$form */\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\$form = \$app->make(Form::class);\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div class=\"form-group\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$form->label(\"submitLabel\", t('Submit Label')); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$form->text(\"submitLabel\", \$submitLabel); ?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div class=\"form-group\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$form->label(\"thankYouMessage\", t('Message to display when completed')); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<?php echo \$form->textarea(\"thankYouMessage\", \$thankYouMessage, [\"rows\" => 3]); ?>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div class=\"form-group\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<label class=\"control-label\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo t('Send email notification'); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</label>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div class=\"radio\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<label>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo \$form->radio(\"notifyMeOnSubmission\", 1, \$notifyMeOnSubmission); ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo t('Yes') ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</label>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div class=\"radio\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<label>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo \$form->radio(\"notifyMeOnSubmission\", 0, \$notifyMeOnSubmission); ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo t('No') ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</label>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div id=\"recipientEmailContainer\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div class=\"form-group\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$form->label(\"recipientEmail\", t('Send form submissions to email addresses')) ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$form->text(\"recipientEmail\", \$recipientEmail); ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<span class=\"help-block\">\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo t(\"(Separate multiple emails with a comma)\"); ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</span>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div class=\"form-group\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<label class=\"control-label\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo t('Solving a CAPTCHA Required to Post?'); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</label>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div class=\"radio\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<label>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo \$form->radio(\"displayCaptcha\", 1, \$displayCaptcha); ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo t('Yes') ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</label>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div class=\"radio\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<label>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo \$form->radio(\"displayCaptcha\", 0, \$displayCaptcha); ?>\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "<?php echo t('No') ?>\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "</label>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<div class=\"form-group\">\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<label class=\"control-label\" for=\"ccm-form-redirect\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo t('Redirect to another page after form submission?'); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</label>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "<div id=\"ccm-form-redirect-page\">\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "<?php echo \$pageSelector->selectPage(\"redirectCID\", \$redirectCID); ?>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</div>\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<!--suppress JSUnresolvedVariable -->\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "<script>\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "(function (\$) {\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "\$(function () {\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "\$(\"input[name=notifyMeOnSubmission]\").bind(\"change\", function (e) {\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "e.preventDefault();\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "if (parseInt(\$(\"input[name=notifyMeOnSubmission]:checked\").val()) === 1) {\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$(\"#recipientEmailContainer\").removeClass(\"d-none\");\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "} else {\n";
        $code .= str_repeat(" ", ($indent + 5) * $indentSize) . "\$(\"#recipientEmailContainer\").addClass(\"d-none\");\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "}\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "\n";
        $code .= str_repeat(" ", ($indent + 4) * $indentSize) . "return false;\n";
        $code .= str_repeat(" ", ($indent + 3) * $indentSize) . "}).trigger(\"change\");\n";
        $code .= str_repeat(" ", ($indent + 2) * $indentSize) . "});\n";
        $code .= str_repeat(" ", ($indent + 1) * $indentSize) . "})(jQuery);\n";
        $code .= str_repeat(" ", ($indent + 0) * $indentSize) . "</script>";

        return [new GeneratorItem($fileName, $code)];
    }
}