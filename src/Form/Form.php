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
use Contao\Module;

class Form
{

	protected $arrFormConfig;

	protected $objForm;

	protected $arrData;

	/**
	 * Set an object property
	 *
	 * @param string $strKey
	 * @param mixed  $varValue
	 */
	public function __set($strKey, $varValue) {
		$this->arrData[$strKey] = $varValue;
	}

	/**
	 * Return an object property
	 *
	 * @param string $strKey
	 *
	 * @return mixed
	 */
	public function __get($strKey) {
		if (isset($this->arrData[$strKey])) {
			return $this->arrData[$strKey];
		}

		return null;
	}

	/**
	 * Check whether a property is set
	 *
	 * @param string $strKey
	 *
	 * @return boolean
	 */
	public function __isset($strKey) {
		return isset($this->arrData[$strKey]);
	}

	public function __construct($arrFormConfig) {
		$this->arrFormConfig = $arrFormConfig;
		$this->objForm = $this->getFormInstance();
	}

	public function generate() {
		if ($this->objForm === null) {
			return '';
		}

		$objForm = $this->objForm;

		$elementClass = Module::findClass('form');

		if (!class_exists($elementClass)) {
			System::log('Form class "'.$elementClass.'" does not exist', __METHOD__, TL_ERROR);

			return '';
		}

		$objForm->typePrefix = 'mod_';
		$objForm->form = $objForm->id;

		$objElement = new $elementClass($objForm, 'main');
		$objElement->hl = $this->hl;
		$objElement->headline = $this->headline;
		if (empty($objForm->cssID)) {
			$objElement->cssID = $this->cssID;
		}

		$strForm = $objElement->generate();

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
			// Currently not usable this way, see "TODO: What if ..."
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
