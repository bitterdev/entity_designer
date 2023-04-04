<?php
/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Validation\CSRF\Token;
use Concrete\Package\EntityDesigner\Controller\Dialog\EntityDesigner\AddPackage;

defined('C5_EXECUTE') or die("Access Denied.");

/** @var AddPackage $controller */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var Token $token */
$token = $app->make(Token::class);
?>

<form method="post" action="<?php echo Url::to("/bitter/entity_designer/dialogs/add_package/submit"); ?>"
      data-dialog-form="add-package">

    <?php echo $token->output('save_package'); ?>

    <div class="form-group">
        <?php echo $form->label(
            "name",
            t("Name"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Enter a name for the package you want to create. While typing in the name the handle field will be automatically filled too.")
            ]
        ); ?>

        <span class="text-muted small">
            <?php echo t("Required") ?>
        </span>

        <?php echo $form->text(
            "name",
            null,
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label(
            "handle",
            t("Handle"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("The handle must begin with a letter of the alphabet. After the first initial letter the handle can contain a underscore and letters.")
            ]
        ); ?>

        <span class="text-muted small">
            <?php echo t("Required") ?>
        </span>

        <?php echo $form->text(
            "handle",
            null,
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label(
            "description",
            t("Description"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Enter a short description for the package.")
            ]
        ); ?>

        <span class="text-muted small">
            <?php echo t("Required") ?>
        </span>

        <?php echo $form->text(
            "description",
            null,
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="dialog-buttons">
        <button class="btn btn-default float-left" data-dialog-action="cancel">
            <?php echo t('Cancel') ?>
        </button>

        <button type="button" data-dialog-action="submit" class="btn btn-primary float-end">
            <i class="fa fa-save" aria-hidden="true"></i> <?php echo t('Save') ?>
        </button>
    </div>
</form>

<!--suppress JSUnresolvedVariable -->
<script>
    (function ($) {
        $(function () {
            let xhrPool = [];

            $.ajaxSetup({
                beforeSend: function (jqXHR) {
                    xhrPool.push(jqXHR);
                },
                complete: function (jqXHR) {
                    let index = xhrPool.indexOf(jqXHR);

                    if (index > -1) {
                        xhrPool.splice(index, 1);
                    }
                }
            });

            $(".ui-dialog input[name=name]").bind("change keyup", function (e) {
                e.preventDefault();

                $.each(xhrPool, function (idx, jqXHR) {
                    if (jqXHR && jqXHR.readystate !== 4) {
                        jqXHR.abort();
                    }
                });

                $.xhrPool = [];

                $.ajax({
                    url: "<?php echo h(Url::to("/dashboard/entity_designer/get_handle")); ?>",
                    data: {
                        name: $(this).val()
                    },
                    success: function (data) {
                        $(".ui-dialog input[name=handle]").val((data.handle));
                    }
                });

                return false;
            });
        });
    })(jQuery);
</script>