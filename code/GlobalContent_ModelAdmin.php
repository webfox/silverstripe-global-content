<?php

class GlobalContent_ModelAdmin extends ModelAdmin
{
    private static $managed_models = ['GlobalContent'];

    private static $url_segment = 'global-content';

    private static $menu_title = 'Global Content';

    protected static $menu_icon = 'silverstripe-global-content/images/globe-icon.png';

    protected static $tree_class = 'GlobalContent';

    public function getEditForm($id = null, $fields = null)
    {
        $fields  = GlobalContent::inst()->getCMSFields();
        $actions = FieldList::create(
            FormAction::create('save', _t('GridFieldDetailForm.Save', 'Save'))
                      ->setUseButtonTag(true)->addExtraClass('ss-ui-action-constructive')
        );

        GlobalContent::inst()->extend('updateCMSActions', $actions);

        if (GlobalContent::inst()->hasMethod('getCMSValidator')) {
            $validator = GlobalContent::inst()->getCMSValidator();
        } else {
            $validator = RequiredFields::create();
        }
        GlobalContent::inst()->extend('updateCMSValidator', $validator);

        /** @var CMSForm $form */
        $form = CMSForm::create($this, 'EditForm', $fields, $actions, $validator);
        $form->loadDataFrom(GlobalContent::inst());
        $form->addExtraClass('cms-edit-form center');
        $form->setTemplate($this->getTemplatesWithSuffix('_EditForm'));
        $form->setHTMLID('Form_EditForm');
        $form->setResponseNegotiator($this->getResponseNegotiator());
        $form->setAttribute('data-pjax-fragment', 'CurrentForm');
        $form->setFormAction(Controller::join_links(
            $this->Link($this->sanitiseClassName($this->modelClass)),
            'EditForm'
        ));

        $this->extend('updateEditForm', $form);

        return $form;
    }

    public function saveSettings($data, Form $form)
    {

        $globalContentInstance = GlobalContent::inst();

        $form->saveInto($globalContentInstance);
        $form->sessionMessage('Global content updated', 'good');

        return $this->getResponseNegotiator()->respond($this->request);
    }

    public function save($data, $form)
    {
        return parent::save($data, $form);
    }


}