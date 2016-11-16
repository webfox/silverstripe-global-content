<?php

class GlobalContentAdmin extends LeftAndMain implements PermissionProvider
{
    private static $url_segment = 'global-content';

    private static $url_rule = '/$Action/$ID/$OtherID';

    private static $menu_title = 'Global Content';

    private static $tree_class = 'GlobalContent';

    private static $subitem_class = 'GlobalContent';

    protected static $menu_icon = 'silverstripe-global-content/images/globe-icon.png';

    private static $allowed_actions = [
        'EditForm',
    ];

    public function init()
    {
        parent::init();
    }

    public function getEditForm($id = null, $fields = null)
    {
        $record  = GlobalContent::inst();
        $fields  = $record->getCMSFields();
        $actions = $this->getCMSActions($record);

        if ($record && !$record->canView()) {
            return Security::permissionFailure($this);
        }

        // Tab nav in CMS is rendered through separate template
        /** @var TabSet $root */
        $root = $fields->fieldByName('Root');
        $root->setTemplate('CMSTabSet');

        $form = $this->buildForm($fields, $actions);

        $this->extend('updateEditForm', $form);
        $form->loadDataFrom($record);
        $this->extend('updateEditFormWithData', $form);

        return $form;
    }

    public function getCMSActions(GlobalContent $record)
    {
        if ($record->hasMethod('getAllCMSActions')) {
            $actions = $record->getAllCMSActions();
        } else {
            $actions = $record->getCMSActions();
            // add default actions if none are defined
            if (!$actions || !$actions->Count()) {
                if ($record->hasMethod('canEdit') && $record->canEdit()) {
                    $actions->push(
                        FormAction::create('save', _t('CMSMain.SAVE', 'Save'))
                            ->addExtraClass('ss-ui-action-constructive')->setAttribute('data-icon', 'accept')
                    );
                }
                if ($record->hasMethod('canDelete') && $record->canDelete()) {
                    $actions->push(
                        FormAction::create('delete', _t('ModelAdmin.DELETE', 'Delete'))
                            ->addExtraClass('ss-ui-action-destructive')
                    );
                }
            }
        }

        // Use <button> to allow full jQuery UI styling
        $actionsFlattened = $actions->dataFields();
        if ($actionsFlattened) {
            foreach ($actionsFlattened as $action) {
                $action->setUseButtonTag(true);
            }
        }

        return $actions;
    }

    protected function buildForm(FieldList $fields, FieldList $actions){
        $form = CMSForm::create(
            $this,
            'EditForm',
            $fields,
            $actions
        )->setHTMLID('Form_EditForm');

        $form->setResponseNegotiator($this->getResponseNegotiator());
        $form->addExtraClass('cms-edit-form');
        $form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
        // Tab nav in CMS is rendered through separate template
        if ($form->Fields()->hasTabset()) {
            $form->Fields()->findOrMakeTab('Root')->setTemplate('CMSTabSet');
        }
        $form->addExtraClass('center ss-tabset cms-tabset ' . $this->BaseCSSClasses());
        $form->setAttribute('data-pjax-fragment', 'CurrentForm');

        return $form;
    }

    public function Backlink()
    {
        return false;
    }

    public function Breadcrumbs($unlinked = false)
    {
        return ArrayList::create([
            ArrayData::create([
                'Title' => singleton('GlobalContent')->i18n_singular_name(),
                'Link'  => $this->Link()
            ])
        ]);
    }

    public function providePermissions()
    {
        $title = _t("GlobalContentAdmin.MENUTITLE", LeftAndMain::menu_title_for_class($this->class));
        return [
            "CMS_ACCESS_GlobalContentAdmin" => [
                'name'     => _t('CMSMain.ACCESS', "Access to '{title}' section", ['title' => $title]),
                'category' => _t('Permission.CMS_ACCESS_CATEGORY', 'CMS Access'),
                'help'     => _t('GlobalContentAdmin.ACCESS_HELP', "Allow viewing and editing data in the '{title}' section", ['title' => $title]),
            ]
        ];
    }

}