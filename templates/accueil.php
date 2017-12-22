<?php
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 
//C'est la propriété php_self qui nous l'indique : 
// Quand on vient de index : 
// [PHP_SELF] => /chatISIG/index.php 
// Quand on vient directement par le répertoire templates
// [PHP_SELF] => /chatISIG/templates/accueil.php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
// Pas de soucis de bufferisation, puisque c'est dans le cas où on appelle directement la page sans son contexte
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
	header("Location:../index.php?view=accueil");
	die("");
}

?>
<style>
#check
{
width : 30px;
height : 30px;
}
</style>
<!-- <style>
body{
	background-color:red;
}
.form-control {
display: block;
width: 100%;
height: 34px;
padding: 6px 12px;
		 font-size: 14px;
		 line-height: 1.42857143;
color: #555;
	   background-color: #fff;
	   background-image: none;
border: 1px solid #ccc;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
		-webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
		-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}
</style> -->

<link href="./bootstrap/css/bootstrap.css" rel="stylesheet">

<html>
<head>
<style>
</style>
</head>

<body>

<h1>Gestion des r&eacutepertoires </h1>
<form>
<label>Cr&eacuteer un nouveau r&eacutepertoire : </label>
<input type="text" style="width:50%" class="form-control" name="nomRep"/>
<input type="submit" class="btn btn-default" name="action" value="Creer" />
</form>

<form>
<label>Choisir un r&eacutepertoire : </label>
<select name="nomRep" style="width:50%" class="form-control">
<?php

//C'est la propriété php_self qui nous l'indique : 
// Quand on vient de index : 
// [PHP_SELF] => /chatISIG/index.php 
// Quand on vient directement par le répertoire templates
// [PHP_SELF] => /chatISIG/templates/accueil.php

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
// Pas de soucis de bufferisation, puisque c'est dans le cas où on appelle directement la page sans son contexte


// Pose qq soucis avec certains serveurs...
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";

if (isset($_REQUEST["nomRep"]))  $nomRep = $_REQUEST["nomRep"];
else $nomRep = false;

if (isset($_REQUEST["action"]))
{
	switch($_REQUEST["action"])
	{
		case 'Creer' : 
			if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
				if (!is_dir("./ressources/" . $_GET["nomRep"]))
				{
					// A compléter : Code de création d'un répertoire
					mkdir("./ressources/" . $_GET["nomRep"]); // commentaire
					mkdir("./ressources/" . $_GET["nomRep"]."/copyright" . $_GET["nomRep"]); // commentaire
				}
			break;

		case 'Supprimer' : 
			if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
				if (isset($_GET["fichier"]) && ($_GET["fichier"] != ""))
				{
					$nomRep = $_GET["nomRep"];
					$fichier = $_GET["fichier"];

					// A compléter : Supprime le fichier image
					unlink("ressources/" . $nomRep . "/copyright". $nomRep ."/" . $fichier);
					unlink("ressources/" . $nomRep . "/copyright". $nomRep ."/thumbs/" . $fichier);
					unlink("ressources/" . $nomRep . "/" . $fichier);
					// A compléter : Supprime aussi la miniature si elle existe					
					unlink("ressources/" . $nomRep . "/thumbs/" . $fichier);
				}
			break;

		case 'Renommer' : 
			if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
				if (isset($_GET["fichier"]) && ($_GET["fichier"] != ""))
					if (isset($_GET["nomFichier"]) && ($_GET["nomFichier"] != ""))
					{
						$nomRep = $_GET["nomRep"];
						$fichier = $_GET["fichier"];
						$nomFichier = $_GET["nomFichier"]; // nouveau nom 

						// A compléter : renomme le fichier et sa miniature si elle existe
						if (file_exists("./ressources/$nomRep/$fichier"))			
							rename("./ressources/$nomRep/$fichier","./ressources/$nomRep/$nomFichier");

						if (file_exists("./ressources/$nomRep/thumbs/$fichier"))
							rename("./ressources/$nomRep/thumbs/$fichier","./ressources/$nomRep/thumbs/$nomFichier");


					}
			break;

		case 'Uploader' : 
			if (!empty($_FILES["FileToUpload"]))
			{

				if (is_uploaded_file($_FILES["FileToUpload"]["tmp_name"]))
				{
					//print("Quelques informations sur le fichier récupéré :<br>");
					//print("Nom : ".$_FILES["FileToUpload"]["name"]."<br>");
					//print("Type : ".$_FILES["FileToUpload"]["type"]."<br>");
					//print("Taille : ".$_FILES["FileToUpload"]["size"]."<br>");
					//print("Tempname : ".$_FILES["FileToUpload"]["tmp_name"]."<br>");
					$name = $_FILES["FileToUpload"]["name"];
					copy($_FILES["FileToUpload"]["tmp_name"],"./ressources/$nomRep/$name");
					logo_copyright("./ressources/$nomRep/$name","./ressources/$nomRep/copyright$nomRep/$name");

					// créer le répertoire miniature s'il n'existe pas
					if (!is_dir("./ressources/$nomRep/thumbs")) 
					{
						mkdir("./ressources/$nomRep/thumbs");
					}

					if (!is_dir("./ressources/$nomRep/copyright$nomRep/thumbs")) 
					{
						mkdir("./ressources/$nomRep/copyright$nomRep/thumbs");
					}

					$dataImg = getimagesize("./ressources/$nomRep/$name");  
					$type= substr($dataImg["mime"],6);// on enleve "image/" 

					// créer la miniature dans ce répertoire 
					miniature($type,"./ressources/$nomRep/$name",200,"./ressources/$nomRep/thumbs/$name");

					$dataImg = getimagesize("./ressources/$nomRep/copyright$nomRep/$name");  
					$type= substr($dataImg["mime"],6);// on enleve "image/" 

					// créer la miniature dans ce répertoire 
					miniature($type,"./ressources/$nomRep/copyright$nomRep/$name",200,"./ressources/$nomRep/copyright$nomRep/thumbs/$name");
				}
				else
				{
					echo "pb";
				}
			}

			break;

		case 'Supprimer Repertoire':
			// On ne peut supprimer que des répertoires vide !
			if (isset($_GET["nomRep"]) && ($_GET["nomRep"] != ""))
			{
				// A compléter : Supprime le répertoire des miniatures s'il existe, puis le répertoire principal

				if (is_dir("./ressources/$nomRep/thumbs"))
				{
					$rep = opendir("./ressources/$nomRep/thumbs"); 		// ouverture du repertoire 
					while ( $fichier = readdir($rep))	// parcours de tout le contenu de ce répertoire
					{

						if (($fichier!=".") && ($fichier!=".."))
						{
							// Pour éliminer les autres répertoires du menu déroulant, 
							// on dispose de la fonction 'is_dir'
							if (!is_dir("./ressources/$nomRep/thumbs/" . $fichier))
							{
								unlink("./ressources/$nomRep/thumbs/" . $fichier);
							}
						}
					}
					rmdir("./ressources/$nomRep/thumbs");
				}

				// répertoire principal
				$rep = opendir("./ressources/$nomRep"); 		// ouverture du repertoire 
				while ( $fichier = readdir($rep))	// parcours de tout le contenu de ce répertoire
				{

					if (($fichier!=".") && ($fichier!=".."))
					{
						// Pour éliminer les autres répertoires du menu déroulant, 
						// on dispose de la fonction 'is_dir'
						if (!is_dir("./ressources/$nomRep/" . $fichier))
						{
							unlink("./ressources/$nomRep/" . $fichier);
						}
					}
				}

				rmdir("./ressources/$nomRep");
				$nomRep = false;
			}
			break;

		case 'Download':

		if (isset($_GET["nom"]) && ($_GET["nom"] != ""))
			{
				if (isset($_GET["numImage"]) && ($_GET["numImage"] != ""))
				{
					$zipPath = "./".$nom.".zip";
					$zip = new ZipArchive(); 
			      	if($zip->open($nom.'.zip') == true)
				      	if($zip->open($nom.'.zip', ZipArchive::CREATE) == true)
				      	{
				        	echo '&quot;Zip.zip&quot; ouvert<br/>';
							for ($i=1 ; $i<= $numImage ; $i++)
							{
								if (isset($_GET["fic".$i]) && ($_GET["fic".$i] != ""))
								$zip->addFile('fic'.$i);
							}
			
							$zip->close();
				      	}

						
				//envoyer l'entete d'un fichier archive standard
				header("Content-type: application/zip");
				header("Content-Disposition: attachment; filename=$zipPath");
				header("Pragma: no-cache");
				header("Expires: 0");
				
				//envoyer l'archive
				ob_end_clean();
				readfile("$zipPath");
				//exit();
				}
			}
			break;
			
			


	}
}

function miniature($type,$nom,$dw,$nomMin)
{
	// Crée une miniature de l'image $nom
	// de largeur $dw
	// et l'enregistre dans le fichier $nomMin 


	// lecture de l'image d'origine, enregistrement dans la zone mémoire $im
	switch($type)
	{
		case "jpeg" : $im =  imagecreatefromjpeg ($nom);break;
		case "png" : $im =  imagecreatefrompng ($nom);break;
		case "gif" : $im =  imagecreatefromgif ($nom);break;		
	}

	$sw = imagesx($im); // largeur de l'image d'origine
	$sh = imagesy($im); // hauteur de l'image d'origine
	$dh = $dw * $sh / $sw;

	$im2 = imagecreatetruecolor($dw, $dh);

	$dst_x= 0;
	$dst_y= 0;
	$src_x= 0; 
	$src_y= 0; 
	$dst_w= $dw ; 
	$dst_h= $dh ; 
	$src_w= $sw ; 
	$src_h= $sh ;

	imagecopyresized ($im2,$im,$dst_x , $dst_y  , $src_x  , $src_y  , $dst_w  , $dst_h  , $src_w  , $src_h);


	switch($type)
	{
		case "jpeg" : imagejpeg($im2,$nomMin);break;
		case "png" : imagepng($im2,$nomMin);break;
		case "gif" : imagegif($im2,$nomMin);break;		
	}

	imagedestroy($im);
	imagedestroy($im2);
}

function incrustation($typeCible, $nomCible, $typeSource, $nomSource, $dw, $dx, $dy, $nomMin)
{
	// Insère l'image source au point (dx,dy) dans l'image cible
	// L'image insérée sera redimensionnée pour avoir une largeur de $dw

	// lecture de l'image cible, enregistrement dans la zone mémoire $im
	switch($typeCible)
	{
		case "jpeg" : $imCible =  imagecreatefromjpeg ($nomCible);break;
		case "png" : $imCible =  imagecreatefrompng ($nomCible);break;
		case "gif" : $imCible =  imagecreatefromgif ($nomCible);break;		
	}

	// lecture de l'image source, enregistrement dans la zone mémoire $im
	switch($typeSource)
	{
		case "jpeg" : $imSource =  imagecreatefromjpeg ($nomSource);break;
		case "png" : $imSource =  imagecreatefrompng ($nomSource);break;
		case "gif" : $imSource =  imagecreatefromgif ($nomSource);break;		
	}

	// On connait la dimension en largeur de l'image à incruster
	// dw = destination width

	$sw = imagesx($imSource); // largeur de l'image d'origine
	$sh = imagesy($imSource); // hauteur de l'image d'origine
	// TODO : calculer $dh	
	$dh = 0;

	$src_x= 0; 		// image à incruster
	$src_y= 0; 
	$src_w= 0; 
	$src_h= 0;

	$dst_x= 0;
	$dst_y= 0;
	$dst_w= 0; 
	$dst_h= 0; 

	imagecopyresized ($imCible, $imSource, $dst_x , $dst_y  , $src_x  , $src_y  , $dst_w  , $dst_h  , $src_w  , $src_h);


	switch($typeCible)
	{
		case "jpeg" : imagejpeg($imCible,$nomMin);imagejpeg($imCible);break;
		case "png" : imagepng($imCible,$nomMin);imagepng($imCible);break;
		case "gif" : imagegif($imCible,$nomMin);imagegif($imCible);break;		
	}

	imagedestroy($imCible);
	imagedestroy($imSource);
}

$rep = opendir("./ressources/"); // ouverture du repertoire 
while ( $fichier = readdir($rep))
{
	// On élimine le résultat '.' (répertoire courant) 
	// et '..' (répertoire parent)

	if (($fichier!=".") && ($fichier!=".."))
	{
		// Pour éliminer les autres fichiers du menu déroulant, 
		// on dispose de la fonction 'is_dir'
		if (is_dir("./ressources/" . $fichier))
			printf("<option value=\"$fichier\">$fichier</option>");
	}
}
closedir($rep);
?>

</select>
<input type="submit" class="btn btn-default" value="Explorer"> <input type="submit" class="btn btn-default" name="action" value="Supprimer Repertoire">
</form>

<?php
if (!$nomRep)  die("Choisissez un r&eacutepertoire"); 
// interrompt immédiatement l'exécution du code php
?>

<hr />
<h2> Contenu du r&eacutepertoire '<?php echo$_GET["nomRep"]?>' </h2>


<form enctype="multipart/form-data" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="10000000">
<input type="hidden" name="nomRep" value="<?php echo $nomRep; ?>">
<label>Ajouter un fichier image : </label>
<input type="file" name="FileToUpload">
<input type="submit"  class="btn btn-default" value="Uploader" name="action">
</form>

<?php 
if (isset($_SESSION["connecte"]) && ($_SESSION["connecte"]))
{
	echo "<form>";
}
$numImage = 0;
$rep = opendir("./ressources/$nomRep"); 		// ouverture du repertoire 
while ( $fichier = readdir($rep))	// parcours de tout le contenu de ce répertoire
{
	if (($fichier!=".") && ($fichier!=".."))
	{
		// Pour éliminer les autres répertoires du menu déroulant, 
		// on dispose de la fonction 'is_dir'
		if (!is_dir("./ressources/$nomRep/" . $fichier))
		{
			// Un fichier... est-ce une image ?
			// On ne liste que les images ... 
			// Ajouter la prise en charge des fichiers bmp
			$formats = ".jpeg.jpg.gif.png";
			if (strstr($formats,strrchr($fichier,"."))) 
			{
				$numImage++;
				$dataImg = getimagesize("./ressources/$nomRep/$fichier"); 
				// A compléter : récupérer le type d'une image, et sa taille 
				$width= $dataImg[0];
				$height= $dataImg[1];
				$type= substr($dataImg["mime"],6);

				// A compléter : On cherche si une miniature existe pour l'afficher...
				// Si non, on crée éventuellement le répertoire des miniatures, 
				// et la miniature que l'on place dans ce sous-répertoire				
				echo "<div class=\"mini\">\n";

				if (isset($_SESSION["connecte"]) && ($_SESSION["connecte"]))
				{
					$test = verifvip($_SESSION["pseudo"]);
					$vip = $test->fetch();
					if ($vip[0] == "1")
					{
						echo "<a target=\"_blank\" href=\"ressources/$nomRep/$fichier\"><img src=\"ressources/$nomRep/thumbs/$fichier\"/></a>\n";
						$chemin_fic="ressources/$nomRep/$fichier";
					}
					else
					{
						echo "<a target=\"_blank\" href=\"ressources/$nomRep/copyright$nomRep/$fichier\"><img src=\"ressources/$nomRep/copyright$nomRep/thumbs/$fichier\"/></a>\n";
						$chemin_fic="ressources/$nomRep/copyright$nomRep/$fichier";
					}
				}
				else
					echo "<a><img src=\"ressources/$nomRep/copyright$nomRep/thumbs/$fichier\"/></a>\n";

				echo "<div>$fichier \n";			
				echo "<a href=\"?nomRep=$nomRep&fichier=$fichier&action=Supprimer\" >Supp</a>\n";
				echo "<br />($width * $height $type)\n";
				echo "<br />\n";

				echo "<form>\n";
				echo "<input type=\"hidden\" name=\"fichier\" value=\"$fichier\" />\n";
				echo "<input type=\"hidden\" name=\"nomRep\" value=\"$nomRep\" />\n";
				echo "<input type=\"hidden\" name=\"action\" value=\"Renommer\" />\n";
				echo "<input type=\"text\" class=\"renommer\" name=\"nomFichier\" value=\"$fichier\" onclick=\"this.select();\" />\n";
				echo "<input type=\"submit\" class=\"btn_renommer\" value=\">\" />\n";
				echo "</form>\n";
				if (isset($_SESSION["connecte"]) && ($_SESSION["connecte"]))
				{
					echo "<br><input id='check' type='checkbox' name='fic$numImage' value='$chemin_fic'>";
					echo "<input type='hidden' name='nom' value='$nomRep'>";
					echo "<input type='hidden' name='numImage' value='$numImage'>";
				}
				echo "</div></div>\n";

				// A compléter : appeler echo "<br style=\"clear:left;\" />"; si on a affiché 5 images sur la ligne actuelle

				if (($numImage%5) == 0)
					echo "<br style=\"clear:left;\" />";
			}
		}
	}

}
closedir($rep);
if (isset($_SESSION["connecte"]) && ($_SESSION["connecte"]))
{
	echo "<input type='submit'  class='btn btn-default' value='Download' name='action'></form>";
}

// A compléter : afficher un message lorsque le répertoire est vide
if ($numImage==0) echo "<h3>Aucune image dans le r&eacutepertoire</h3>";

/************************************************************/
/**************** 	Fonction copyright 	*********************/
/************************************************************/


function logo_copyright($image,$nomCopy)
{

	/*	Mettre un logo de dimension : 20% de la hauteur de l'image et avec une marge de 3% du bord
	 *	Mettre un texte horizontal en rouge en bas de la photo : à une hauteur de 10% du bas de l'image
	 */	

	/** CHARGEMENT DE L'IMAGE **/
	list($width, $height) = getimagesize($image);						// Obtenir les dimensions du fichier // width : largeur / height : hauteur
	/** Vérifie si l'image est un .jpeg ou un .png ou un .gif **/
	if(exif_imagetype($image) == IMAGETYPE_JPEG)
		$im = imagecreatefromjpeg($image);								// Transformation du fichier en image
	if(exif_imagetype($image) == IMAGETYPE_PNG)
		$im = imagecreatefrompng($image);
	if(exif_imagetype($image) == IMAGETYPE_GIF)
		$im = imagecreatefromgif($image);
	$couleur = imagecolorallocatealpha($im, 255,0,0,80); 				// Alloue une couleur pour le texte : 0 0 0 = noir | 255 0 0 = rouge
	imagecolortransparent($im, $couleur); 								// Définir la couleur transparente

	/** CHARGEMENT DU LOGO COPYRIGHT **/
	$logo = "utiles/images/logo.jpg";								// Chemin du fichier
	/** Vérifie si l'image est un .jpeg ou un .png ou un .gif **/
	if(exif_imagetype($logo) == IMAGETYPE_JPEG)
		$imlogo = imagecreatefromjpeg($logo);
	if(exif_imagetype($logo) == IMAGETYPE_PNG)
		$imlogo = imagecreatefrompng($logo);
	if(exif_imagetype($logo) == IMAGETYPE_GIF)
		$imlogo = imagecreatefromgif($logo);
	list($logowidth, $logoheight, $logotype) = getimagesize($logo);		// Récupére les dimensions du logo

	/** CALCUL DE LA NOUVELLE DIMENSION DU LOGO **/
	$percent = 20;														//Ajustement à 20% de la hauteur de l'image
	$newheight = $height * $percent / 100;
	$delta = $logoheight / $newheight;
	$newwidth = $logowidth / $delta;

	/**	SUPERPOSE L'IMAGE & LE LOGO **/
	imagecopyresized($im, $imlogo, $width-$newwidth-$width*0.03, $height*0.03, 0, 0, $newwidth, $newheight, $logowidth, $logoheight);

	/** DEFINIR LA POLICE DU TEXTE **/
	putenv('GDFONTPATH=' . realpath('./ressources/polices'));
	$font = "./utiles/polices/chumbly.ttf";

	/** SUPERPOSE L'IMAGE ET LE TEXTE **/
	imagettftext($im, $width/10, 0, $width * 0.25, $height * 0.9, $couleur, $font, "copyright");	// affiche le texte sur l'image
	//$image : l'image sur laquelle inserer le texte
	//$size : Taille des caractères
	//$angle : Angle d'inclinaison du texte en degré
	//$x : Coordonée du premier caractère
	//$y : Coordonée de la ligne de base du texte
	//$color : Couleur du texte
	//$fontfile : Police des caractères
	//$text : Texte a inserer

	/** SAUVEGARDE DE L'IMAGE **/
	if(exif_imagetype($image) == IMAGETYPE_JPEG)
		imagejpeg($im,$nomCopy);					// Enregistre l'image sous le nom : $nomCopy
	if(exif_imagetype($image) == IMAGETYPE_PNG)
		imagepng($im,$nomCopy);
	if(exif_imagetype($image) == IMAGETYPE_GIF)
		imagegif($im,$nomCopy);
	imagedestroy($im);								// Libère toute la mémoire associé à l'image

}	//fin logo_copyright()
?>

</body>
