<?php

class GlobalContent extends DataObject
{
    /**
     * Return the GlobalContent instance
     *
     * @return self
     */
    public static function inst()
    {
        return static::get_one(__CLASS__);
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->add(HiddenField::create('ID', 'ID', $this->ID));

        /** @var TabSet $rootTabset */
        $rootTabset = $fields->fieldByName('Root');
        $rootTabset->setTemplate('TablessCMSTabSet');

        return $fields;
    }


    public function requireDefaultRecords()
    {
        if (!static::inst()) {
            /** @var self $config */
            $config = new static();
            $config->write();

            DB::alteration_message('Created default Global Content instance', 'created');
        }
    }

    public function canView($member = null)
    {
        return true;
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_GlobalContent_ModelAdmin', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return false;
    }

    public function canCreate($member = null)
    {
        return false;
    }

}