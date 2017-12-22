<?php
session_start();

	//echo $_SERVER["REQUEST_URI"] . "<br />";

	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/modele.php"; 

	$data["action"] = valider("action");
	$data["feedback"] = false;

	// si on a une action, on devrait avoir un message classique
	$data["feedback"] = "entrez action: ";

	switch($data["action"])
	{


		// Galeries //////////////////////////////////////////////////
		case 'getGaleries' :
			$data["galeries"] = getGaleries(); 
			$data["feedback"] = true;
		break;


		// Images //////////////////////////////////////////////////
		case 'getImages' :
			if ($idGalerie = valider("idGalerie")) {
				$data["images"] = getImages($idGalerie);
				$data["feedback"] = true;
			}
			else $data["images"] = array();
		break;

		case 'updateLegende' :
			if ($idImage = valider("idImage")) 
			if ($legende = valider("legende")) {
				updateLegende($idImage, $legende); 
				$data["feedback"] = true;
			}
			
		break;

		// Defaut //////////////////////////////////////////////////

		default : 				
			$data["action"] = "default";
			$data["feedback"] = false;

	}

		
	 
	echo json_encode($data);

?>










