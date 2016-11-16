<?php

class GlobalContentTemplateProvider implements TemplateGlobalProvider {

	public static function get_template_global_variables() {
		return [
			'GlobalContent',
			'Dump'
        ];
	}

	public static function GlobalContent($key = null){
	    $inst = GlobalContent::inst();
	    return (is_null($key) ? $inst : $inst->{$key});
	}

}
