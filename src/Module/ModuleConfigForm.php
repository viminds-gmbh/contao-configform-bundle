<?php

namespace Qbus\ConfigFormBundle\Module;

use Contao\Module as ContaoModule;
use Contao\BackendTemplate;
use Contao\System;
use Patchwork\Utf8;
use Qbus\ConfigFormBundle\Form\Form as ConfigForm;

class ModuleConfigForm extends ContaoModule
{

	protected $strTemplate = 'mod_configform';

	public function generate() {
		$container = System::getContainer();
		$scopeMatcher = $container->get('contao.routing.scope_matcher');
		$requestStack = $container->get('request_stack');
		if ($scopeMatcher->isBackendRequest($requestStack->getCurrentRequest()))
		{
			$beTemplate = new BackendTemplate('be_wildcard');

			$beTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['configform'][0]) . ' ###';
			$beTemplate->title = $this->headline;
			$beTemplate->id = $this->id;
			$beTemplate->link = $this->name;
			$beTemplate->href = 'contao?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $beTemplate->parse();
		}

		return parent::generate();
	}

	protected function compile() {
		$container = System::getContainer();
		$rootDir = $container->getParameter('kernel.project_dir');
		$formConfig = null;
		$configFile = $rootDir . $this->formconfig . '.php';
		if (file_exists($configFile)) {
			$formConfig = include $configFile;
		}
		$configForm = new ConfigForm($formConfig);
		foreach ($this->arrData as $key => $property) {
			$configForm->$key = $property;
		}
		$this->Template->form = $configForm->generate();
		$this->Template->objForm = $configForm->getFormInstance();
	}

}
