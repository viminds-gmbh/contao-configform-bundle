<?php

/**
 * Contao Open Source CMS
 *
 * Config Form Extension
 *
 * @copyright  Qbus
 * @author     Alex Wuttke <alw@qbus.de>
 * @license    LGPL-3.0+
 */

namespace Qbus\ConfigFormBundle\Form;

use Qbus\TransientForm\TransientFormModel;
use Qbus\TransientForm\TransientFormFieldModel;
use Contao\Controller;

class Form
{

	protected $arrFormConfig;

	protected $objForm;

	public function __construct($arrFormConfig) {
		$this->arrFormConfig = $arrFormConfig;
		$this->objForm = $this->getFormInstance();
	}

	public function generate() {
		if ($this->objForm === null) {
			return '';
		}

		$strForm = Controller::getForm($this->objForm);

		// TODO: What if something is inserting rows into the form database
		//       tables during frontend rendering?
		//       This provision should be implemented, but it can't be done here
		//       because then the IDs would start over every time Form is used.
		/*
		$objForm->unregisterTransient();
		foreach ($arrFormFields as $objFormFieldToUnregister) {
			$objFormFieldToUnregister->unregisterTransient();
		}
		*/

		return $strForm;
	}

	public function getFormInstance() {
		if ($this->objForm instanceof TransientFormModel) {
			return $this->objForm;
		}

		if (
			!is_array($this->arrFormConfig)
			|| !array_key_exists('tl_form', $this->arrFormConfig)
			|| !array_key_exists('tl_form_field', $this->arrFormConfig)
		) {
			return null;
		}

		$arrFormFields = [];
		$arrFormFieldIds = [];

		foreach ($this->arrFormConfig['tl_form_field'] as $arrFormField) {
			$objFormField = new TransientFormFieldModel;
			$objFormField->setRow($arrFormField);
			$objFormField->registerTransient();
			// Currently not used, see "TODO: What if ..."
			// $arrFormFields[] = $objFormField;
			$arrFormFieldIds[] = $objFormField->id;
		}

		$objForm = new TransientFormModel;
		$objForm->setRow($this->arrFormConfig['tl_form']);
		$objForm->registerTransient();
		$objForm->addFormFields($arrFormFieldIds);

		return $objForm;
	}

}
