# Installation Instructions #
## Composer ##
Run the following to add this module as a requirement and install it via composer.

```
#!bash

composer require "webfox/silverstripe-global-content"
```
then browse to /dev/build?flush=all


#Requirements#
* Silverstripe 3.2+
* php5.4+ 

#Module Overview#
This module adds a convenient `SiteConfig` like interface for managing global content.
 Useful for when you want global content but don't want to give content-authors access to `SiteConfig`
 
#Module Usage#
Too add additional fields:
* Create a `DataExtension` that gets applied to `GlobalContent`
* The extension requires an `updateCMSFields(FieldList $fields)` method and any standard `DataExtension` properties 

```php
class GlobalContentExtension extends DataExtension 
{

    protected static $db = [
        'MyFieldName' => 'Varchar'
    ];
    
    public function updateCMSFields(FieldList $fields)
    {
    
        $fields->addFieldToTab(
            'Root.Main', 
            TextField::create('MyFieldName', 'My field name')
        );
    
    }

}
```

The use with permissions:
* Grant the user/role/group the `Access to 'Global Content' section` permission

To use in templates:
* `$GlobalContent.MyFieldName`
* `<% with $GlobalContent %> {$MyFieldName} <% end_with %>`
* `$GlobalContent('MyFieldName')`

To use in PHP:
* `GlobalContent::inst()->MyFieldName`

To alter the edit form directly:
* Create a new `LeftAndMainExtension` that gets applied to `GlobalContentAdmin`
* The extension can use the `updateEditForm($form)` method to update the form **before** data is loaded.
* The extension can use the `updateEditFormData($form)` method to update the form **after** data is loaded.
