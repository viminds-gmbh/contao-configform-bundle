<?php

namespace Qbus\ConfigFormBundle\DataContainer;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\DataContainer;
use Contao\ThemeModel;

class Module
{

	const FILE_PREFIX = 'formconfig_';
	const FILE_EXTENSION = 'php';

	private $framework;
	private $projectDir;

	public function __construct(\Contao\CoreBundle\Framework\ContaoFramework $framework, string $projectDir) {
		$this->framework = $framework;
		$this->projectDir = $projectDir;
	}

	public function optionsFormConfig(DataContainer $dc) {
		$rootDir = $this->projectDir;
		$fileGlob = self::FILE_PREFIX . '*.' . self::FILE_EXTENSION;
		$configFiles = glob($rootDir . '/templates/' . $fileGlob) ?: [];
		$themeModelAdapter = $this->framework->getAdapter(ThemeModel::class);
		$theme = $themeModelAdapter->findByPk($dc->activeRecord->pid);
		if ($theme !== null && $theme->templates) {
			$configFiles = array_merge(
				$configFiles,
				glob($rootDir . '/' . $theme->templates . '/' . $fileGlob) ?: []
			);
		}
		$options = [];
		foreach ($configFiles as $file) {
			$path = str_replace([$rootDir, '.php'], '', $file);
			$name = str_replace('/templates/', '', $path);
			$options[$path] = $name;
		}

		return $options;
	}

}
