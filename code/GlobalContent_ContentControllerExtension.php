<?php

class GlobalContent_ContentControllerExtension extends Extension
{

    public function getGlobalContent()
    {
        return GlobalContent::inst();
    }

}