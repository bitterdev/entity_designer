<?php
/**
 * @project:   Entity Designer
 *
 * @author     Fabian Bitter (fabian@bitter.de)
 * @copyright  (C) 2020 Fabian Bitter (www.bitter.de)
 * @version    X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

use Bitter\EntityDesigner\Entity\Entity;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Validation\CSRF\Token;
use Concrete\Core\View\View;

/** @var Entity $entity */
/** @var array $packages */
/** @var $form Form */
/** @var $token Token */
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/help', null, 'entity_designer');
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/reminder', ["packageHandle" => "entity_designer", "rateUrl" => "https://www.concrete5.org/marketplace/addons/entity-designer/reviews"], 'entity_designer');
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/license_check', ["packageHandle" => "entity_designer"], 'entity_designer');

?>

    <form action="#" method="post" id="entityForm">
        <?php echo $token->output('save_entity'); ?>

        <fieldset>
            <legend>
                <?php echo t("General settings"); ?>
            </legend>

            <div class="form-group">
                <?php echo $form->label(
                    "name",
                    t("Name"),
                    [
                        "class" => "control-label launch-tooltip",
                        "title" => t("Enter a name for the entity you want to create. While typing in the name the handle field will be automatically filled too.")
                    ]
                ); ?>

                <span class="text-muted small">
                    <?php echo t("Required") ?>
                </span>

                <?php echo $form->text(
                    "name",
                    $entity->getName(),
                    [
                        "class" => "form-control",
                        "maxlength" => 255,
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
                    "handle", $entity->getHandle(),
                    [
                        "class" => "form-control",
                        "maxlength" => 255,
                        "required" => "required"
                    ]
                ); ?>
            </div>

            <div class="form-group">
                <?php echo $form->label(
                    "packageHandle",
                    t("Package"),
                    [
                        "class" => "control-label launch-tooltip",
                        "title" => t("If you want to create the entity within a package you can selected the destination package here.")
                    ]
                ); ?>

                <div class="input-group">
                    <?php echo $form->select(
                        "packageHandle",
                        $packages,
                        $entity->getPackageHandle(),
                        [
                            "class" => "form-control"
                        ]
                    ); ?>

                    <span class="input-group-btn">
                        <button class="btn btn-outline-primary" id="createPackage" type="button">
                            <i class="fa fa-plus" aria-hidden="true"></i> <?php echo t("Create Package"); ?>
                        </button>
                    </span>
                </div>

                <p class="help-block">
                    <?php echo t("If you want to generate the entity within a package please be aware that the install code for this entity will be injected in the package controller. In this process the code formatting of the package controller get lost."); ?>
                </p>
            </div>

            <div class="form-group">
                <?php echo $form->label(
                    "parentPage",
                    t("Parent Page"),
                    [
                        "class" => "control-label launch-tooltip",
                        "title" => t("Select the parent page for this entity.")
                    ]
                ); ?>

                <div>
                    <?php echo $form->hidden("parentPage", (string)$entity->getParentPageId()); ?>

                    <div id="pageSelector" class="ccm-sitemap-tree"></div>
                </div>
            </div>


            <div class="form-group">
                <?php echo $form->label(
                    "displayValue",
                    t("Display Value"),
                    [
                        "class" => "control-label launch-tooltip",
                        "title" => t("Here you can define the display value for the entity. This value will be used when this entity is associated with another entity as identification string. You can use all text or number fields of the entity by auto complete mixed with custom content.")
                    ]
                ); ?>

                <span class="text-muted small">
                    <?php echo t("Required") ?>
                </span>

                <?php echo $form->text(
                    "displayValue",
                    $entity->getDisplayValue(),
                    [
                        "class" => "form-control",
                        "maxlength" => 255
                    ]
                ); ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>
                <?php echo t("List View"); ?>
            </legend>

            <div class="form-group">
                <?php echo $form->label(
                    "listViewHelpText",
                    t("Help text"),
                    [
                        "class" => "control-label launch-tooltip",
                        "title" => t("Here you can enter a help text for the entire view. The help text will be shown in a blue box in the page header.")
                    ]
                ); ?>

                <?php echo $form->textarea(
                    "listViewHelpText",
                    $entity->getListViewHelpText(),
                    [
                        "class" => "form-control"
                    ]
                ); ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>
                <?php echo t("Detail View"); ?>
            </legend>

            <div class="form-group">
                <?php echo $form->label(
                    "detailViewHelpText",
                    t("Help text"),
                    [
                        "class" => "control-label launch-tooltip",
                        "title" => t("Here you can enter a help text for the entire view. The help text will be shown in a blue box in the page header.")
                    ]
                ); ?>

                <?php echo $form->textarea(
                    "detailViewHelpText",
                    $entity->getDetailViewHelpText(),
                    [
                        "class" => "form-control"
                    ]
                ); ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>
                <?php echo t("Fields"); ?>
            </legend>

            <div class="alert alert-warning no-fields-message <?php echo empty($entity->getFields()) ? " " : " d-none"; ?>">
                <?php echo t("Click the »Add Field« button in the header to start creating fields."); ?>
            </div>

            <table class="table table-striped fields-table <?php echo empty($entity->getFields()) ? " d-none" : ""; ?>">
                <thead>
                <tr>
                    <th>
                        <?php echo t("Label"); ?>
                    </th>

                    <th>
                        &nbsp;
                    </th>
                </tr>
                </thead>

                <tbody id="fields-container">
                <?php if (!empty($entity->getFields())): ?>
                    <?php foreach ($entity->getFields() as $field): ?>
                        <tr>
                            <td>
                                <?php echo $field->getLabel(); ?>
                            </td>

                            <td>
                                <div class="float-end">
                                    <a href="javascript:void(0)" class="btn btn-default edit-field"
                                       data-field-id="<?php echo h($field->getId()); ?>">
                                        <i class="fa fa-pencil"></i> <?php echo t("Edit field"); ?>
                                    </a>

                                    <a href="javascript:void(0)" class="btn btn-danger remove-field"
                                       data-field-id="<?php echo h($field->getId()); ?>">
                                        <i class="fa fa-trash"></i> <?php echo t("Remove field"); ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </fieldset>

        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <a href="<?php echo Url::to("/dashboard/entity_designer"); ?>" class="btn btn-default">
                    <i class="fa fa-chevron-left"></i> <?php echo t('Back'); ?>
                </a>

                <div class="float-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> <?php echo t("Save"); ?>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <style type="text/css">
        #autoCompleteContainer {
            display: block;
            position:relative
        }

        .ui-autocomplete {
            position: absolute;
        }
    </style>

    <script id="fields-template" type="text/template">
        <% for(let field of fields) { %>
        <tr>
            <td>
                <%=field.label%>
            </td>

            <td>
                <div class="float-end">
                    <a href="javascript:void(0)" class="btn btn-default edit-field"
                       data-field-id="<%=field.id%>">
                        <i class="fa fa-pencil"></i> <?php echo t("Edit field"); ?>
                    </a>

                    <a href="javascript:void(0)" class="btn btn-danger remove-field"
                       data-field-id="<%=field.id%>">
                        <i class="fa fa-trash"></i> <?php echo t("Remove field"); ?>
                    </a>
                </div>
            </td>
        </tr>
        <% } %>
    </script>

    <!--suppress JSUnresolvedVariable, JSUnfilteredForInLoop -->
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

                $("button[type=submit]").bind("click", function (e) {
                    e.preventDefault();

                    ConcreteAlert.confirm(
                        "<?php echo h(t('If you confirm this dialog all files and the database schema will be regenerated and all manual code changes within the generated files get lost. Furthermore all generated blocks from this entity that are added in the frontend of the site will be removed and the current database schema including data will be removed. Are you sure that you want to proceed?')); ?>",
                        function () {
                            $("#entityForm").submit();
                        },
                        'btn-danger',
                        "<?php echo h(t('Confirm')); ?>"
                    );

                    return false;
                });

                $("#name").bind("change keyup", function (e) {
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
                            $("#handle").val((data.handle));
                        }
                    });

                    return false;
                });

                let updateEventHandlers = function () {

                    $(".edit-field").bind("click", function (e) {
                        e.preventDefault();

                        $.fn.dialog.open({
                            href: '<?php echo h(Url::to("/bitter/entity_designer/dialogs/edit_field/edit")); ?>/' + $(this).data("fieldId"),
                            title: '<?php echo h(t("Edit Field")); ?>',
                            width: '600',
                            height: '80%',
                            modal: true,
                            close: updateFields
                        });

                        return false;
                    });

                    $(".remove-field").unbind().bind("click", function (e) {
                        e.preventDefault();

                        let fieldId = $(this).data("fieldId");

                        ConcreteAlert.confirm(
                            "<?php echo h(t('Are you sure?')); ?>",
                            function () {
                                $.ajax({
                                    url: "<?php echo h(Url::to("/dashboard/entity_designer/remove_field")); ?>",
                                    data: {
                                        id: fieldId,
                                    },
                                    success: function () {
                                        updateFields();
                                        $.fn.dialog.closeTop();
                                    }
                                });
                            },
                            'btn-danger',
                            "<?php echo h(t('Confirm')); ?>"
                        );

                        return false;
                    });
                };

                let initDashbaordPageSelector = function () {
                    $("#pageSelector").concreteSitemap({
                        selectMode: 'single',
                        minExpandLevel: 0,
                        siteTreeID: 0,
                        dataSource: "<?php echo (string)Url::to('/dashboard/entity_designer/get_sitemap'); ?>",
                        lazyLoad: false,
                        ajaxData: {
                            startingPoint: <?php echo json_encode(Page::getHomePageID()); ?>,
                            ccm_token: <?php echo json_encode($token->generate('select_sitemap')); ?>,
                            filters: []
                        },
                        onSelectNode: function(node, flag) {
                            $("#parentPage").val(node.key);
                        }
                    });
                };

                let updatePackages = function () {
                    $.ajax({
                        url: "<?php echo h(Url::to("/dashboard/entity_designer/get_packages")); ?>",
                        success: function (data) {
                            let $packageHandle = $("#packageHandle");

                            $packageHandle.html("");

                            for (let optionValue in data.packages) {
                                let optionName = data.packages[optionValue];
                                let optionElement = new Option(optionName, optionValue);
                                $packageHandle.append(optionElement);
                            }
                        }
                    });
                };

                let availableFields = [];

                let isAllowedType = function(type) {
                    let allowedTypes = <?php echo json_encode([
                        \Bitter\EntityDesigner\Enumeration\FieldType::FIELD_TYPE_TEXT,
                        \Bitter\EntityDesigner\Enumeration\FieldType::FIELD_TYPE_NUMBER,
                        \Bitter\EntityDesigner\Enumeration\FieldType::FIELD_TYPE_EMAIL,
                        \Bitter\EntityDesigner\Enumeration\FieldType::FIELD_TYPE_PASSWORD,
                        \Bitter\EntityDesigner\Enumeration\FieldType::FIELD_TYPE_TEXT_AREA,
                        \Bitter\EntityDesigner\Enumeration\FieldType::FIELD_TYPE_WEB_ADDRESS,
                        \Bitter\EntityDesigner\Enumeration\FieldType::FIELD_TYPE_TELEPHONE
                    ]); ?>;

                    return allowedTypes.indexOf(type) > -1;
                };


                let updateFields = function () {
                    $.ajax({
                        url: "<?php echo h(Url::to("/dashboard/entity_designer/get_fields", $entity->getId())); ?>",
                        success: function (data) {
                            $("#fields-container").html(_.template($("#fields-template").html())({
                                fields: data.fields
                            }));

                            if (data.fields.length) {
                                $(".no-fields-message").addClass("d-none");
                                $(".fields-table").removeClass("d-none");
                            } else {
                                $(".no-fields-message").removeClass("d-none");
                                $(".fields-table").addClass("d-none");
                            }

                            updateEventHandlers();
                        }
                    });
                };

                $("#createPackage").bind("click", function (e) {
                    e.preventDefault();

                    $.fn.dialog.open({
                        href: '<?php echo h(Url::to("/bitter/entity_designer/dialogs/add_package/add")); ?>',
                        title: '<?php echo h(t("Create Package")); ?>',
                        width: '600',
                        height: '80%',
                        modal: true,
                        close: updatePackages
                    });

                    return false;
                });

                $(".ccm-dashboard-header-menu a").bind("click", function (e) {
                    e.preventDefault();

                    $.fn.dialog.open({
                        href: '<?php echo h(Url::to("/bitter/entity_designer/dialogs/edit_field/add", $entity->getId())); ?>',
                        title: '<?php echo h(t("Add Field")); ?>',
                        width: '600',
                        height: '80%',
                        modal: true,
                        close: updateFields
                    });

                    return false;
                });

                updateEventHandlers();
                initDashbaordPageSelector();
                updateFields();
            });
        })(jQuery);
    </script>

<?php
/** @noinspection PhpUnhandledExceptionInspection */
View::element('/dashboard/did_you_know', ["packageHandle" => "entity_designer"], 'entity_designer');
?>