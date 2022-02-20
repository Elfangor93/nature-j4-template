<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.nature
 *
 * @copyright   (C) 2021 Dr. Menzel IT. <https://www.dr-menzel-it.de>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Installer\InstallerScript;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since  1.0.6v1
 */
class NatureInstallerScript extends InstallerScript
{
/**
	 * Move nature template (s)css or js or image files which are left after deleting
	 * obsolete core files to the right place in media folder.
	 *
	 * @return  void
	 *
	 * @since   4.1.0
	 */
	protected function moveTemplateFiles()
	{
		$folders = [
			'/templates/nature/css' => '/media/templates/site/nature/css',
			'/templates/nature/images' => '/media/templates/site/nature/images',
			'/templates/nature/js' => '/media/templates/site/nature/js',
			'/templates/nature/fonts' => '/media/templates/site/nature/fonts',
		];

		foreach ($folders as $oldFolder => $newFolder)
		{
			if (Folder::exists(JPATH_ROOT . $oldFolder))
			{
				$oldPath   = realpath(JPATH_ROOT . $oldFolder);
				$newPath   = realpath(JPATH_ROOT . $newFolder);
				$directory = new \RecursiveDirectoryIterator($oldPath);
				$directory->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
				$iterator  = new \RecursiveIteratorIterator($directory);

				// Handle all files in this folder and all sub-folders
				foreach ($iterator as $oldFile)
				{
					if ($oldFile->isDir())
					{
						continue;
					}

					$newFile = $newPath . substr($oldFile, strlen($oldPath));

					// Create target folder and parent folders if they don't exist yet
					if (is_dir(dirname($newFile)) || @mkdir(dirname($newFile), 0755, true))
					{
						File::move($oldFile, $newFile);
					}
				}
			}
		}
	}
		/**
	 * Function to perform changes during postflight
	 *
	 * @param   string            $type    The action being performed
	 * @param   ComponentAdapter  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   1.0.6v1
	 */
	public function postflight($type, $parent)
	{
		if ($type == 'update')
		{
			$this->moveTemplateFiles();
		}
	}
}