<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\DB\Location;
use \Classes\Webforce3\Helpers\SelectHelper;

// Get the config object
$conf = Config::getInstance();

$locationId = isset($_GET['loc_id']) ? intval($_GET['loc_id']) : 0;
$locationObject = new Location();

// Récupère la liste complète des country en DB
$locationsList = Location::getAllForSelect();

// Si modification d'une ville, on charge les données pour le formulaire
if ($locationId > 0) {
	$locationObject = Location::get($countryId);
}

// Si lien suppression
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
	if (Country::deleteById(intval($_GET['delete']))) {
		header('Location: locaction.php?success='.urlencode('Suppression effectuée'));
		exit;
	}
}

// Formulaire soumis
if(!empty($_POST)) {
	$locationId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $locationName = isset($_POST['loc_name']) ? trim($_POST['loc_name']) : '';

    if (empty($locationName)) {
		$conf->addError('Veuillez renseigner le nom');
	}
    
    // je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
	$locationObject = new Country(
		$locationId,
		$loactionName
	);
    
    // Si tout est ok => en DB
	if (!$conf->haveError()) {
		if ($locationObject->saveDB()) {
			header('Location: loaction.php?success='.urlencode('Ajout/Modification effectuée').'&loc_id='.$locationObject->getId());
			exit;
		}
		else {
			$conf->addError('Erreur dans l\'ajout ou la modification');
		}
	}
}

$selectLocations = new SelectHelper($locationsList, $locationId, array(
	'name' => 'loc_id',
	'id' => 'loc_id',
	'class' => 'form-control',
));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'location.php';
require $conf->getViewsDir().'footer.php';