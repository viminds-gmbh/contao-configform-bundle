<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['configform'] = '{title_legend},name,headline,type;{template_legend:hide},formconfig,customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['fields']['formconfig'] = [
	'label'            => &$GLOBALS['TL_LANG']['tl_module']['formconfig'],
	'exclude'          => true,
	'inputType'        => 'select',
	'options_callback' => ['qbus_config_form.data_container.module', 'optionsFormConfig'],
	'eval'             => ['includeBlankOption' => true, 'tl_class' => 'w50'],
	'sql'              => "varchar(64) NOT NULL default ''"
];
