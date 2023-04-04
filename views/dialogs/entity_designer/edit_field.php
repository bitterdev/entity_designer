<?php
/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die("Access Denied.");

use Bitter\EntityDesigner\Entity\Field;
use Bitter\EntityDesigner\Enumeration\FieldType;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Validation\CSRF\Token;
use Concrete\Package\EntityDesigner\Controller\Dialog\EntityDesigner\EditField;

/** @var array $fieldTypes */
/** @var array $fieldValidations */
/** @var array $fileTypes */
/** @var array $pageSelectorStyles */
/** @var array $associatedEntities */
/** @var array $associationTypes */
/** @var Field $field */
/** @var EditField $controller */
$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var Token $token */
$token = $app->make(Token::class);
?>

<script id="option-template" type="text/template">
    <tr>
        <td>
            <!--suppress HtmlFormInputWithoutLabel -->
            <input name="options[<%=option.id%>][value]%>" value="<%=option.value%>" class="form-control"
                   required="required">
        </td>

        <td>
            <!--suppress HtmlFormInputWithoutLabel -->
            <input name="options[<%=option.id%>][label]%>" value="<%=option.label%>" class="form-control"
                   required="required">
        </td>

        <td>
            <div class="float-end">
                <a href="javascript:void(0)" class="btn btn-danger remove-option">
                    <i class="fa fa-trash"></i> <?php echo t("Remove option"); ?>
                </a>
            </div>
        </td>
    </tr>
</script>

<form method="post" action="<?php echo Url::to("/bitter/entity_designer/dialogs/edit_field/submit"); ?>"
      data-dialog-form="add-field">

    <?php echo $token->output('save_field'); ?>

    <?php echo $form->hidden("id", $field->getId()); ?>

    <div class="form-group">
        <?php echo $form->label(
            "type",
            t("Type"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Select the type of the field. Depending on the field type you can define further type associated fields.")
            ]
        ); ?>

        <span class="text-muted small">
            <?php echo t("Required") ?>
        </span>

        <?php echo $form->select(
            "type",
            $fieldTypes,
            $field->getType(),
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label(
            "label",
            t("Label"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Enter a label for the field you want to create. While typing in the label the handle field will be automatically filled too.")
            ]
        ); ?>

        <span class="text-muted small">
            <?php echo t("Required") ?>
        </span>

        <?php echo $form->text(
            "label",
            $field->getLabel(),
            [
                "class" => "form-control",
                "required" => "required"
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
            $field->getHandle(),
            [
                "class" => "form-control",
                "required" => "required"
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo implode(",", [
        FieldType::FIELD_TYPE_TEXT,
        FieldType::FIELD_TYPE_EMAIL,
        FieldType::FIELD_TYPE_TELEPHONE,
        FieldType::FIELD_TYPE_PASSWORD,
        FieldType::FIELD_TYPE_WEB_ADDRESS,
        FieldType::FIELD_TYPE_NUMBER
    ]); ?>">

        <?php echo $form->label(
            "prefix",
            t("Prefix"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Enter a value to prepend a info text to the input field. Check the input field below to see this setting it in action.")
            ]
        ); ?>

        <div class="input-group">
            <div class="input-group-text">
                <?php echo t("Example Prefix"); ?>
            </div>

            <?php echo $form->text(
                "prefix",
                $field->getPrefix(),
                [
                    "class" => "form-control"
                ]
            ); ?>
        </div>
    </div>

    <div class="form-group" data-limit-types="<?php echo implode(",", [
        FieldType::FIELD_TYPE_TEXT,
        FieldType::FIELD_TYPE_EMAIL,
        FieldType::FIELD_TYPE_TELEPHONE,
        FieldType::FIELD_TYPE_PASSWORD,
        FieldType::FIELD_TYPE_WEB_ADDRESS,
        FieldType::FIELD_TYPE_NUMBER
    ]); ?>">

        <?php echo $form->label(
            "suffix",
            t("Suffix"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Enter a value to append a info text to the input field. Check the input field below to see this setting in action.")
            ]
        ); ?>

        <div class="input-group">
            <?php echo $form->text(
                "suffix",
                $field->getSuffix(),
                [
                    "class" => "form-control"
                ]
            ); ?>

            <div class="input-group-text">
                <?php echo t("Example Suffix"); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->label(
            "helpText",
            t("Help text"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Enter a value to append a help text like this to the input field.")
            ]
        ); ?>

        <?php echo $form->text(
            "helpText",
            $field->getHelpText(),
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo implode(",", [
        FieldType::FIELD_TYPE_TEXT,
        FieldType::FIELD_TYPE_EMAIL,
        FieldType::FIELD_TYPE_TELEPHONE,
        FieldType::FIELD_TYPE_PASSWORD,
        FieldType::FIELD_TYPE_WEB_ADDRESS,
        FieldType::FIELD_TYPE_NUMBER,
        FieldType::FIELD_TYPE_TEXT_AREA,
        FieldType::FIELD_TYPE_RADIO_BUTTONS,
        FieldType::FIELD_TYPE_SELECT_BOX
    ]); ?>">
        <?php echo $form->label(
            "value",
            t("Default Value"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Enter a default value if you want to pretend a value for the field.")
            ]
        ); ?>

        <?php echo $form->text(
            "value",
            $field->getValue(),
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo implode(",", [
        FieldType::FIELD_TYPE_TEXT,
        FieldType::FIELD_TYPE_EMAIL,
        FieldType::FIELD_TYPE_TELEPHONE,
        FieldType::FIELD_TYPE_PASSWORD,
        FieldType::FIELD_TYPE_WEB_ADDRESS,
        FieldType::FIELD_TYPE_NUMBER
    ]); ?>">
        <?php echo $form->label(
            "placeholder",
            t("Placeholder"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Enter a placeholder for the field.")
            ]
        ); ?>

        <?php echo $form->text(
            "placeholder",
            $field->getPlaceholder(),
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo implode(",", [
        FieldType::FIELD_TYPE_TEXT,
        FieldType::FIELD_TYPE_EMAIL,
        FieldType::FIELD_TYPE_TELEPHONE,
        FieldType::FIELD_TYPE_PASSWORD,
        FieldType::FIELD_TYPE_WEB_ADDRESS
    ]); ?>">
        <?php echo $form->label(
            "maxLength",
            t("Maximum length"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Define the maximum length for the field.")
            ]
        ); ?>

        <?php echo $form->number(
            "maxLength",
            $field->getMaxLength(),
            [
                "class" => "form-control",
                "min" => 0,
                "step" => 1
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo FieldType::FIELD_TYPE_NUMBER; ?>">
        <?php echo $form->label(
            "min",
            t("Minimum value"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Define the minimum value for the field.")
            ]
        ); ?>

        <?php echo $form->number(
            "min",
            $field->getMin(),
            [
                "class" => "form-control",
                "min" => 0,
                "step" => 1
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo FieldType::FIELD_TYPE_NUMBER; ?>">
        <?php echo $form->label(
            "max",
            t("Maximum value"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Define the maximum value for the field.")
            ]
        ); ?>

        <?php echo $form->number(
            "max",
            $field->getMax(),
            [
                "class" => "form-control",
                "min" => 0,
                "step" => 1
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo FieldType::FIELD_TYPE_NUMBER; ?>">
        <?php echo $form->label(
            "step",
            t("Step size"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Define the step size for the field.")
            ]
        ); ?>

        <?php echo $form->number(
            "step",
            $field->getStep(),
            [
                "class" => "form-control",
                "min" => 0,
                "step" => 1
            ]
        ); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label(
            "validation",
            t("Validation"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("If you want validation for the field can select the type of validation here.")
            ]
        ); ?>

        <?php echo $form->select(
            "validation",
            $fieldValidations,
            $field->getValidation(),
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo FieldType::FIELD_TYPE_FILE; ?>">
        <?php echo $form->label(
            "allowedFileType",
            t("Allowed File Type"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("If you want to filter certain file types in the dialog you can select the allowed file types here.")
            ]
        ); ?>

        <?php echo $form->select(
            "allowedFileType",
            $fileTypes,
            $field->getAllowedFileType(),
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo FieldType::FIELD_TYPE_PAGE_SELECTOR; ?>">
        <?php echo $form->label(
            "pageSelectorStyle",
            t("Style"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Please choose the style of the select page dialog.")
            ]
        ); ?>

        <?php echo $form->select(
            "pageSelectorStyle",
            $pageSelectorStyles,
            $field->getPageSelectorStyle(),
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo FieldType::FIELD_TYPE_ASSOCIATION; ?>">
        <?php echo $form->label(
            "associatedEntity",
            t("Associated Entity"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Please select the destination entity for the association.")
            ]
        ); ?>

        <?php echo $form->select(
            "associatedEntity",
            $associatedEntities,
            $field->getAssociatedEntityId(),
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div class="form-group" data-limit-types="<?php echo FieldType::FIELD_TYPE_ASSOCIATION; ?>">
        <?php echo $form->label(
            "associationType",
            t("Association Type"),
            [
                "class" => "control-label launch-tooltip",
                "title" => t("Please select the type of the association.")
            ]
        ); ?>

        <?php echo $form->select(
            "associationType",
            $associationTypes,
            $field->getAssociationType(),
            [
                "class" => "form-control"
            ]
        ); ?>
    </div>

    <div data-limit-types="<?php echo implode(",", [
        FieldType::FIELD_TYPE_RADIO_BUTTONS,
        FieldType::FIELD_TYPE_CHECKBOX_LIST,
        FieldType::FIELD_TYPE_SELECT_BOX
    ]); ?>">
        <label class="control-label">
            <?php echo t("Options"); ?>
        </label>

        <div class="alert alert-warning no-options-message <?php echo empty($field->getOptions())? " " : " d-none"; ?>">
            <?php echo t("Click the »Add Option« button to start creating fields."); ?>
        </div>

        <table class="table table-striped options-table <?php echo empty($field->getOptions()) ? " d-none" : " "; ?>">
            <thead>
            <tr>
                <th>
                    <?php echo t("Value"); ?>
                </th>

                <th>
                    <?php echo t("Label"); ?>
                </th>

                <th>
                    &nbsp;
                </th>
            </tr>
            </thead>

            <tbody class="options-container">
            <?php if (!empty($field->getOptions())): ?>
                <?php foreach ($field->getOptions() as $option): ?>
                    <tr>
                        <td>
                            <?php echo $form->text(
                                "options[" . $option->getId() . "][value]",
                                $option->getValue(),
                                [
                                    "class" => "form-control",
                                    "required" => "required"
                                ]
                            ); ?>
                        </td>

                        <td>
                            <?php echo $form->text(
                                "options[" . $option->getId() . "][label]",
                                $option->getLabel(),
                                [
                                    "class" => "form-control",
                                    "required" => "required"
                                ]
                            ); ?>
                        </td>

                        <td>
                            <div class="float-end">
                                <a href="javascript:void(0)" class="btn btn-danger remove-option">
                                    <i class="fa fa-trash"></i> <?php echo t("Remove option"); ?>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <a href="javascript:void(0);" class="btn btn-success add-option">
            <i class="fa fa-plus"></i> <?php echo t("Add Option"); ?>
        </a>
    </div>

    <label class="control-label">
        <?php echo t("Extras"); ?>
    </label>

    <div class="form-check">
        <?php echo $form->checkbox("isDisplayInListView", 1, $field->isDisplayInListView(), ["class" => "form-check-input"]); ?>
        <?php echo $form->label("isDisplayInListView", "Display field in list view.", ["class" => "form-check-label"]); ?>
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

<style type="text/css">
    table {
        margin-bottom: 15px;
    }

    .add-option {
        margin-bottom: 30px !important;
    }
</style>

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

            $(".ui-dialog input[name=label]").bind("change keyup", function (e) {
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

            $(".ui-dialog select[name=type]").bind("change", function () {
                let selectedType = $(this).val();

                $("[data-limit-types]").each(function () {
                    let limitedTypes = $(this).data("limitTypes");
                    let hasType = false;

                    for (let limitedType of limitedTypes.split(",")) {
                        if (limitedType.trim() === selectedType) {
                            hasType = true;
                        }
                    }

                    if (hasType) {
                        $(this).removeClass("d-none");
                    } else {
                        $(this).addClass("d-none");
                    }
                });
            }).trigger("change");

            $(".ui-dialog .remove-option").bind("click", function (e) {
                e.preventDefault();

                let $el = $(this);

                ConcreteAlert.confirm(
                    "<?php echo h(t('Are you sure?')); ?>",
                    function() {
                        $el.parent().parent().parent().remove();

                        if ($(".ui-dialog tbody tr").length) {
                            $(".no-options-message").addClass("d-none");
                            $(".options-table").removeClass("d-none");
                        } else {
                            $(".no-options-message").removeClass("d-none");
                            $(".options-table").addClass("d-none");
                        }

                        $.fn.dialog.closeTop();
                    },
                    'btn-danger',
                    "<?php echo h(t('Confirm')); ?>"
                );

                return false;
            });

            $(".add-option").bind("click", function (e) {
                e.preventDefault();

                $(".ui-dialog .options-container").append(_.template($("#option-template").html())({
                    option: {
                        id: Date.now() + Math.random(),
                        value: '',
                        label: ''
                    }
                }));

                $(".ui-dialog .remove-option").unbind().bind("click", function (e) {
                    e.preventDefault();

                    let $el = $(this);

                    ConcreteAlert.confirm(
                        "<?php echo h(t('Are you sure?')); ?>",
                        function() {
                            $el.parent().parent().parent().remove();

                            if ($(".ui-dialog tbody tr").length) {
                                $(".no-options-message").addClass("d-none");
                                $(".options-table").removeClass("d-none");
                            } else {
                                $(".no-options-message").removeClass("d-none");
                                $(".options-table").addClass("d-none");
                            }

                            $.fn.dialog.closeTop();
                        },
                        'btn-danger',
                        "<?php echo h(t('Confirm')); ?>"
                    );

                    return false;
                });

                if ($(".ui-dialog tbody tr").length) {
                    $(".no-options-message").addClass("d-none");
                    $(".options-table").removeClass("d-none");
                } else {
                    $(".no-options-message").removeClass("d-none");
                    $(".options-table").addClass("d-none");
                }

                return false;
            });
        });
    })(jQuery);
</script>