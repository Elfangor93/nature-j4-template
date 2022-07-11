<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\AuthenticationHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

$twofactormethods = AuthenticationHelper::getTwoFactorMethods();
$app              = Factory::getApplication();
$wa               = $this->getWebAssetManager();
$document         = $app->getDocument();
$params 		  = $this->params;

// Set generator
$this->setGenerator('Manuel Häusler (tech.spuur.ch)');

// Template path
$templatePath = 'media/templates/site/nature';

// Use a font scheme if set in the template style options
$paramsFontScheme = $params->get('useFontScheme', false);

if ($paramsFontScheme)
{
	$assetFontScheme  = 'fontscheme.' . $paramsFontScheme;
	$wa->registerAndUseStyle($assetFontScheme, $templatePath . '/css/global/' . $paramsFontScheme . '.css');
	$this->getPreloadManager()->preload($wa->getAsset('style', $assetFontScheme)->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
}

// Enable assets
$wa->useStyle('template.nature')
	 ->useScript('template.nature')
	 ->useStyle('template.user')
	 ->useScript('template.user')
   ->useStyle('template.offline');
$this->getPreloadManager()->preload($wa->getAsset('style', 'template.nature')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);
$this->getPreloadManager()->preload($wa->getAsset('style', 'template.offline')->getUri() . '?' . $this->getMediaVersion(), ['as' => 'style']);

// Logo file or site title param
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

// Preprocess logo
$logo_path_arr = explode('#', htmlspecialchars($params->get('logoFile'), ENT_QUOTES));
list($width, $height, $type, $logo_attr) = getimagesize($logo_path_arr[0]);

if ($params->get('logoFile'))
{
	$logo = '<img src="' . Uri::root() . htmlspecialchars($params->get('logoFile'), ENT_QUOTES) . '" alt="' . $sitename . '" '.$logo_attr.'>';
}
elseif ($params->get('siteTitle'))
{
	$logo = '<span title="' . $sitename . '">' . htmlspecialchars($params->get('siteTitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<img src="' . $templatePath . '/images/nature-logo.png" class="logo" alt="' . $sitename . '">';
}

// Favicons
include 'includes/favicons.php';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<?php include "includes/style.php";?>
	<jdoc:include type="styles" />
	<jdoc:include type="scripts" />
</head>
<body class="site">
	<div class="outer">
		<div class="offline-card">
			<div class="header">
				<?php if (!empty($logo)) : ?>
					<h1><?php echo $logo; ?></h1>
				<?php else : ?>
					<h1><?php echo $sitename; ?></h1>
				<?php endif; ?>
				<?php if ($app->get('offline_image')) : ?>
					<?php echo HTMLHelper::_('image', $app->get('offline_image'), $sitename, [], false, 0); ?>
				<?php endif; ?>
				<?php if ($app->get('display_offline_message', 1) == 1 && str_replace(' ', '', $app->get('offline_message')) != '') : ?>
					<p><?php echo $app->get('offline_message'); ?></p>
          <p>
            <h2>Kontakt:</h2>
            Telefon: 041 836 04 04<br />
            <a id="mail" href="#"><span class="<?php echo $icon; ?>"></span> E-Mail anzeigen</a>
          </p>
				<?php elseif ($app->get('display_offline_message', 1) == 2) : ?>
					<p><?php echo Text::_('JOFFLINE_MESSAGE'); ?></p>
				<?php endif; ?>
			</div>
			<div class="login">
				<jdoc:include type="message" />
				<form action="<?php echo Route::_('index.php', true); ?>" method="post" id="form-login">
					<fieldset>
						<label for="username"><?php echo Text::_('JGLOBAL_USERNAME'); ?></label>
						<input name="username" class="form-control" id="username" type="text">

						<label for="password"><?php echo Text::_('JGLOBAL_PASSWORD'); ?></label>
						<input name="password" class="form-control" id="password" type="password">

						<?php if (count($twofactormethods) > 1) : ?>
						<label for="secretkey"><?php echo Text::_('JGLOBAL_SECRETKEY'); ?></label>
						<input name="secretkey" autocomplete="one-time-code" class="form-control" id="secretkey" type="text">
						<?php endif; ?>

						<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo Text::_('JLOGIN'); ?>">

						<input type="hidden" name="option" value="com_users">
						<input type="hidden" name="task" value="user.login">
						<input type="hidden" name="return" value="<?php echo base64_encode(Uri::base()); ?>">
						<?php echo HTMLHelper::_('form.token'); ?>
					</fieldset>
				</form>
			</div>
		</div>
	</div>

  <script>
    let link = document.getElementById('mail');

    link.onclick = function()
    {
      if (link.innerHTML.includes("E-Mail anzeigen"))
      {
        let adress = "info"
        let domain = "iten-bewaesserungen.ch";
        let linker = adress + '@' + domain;
        link.innerHTML = linker;
        link.href = "mailto:" + linker;
        return false;
      } else {
        return
      }		    
    };
</script>
</body>
</html>
