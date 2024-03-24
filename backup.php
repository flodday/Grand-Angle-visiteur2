<?php

require_once "config/pdo.php";


$titre = "descriptionOeuvre";
$nav = "descriptionOeuvre";
include_once "includes/pages/nav.php";

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT *
  FROM oeuvres
  JOIN image ON oeuvres.Id_Oeuvres = image.Id_Oeuvres
  JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
  JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
  WHERE  oeuvres.Id_Oeuvres = $id";
  $request = $db->query($sql);
  $arts = $request->fetchAll(PDO::FETCH_ASSOC);
}

$sqlSelectLangue = "SELECT *
FROM langue";
$requestLangue = $db->query($sqlSelectLangue);
$langues = $requestLangue->fetchAll(PDO::FETCH_ASSOC);



if (isset($_POST['submit'])) {
  $langue = $_POST['langue'];
} else {
  $langue = "Français";
}

$sqlLangue = "SELECT * 
FROM langue
WHERE libelle_Langue = :libelle_Langue";
$queryLangue = $db->prepare($sqlLangue);
$queryLangue->bindParam(":libelle_Langue", $langue, PDO::PARAM_STR);
$queryLangue->execute();
$row = $queryLangue->fetch(PDO::FETCH_ASSOC);
$id_L = $row['Id_Langue'];


$sqlDescription = "SELECT *
  FROM contenu
  JOIN langue ON contenu.Id_Langue = langue.Id_Langue
  JOIN oeuvres ON contenu.Id_Oeuvres = oeuvres.Id_Oeuvres
  WHERE  langue.Id_Langue = $id_L And oeuvres.Id_Oeuvres = $id";
$request1 = $db->query($sqlDescription);
$descriptions = $request1->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="language">
  <form method="POST" class="formlanguage">
    <select name="langue" id="langue" class="langues">
      <?php foreach ($langues as $langue) : ?>
        <div class="item">
          <option value="<?php echo $langue['libelle_Langue']; ?>" class="">
            <?php echo $langue['libelle_Langue']; ?></option>
        </div>
      <?php endforeach; ?>
    </select>

    <input type="submit" name="submit" value="traduire">
  </form>
</div>



<section>



    <div id="content1" class="hidden">


    <div class="artwork">
      <i class="fa-solid fa-chart-bar"></i>
      <h3>Fiche de l'oeuvre</h3>
      <?php foreach ($arts as $art) : ?>
        <img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">

    </div>


    <div class="description">
      <i class="fa-solid fa-palette"></i>
      <h3>Description de l'oeuvre</h3>
      <p>
        <?php foreach ($descriptions as $description) {
          echo $description['description_Contenu'];
        } ?>

      </p>
    <?php endforeach ?>

    </div>




    </div>




    <div id="content2" class="hidden">


      <div class="artwork">
        <i class="fa-solid fa-chart-bar"></i>
        <h3>درباره اثر</h3>
        <?php foreach ($arts as $art) : ?>
          <img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">

      </div>


      <div class="description">
        <i class="fa-solid fa-palette"></i>
        <h3>Description de l'oeuvre</h3>
        <p>
          <?php foreach ($descriptions as $description) {
            echo $description['description_Contenu'];
          } ?>
        </p>
      <?php endforeach ?>

      </div>




    </div>





    <div class="additional-info">
      <i class="fa-solid fa-file-circle-plus"></i>
      <h3 class="title_additional_info">Informations supplémentaires</h3>
      <ul>

        <li><strong>Artiste : </strong><?= $art['Prenom_Artiste'] . " "; ?><?= $art['Nom_Artiste']; ?></li>
        <li><strong>Libellé de l'oeuvre : </strong><?= $art['libelle_Oeuvre']; ?></li>
        <li><strong>Prix : </strong><?= $art['prix']; ?>€</li>
        <li><strong>Dimensions : </strong><?= " " . $art['hauteur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['largeur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['profondeur_Oeuvre'] . " "; ?></li>
        <li><strong>Date de l'exposition : </strong><?= date('d-m-y', strtotime($art['Date_Debut'])) . " "; ?>-<?= " " . date('d-m-y', strtotime($art['Date_Fin'])); ?></li>

      </ul>
    </div>


    <div class="exhibition-video">
      <i class="fa-regular fa-circle-play"></i>
      <h3>Vidéo de l'exposition</h3>

      <iframe class="video" width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>
    </div>

    <div class="audio-file">
      <i class="fa-solid fa-file-audio"></i>
      <h3>Fichier audio</h3>
      <audio controls>
        <source src="audio.mp3" type="audio/mpeg">
      </audio>
    </div>

</section>


<script>
    document.getElementById('langue').addEventListener('change', function() {
        let selectedOption = this.value;
 
        // Hide all content divs
        let allContentDivs = document.querySelectorAll('.content');
        allContentDivs.forEach(function(div) {
            div.classList.add('hidden');
        });
 
        // Show content div corresponding to the selected option
        var selectedContentDiv = document.getElementById('content' + selectedOption.charAt(selectedOption.length - 1));
        selectedContentDiv.classList.remove('hidden');
    });
</script>

<?php


include_once "includes/pages/footer.php";

?>




/**/ 


<div class="expo-wrapper">

  <?php foreach ($Expos as $Expo) : ?>
    <div class="card-keeper-expo">
      <div class="card-expo">
        <div class="image-content-expo">
          <div class="card-image-expo">
          <img src="assets/images/exposition/<?= $Expo["chemin_Affiche"]; ?>" class="card-img-expo" alt="Images de l'exposition">
          </div>
        </div>
        <div class="card-content-expo">
        <h4 class="libelle-expo"><?= $Expo["libelle_Exposition"]; ?></h4>
        <a href="#"><button class="voir-plus-expo">Voir plus</button></a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

</div>

/**/

.expo-wrapper {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  margin-right: 10px;
  gap: 10px;

}

.card-keeper-expo{
  width: 100%;
  display:flex;
  padding: 5px 0;
  gap: 10px;
}
.card-expo{
  width: 600px;
  border-radius: 25px;
  flex-grow: 0;
  flex-shrink: 0;
  background-color: #000;
}
.image-content-expo, .card-content-expo {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding:10px 14px;
}
.image-content-expo{
  position: relative;
  row-gap: 5px;
  padding: 25px 0;
}
.card-image-expo{
  position: relative;
  height:250px;
  width :300px;
  border-radius: 5px;
  background-color: #F3f3f3;
}
.card-image-expo .card-img-expo{
  position: absolute;
  height: 100%;
  width: 100%;


  border: 4px solid var(--BGCOLORPR);
}
.libelle-expo{
  font-size: 1.2rem;
  font-weight: 500;
}
.voir-plus-expo{
  border:none;
  background-color: var(--BGCOLORPU);
  color: var(--FONTCOLOR);
  border-radius: 20px;
  padding: 5px 10px;
  margin:5px 0 ;
  cursor: pointer;
  width: 90px;
  font-weight: 500;
  font-family: var(--FONTTITLE);
  transition: all 0.3s ease;
  }
   
  .voir-plus-expo:hover{
    background-color: var(--ACCENT1);
    color: var(--FONTCOLOR2);
  }


  /**/
   

   <?php
if (isset($_GET['id'])) :
    $id2 = $_GET['id'];
    $currentDateTime = date("Y-m-d H:i:s"); // Current date and time
    $sql2 = "SELECT *
    FROM oeuvres
    JOIN image ON oeuvres.Id_Oeuvres = image.Id_Oeuvres
    JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
    JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
    WHERE  oeuvres.Id_Exposition = $id2 AND (exposition.Date_Fin <= '$currentDateTime' OR 'exposition.Date_Debut' >= '$currentDateTime')";
    $request2 = $db->query($sql2);
    $cardOeuvres = $request2->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="bottom" id="oeuvreExpo">
        <div class="wrapper">
            <i id="left" class="fa-solid fa-angle-left"></i>
            <ul class="carousel">
                <?php foreach ($cardOeuvres as $cardOeuvre) : ?>
                    <li class="card">
                        <div class="img"><img src="assets/images/artwork/<?= $cardOeuvre['chemin_Image']; ?>" alt="<?= $cardOeuvre['chemin_Image']; ?>"></div>
                        <div class="btn-voir-plus">
                            <button class="btn-afficher">
                                <a href="descriptionOeuvre.php?id=<?= $cardOeuvre["Id_Oeuvres"]; ?>">Afficher plus</a>
                            </button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <i id="right" class="fa-solid fa-angle-right"></i>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_GET['id'])) :
    $id3 = $_GET['id'];
    $currentDateTime = date("Y-m-d H:i:s"); // Date et heure actuelles
    $sql3 = "SELECT *
    FROM oeuvres
    JOIN image ON oeuvres.Id_Oeuvres = image.Id_Oeuvres
    JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
    JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
    WHERE  oeuvres.Id_Exposition = $id3 AND exposition.Date_Debut < '$currentDateTime'";
    $request3 = $db->query($sql3);
    $infos = $request3->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h3><?php echo "Désolé, l'exposition n'a pas encore commencée."; ?></h3>
<?php endif; ?>


/**/
 */

 <div class="image-content-expo">
          <div class="card-image-expo">
            <img src="assets/images/artiste/<?= $Art['chemin_Imgart']; ?>" class="card-img-expo artiste-image" alt="Images de l'artiste">
          </div>


          /*back up */ */
          <?php

require_once "config/pdo.php";


$titre = "listeCollection";
$nav = "listeCollection";
include_once "includes/pages/nav.php";


$sqlCollec = "SELECT *
FROM oeuvres
JOIN type_oeuvre ON oeuvres.Id_Type = type_oeuvre.Id_Type
JOIN image ON image.Id_Oeuvres = oeuvres.Id_Oeuvres 
GROUP BY type_oeuvre.Id_Type";
$requeteCollec = $db->query($sqlCollec);
$Collecs = $requeteCollec->fetchAll(PDO::FETCH_ASSOC);

$sqlCollec1 = "SELECT *
FROM type_oeuvre";
$requeteCollec1= $db->query($sqlCollec1);
$Collecs1 = $requeteCollec1->fetchAll(PDO::FETCH_ASSOC);


?>


<div class="container-infos-expo">
  <div class="expo-infos">
    <div class="expo-header">
      <h2 class="">Collections Disponibles</h2>
      <p class="date-time"><?php include_once "date.php" ?></p>
    </div>
    <div class="expo-lineinfos">
      <div class="expo-status">
        <div class="item-status">
          <span class="status-number"><?php echo count($Collecs);?></span>
          <span class="type-status">Nombre total oeuvres existantes</span>
        </div>  
        <div class="item-status">
          <span class="status-number"><?php echo count($Collecs1);?></span>
          <span class="type-status">Type(s) de collections</span>
        
        </div>
      </div>
    </div>
  </div>
</div>


<div class="expo-wrapper">

  <?php foreach ($Collecs as $Collec) : ?>
    <h2><?php $Collec['libelle_Type']; ?></h2>
    <div class="card-keeper-expo">
      <div class="card-expo" id="card-artiste">
      <div class="card-row2">
            <div class="image-artiste-ongoing" id="art-img">
                <img src="assets/images/artwork/<?php echo $Collec['chemin_Image']; ?>" alt="image collection">
            </div>

    </div>
        <div class="card-content-expo">
          <h4 class="libelle-expo"><?= $Collec["libelle_Oeuvre"]; ?></h4>

          <div class="keep-expo-btn">
          <button class="voir-plus-expo">
            <a href="descriptionOeuvre.php?id=<?= $Collec["Id_Oeuvres"];?>">Voir plus</a>
          </button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

</div>

<script src="./assets/javascript/script2.js"></script>



<?php
include_once "includes/pages/footer.php";
?>


/*description oeuvre  */

<?php

require_once "config/pdo.php";


$titre = "descriptionOeuvre";
$nav = "descriptionOeuvre";
include_once "includes/pages/nav.php";

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT *
  FROM oeuvres
  JOIN image ON oeuvres.Id_Oeuvres = image.Id_Oeuvres
  JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
  JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
  WHERE  oeuvres.Id_Oeuvres = $id";
  $request = $db->query($sql);
  $arts = $request->fetchAll(PDO::FETCH_ASSOC);
}

/* $sqlSelectLangue = "SELECT *
FROM langue
WHERE ";
$requestLangue = $db->query($sqlSelectLangue);
$langues = $requestLangue->fetchAll(PDO::FETCH_ASSOC);
 */

/* 
$sqlLangue = "SELECT * 
FROM langue
WHERE libelle_Langue = :libelle_Langue";
$queryLangue = $db->prepare($sqlLangue);
$queryLangue->bindParam(":libelle_Langue", $langue, PDO::PARAM_STR);
$queryLangue->execute();
$row = $queryLangue->fetch(PDO::FETCH_ASSOC);
$id_L = $row['Id_Langue'];
 */

/* $sqlDescription = "SELECT *
  FROM contenu
  JOIN langue ON contenu.Id_Langue = langue.Id_Langue
  JOIN oeuvres ON contenu.Id_Oeuvres = oeuvres.Id_Oeuvres
  WHERE  langue.Id_Langue = $id_L AND oeuvres.Id_Oeuvres = $id";
$request1 = $db->query($sqlDescription);
$descriptions = $request1->fetchAll(PDO::FETCH_ASSOC);
 */

 if(isset($_SESSION['lang']) && in_array($_SESSION['lang'], $lang_array)) {
  $lang_code = $_SESSION['lang'];
 
  // Assuming $id_L and $id are the variables holding the respective IDs
  // Use prepared statements to prevent SQL injection
  $sqlDescription = "SELECT *
      FROM contenu
      JOIN langue ON contenu.Id_Langue = langue.Id_Langue
      JOIN oeuvres ON contenu.Id_Oeuvres = oeuvres.Id_Oeuvres
      WHERE langue.value_Langue = :value_Langue AND oeuvres.Id_Oeuvres = :id";
 
  // Prepare the statement
  $stmt = $db->prepare($sqlDescription);
 
  // Bind parameters
  $stmt->bindParam(':value_Langue', $lang_code, PDO::PARAM_STR);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
 
  // Execute the query
  $stmt->execute();
 
  // Fetch the results
  $descriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  // Handle the case where $_SESSION['lang'] is not set or not valid
  // You might want to set a default language or display an error message
}


?>
<!-- 
<div class="language">
  <form method="POST" class="formlanguage">
    <select name="langue" id="langue" class="langues">
      <?php foreach ($langues as $langue) : ?>
        <div class="item">
          <option value="<?php echo $langue['libelle_Langue']; ?>" class="">
            <?php echo $langue['libelle_Langue']; ?></option>
        </div>
      <?php endforeach; ?>
    </select>

    <input type="submit" name="submit" value="traduire">
  </form>
</div> -->







    <div class="artwork">
      <i class="fa-solid fa-chart-bar"></i>
     <div><?php echo DESC_H3;  ?></div>
      <?php foreach ($arts as $art) : ?>
        <img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">

    </div>


    <div class="description">
      <i class="fa-solid fa-palette"></i>
      <div><?php echo DESC1_H3;  ?></div>
      <p>
        <?php foreach ($descriptions as $description) {
          echo $description['description_Contenu'];
        } ?>

      </p>
    <?php endforeach ?>

    </div>






    <div class="additional-info">
      <i class="fa-solid fa-file-circle-plus"></i>
      <h3 class="title_additional_info">Informations supplémentaires</h3>
      <ul>

        <li><strong>Artiste : </strong><?= $art['Prenom_Artiste'] . " "; ?><?= $art['Nom_Artiste']; ?></li>
        <li><strong>Libellé de l'oeuvre : </strong><?= $art['libelle_Oeuvre']; ?></li>
        <li><strong>Prix : </strong><?= $art['prix']; ?>€</li>
        <li><strong>Dimensions : </strong><?= " " . $art['hauteur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['largeur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['profondeur_Oeuvre'] . " "; ?></li>
        <li><strong>Date de l'exposition : </strong><?= date('d-m-y', strtotime($art['Date_Debut'])) . " "; ?>-<?= " " . date('d-m-y', strtotime($art['Date_Fin'])); ?></li>

      </ul>
    </div>


    <div class="exhibition-video">
      <i class="fa-regular fa-circle-play"></i>
      <h3>Vidéo de l'exposition</h3>

      <iframe class="video" width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>
    </div>

    <div class="audio-file">
      <i class="fa-solid fa-file-audio"></i>
      <h3>Fichier audio</h3>
      <audio controls>
        <source src="audio.mp3" type="audio/mpeg">
      </audio>
    </div>




<?php


include_once "includes/pages/footer.php";

?>

/*description oeuvre v2 */
<?php
require_once "config/pdo.php";
 
$titre = "descriptionOeuvre";
$nav = "descriptionOeuvre";
//include_once "includes/pages/nav.php";
 
//$arts = []; // Initialize $arts as an empty array
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    echo $id;
    $sql = "SELECT *
            FROM oeuvres
            JOIN image ON oeuvres.Id_Oeuvres = image.Id_Oeuvres
            JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
            JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
            WHERE oeuvres.Id_Oeuvres = :id"; // Use a parameterized query
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $arts = $stmt->fetchAll(PDO::FETCH_ASSOC);

var_dump($arts);

/* if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $lang_array)) {
    $lang_code = $_SESSION['lang'];
 
    $sqlDescription = "SELECT *
                        FROM contenu
                        JOIN langue ON contenu.Id_Langue = langue.Id_Langue
                        JOIN oeuvres ON contenu.Id_Oeuvres = oeuvres.Id_Oeuvres
                        WHERE langue.value_Langue = :value_Langue AND oeuvres.Id_Oeuvres = :id";
 
    $stmt1 = $db->prepare($sqlDescription);
 
    $stmt1->bindParam(':value_Langue', $lang_code, PDO::PARAM_STR);
    $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
 
    $stmt1->execute();
 
    $descriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Handle the case where $_SESSION['lang'] is not set or not valid
} */
}
?>
 
<div class="artwork">
<i class="fa-solid fa-chart-bar"></i>
<!-- <div><?php echo DESC_H3; ?></div> -->
<?php if (!empty($arts)) : ?>
<?php foreach ($arts as $art) : ?>
<img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">
<?php endforeach ?>
<?php endif ?>
</div>
 
<div class="description">
<i class="fa-solid fa-palette"></i>
<!-- <div><?php echo DESC1_H3; ?></div>
 --><p>
<?php if (!empty($descriptions)) {
            foreach ($descriptions as $description) {
                echo $description['description_Contenu'];
            }
        } ?>
</p>
</div>
 
<div class="additional-info">
<i class="fa-solid fa-file-circle-plus"></i>
<h3 class="title_additional_info">Informations supplémentaires</h3>
<ul>
<?php if (!empty($arts)) : foreach($arts as $art){ ?>
<li><strong>Artiste : </strong><?= $art['Prenom_Artiste'] . " "; ?><?= $art['Nom_Artiste']; ?></li>
<li><strong>Libellé de l'oeuvre : </strong><?= $art['libelle_Oeuvre']; ?></li>
<li><strong>Prix : </strong><?= $art['prix']; ?>€</li>
<li><strong>Dimensions : </strong><?= " " . $art['hauteur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['largeur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['profondeur_Oeuvre'] . " "; ?></li>
<li><strong>Date de l'exposition : </strong><?= date('d-m-y', strtotime($art['Date_Debut'])) . " "; ?>-<?= " " . date('d-m-y', strtotime($art['Date_Fin'])); ?></li>
<?php } ?>
<?php endif; ?>
</ul>
</div>
 
<div class="exhibition-video">
<i class="fa-regular fa-circle-play"></i>
<h3>Vidéo de l'exposition</h3>
 
    <iframe class="video" width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>
</div>
 
<div class="audio-file">
<i class="fa-solid fa-file-audio"></i>
<h3>Fichier audio</h3>
<audio controls>
<source src="audio.mp3" type="audio/mpeg">
</audio>
</div>
 
<?php
include_once "includes/pages/footer.php";
?>

/*descriptionoeuvre v3 */
<?php
 
require_once "config/pdo.php";
$titre = "descriptionOeuvre";
$nav = "descriptionOeuvre";
include_once "includes/pages/nav.php";
 
// Check if both id and lang parameters are present
if (isset($_GET['id']) && isset($_GET['lang'])) {
    $id = $_GET['id'];
    $lang = $_GET['lang'];
 
    // Fetch object details based on ID
    $sql = "SELECT *
            FROM oeuvres
            JOIN image ON oeuvres.Id_Oeuvres = image.Id_Oeuvres
            JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
            JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
            WHERE oeuvres.Id_Oeuvres = $id";
    $request = $db->query($sql);
/*     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute(); */
    $arts = $request->fetchAll(PDO::FETCH_ASSOC);
    var_dump($arts);

    // Fetch content in the selected language
    $sqlDescription = "SELECT *
                       FROM contenu
                       JOIN langue ON contenu.Id_Langue = langue.Id_Langue
                       JOIN oeuvres ON contenu.Id_Oeuvres = oeuvres.Id_Oeuvres
                       WHERE langue.libelle_Langue = :lang AND oeuvres.Id_Oeuvres = :id";
    $stmt1 = $db->prepare($sqlDescription);
    $stmt1->bindParam(':lang', $lang, PDO::PARAM_STR);
    $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt1->execute();
    $descriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif (isset($_GET['lang'])) {
    // Only language parameter is present, fetch content in the selected language
    $lang = $_GET['lang'];
 
    // Fetch content in the selected language without object details
    $sqlDescription = "SELECT *
                       FROM contenu
                       JOIN langue ON contenu.Id_Langue = langue.Id_Langue
                       JOIN oeuvres ON contenu.Id_Oeuvres = oeuvres.Id_Oeuvres
                       WHERE langue.libelle_Langue = :lang";
    $stmt = $db->prepare($sqlDescription);
    $stmt->bindParam(':lang', $lang, PDO::PARAM_STR);
    $stmt->execute();
    $descriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Handle the case where neither id nor lang parameter is present
    // You can redirect the user to a default page or display an error message
    echo "Error: Missing parameters";
}
 
// Rest of your code to display the page content based on fetched data
 
?>
 
<div class="artwork">
<i class="fa-solid fa-chart-bar"></i>
<!-- <div><?php echo DESC_H3; ?></div> -->
<?php if (!empty($arts)) : ?>
<?php foreach ($arts as $art) : ?>
<img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">
<?php endforeach ?>
<?php endif ?>
</div>
 
<div class="description">
<i class="fa-solid fa-palette"></i>
<!-- <div><?php echo DESC1_H3; ?></div>
 --><p>
<?php if (!empty($descriptions)) {
            foreach ($descriptions as $description) {
                echo $description['description_Contenu'];
            }
        } ?>
</p>
</div>
 
<div class="additional-info">
<i class="fa-solid fa-file-circle-plus"></i>
<!-- <div><?php echo ADDINFOS_H3; ?></div>
<ul>
<?php if (!empty($arts)) : foreach($arts as $art){ ?>
<li><strong>Artiste : </strong><?= $art['Prenom_Artiste'] . " "; ?><?= $art['Nom_Artiste']; ?></li>
<li><strong>Libellé de l'oeuvre : </strong><?= $art['libelle_Oeuvre']; ?></li>
<li><strong>Prix : </strong><?= $art['prix']; ?>€</li>
<li><strong>Dimensions : </strong><?= " " . $art['hauteur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['largeur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['profondeur_Oeuvre'] . " "; ?></li>
<li><strong>Date de l'exposition : </strong><?= date('d-m-y', strtotime($art['Date_Debut'])) . " "; ?>-<?= " " . date('d-m-y', strtotime($art['Date_Fin'])); ?></li>
<?php } ?>
<?php endif; ?>
</ul>
</div>
 
<div class="exhibition-video">
<i class="fa-regular fa-circle-play"></i>
<h3>Vidéo de l'exposition</h3>
 
    <iframe class="video" width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>
</div>
 
<div class="audio-file">
<i class="fa-solid fa-file-audio"></i>
<h3>Fichier audio</h3>
<audio controls>
<source src="audio.mp3" type="audio/mpeg">
</audio>
</div>
 
<?php
include_once "includes/pages/footer.php";
?>




<?php
require_once "config/pdo.php";

$titre = "listeCollection";
$nav = "listeCollection";
include_once "includes/pages/nav.php";

$sqlCollec = "SELECT *
              FROM oeuvres
              JOIN type_oeuvre ON oeuvres.Id_Type = type_oeuvre.Id_Type
              JOIN image ON image.Id_Oeuvres = oeuvres.Id_Oeuvres";
$requeteCollec = $db->query($sqlCollec);
$Collecs = $requeteCollec->fetchAll(PDO::FETCH_ASSOC);

?>





$sqlSelectLangue = "SELECT *
FROM langue";
$requestLangue = $db->query($sqlSelectLangue);
$langues = $requestLangue->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT DISTINCT *
    FROM artiste
    JOIN bio_artist ON artiste.Id_Artiste = bio_artist.Id_Artiste
    WHERE  artiste.Id_Artiste = $id
    GROUP BY artiste.Id_Artiste";
    $request = $db->query($sql);
    $artistes = $request->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Cet artiste n'existe pas.";
}

if (isset($_GET['id'])) {
    $id2 = $_GET['id'];
        if (isset($_POST['submit'])) {
        $langue = $_POST['langue'];
      } else {
        $langue = "Français";
      }
      $sqlLangue = "SELECT * 
      FROM langue
      WHERE libelle_Langue = :libelle_Langue";
      $queryLangue = $db->prepare($sqlLangue);
      $queryLangue->bindParam(":libelle_Langue", $langue, PDO::PARAM_STR);
      $queryLangue->execute();
      $row = $queryLangue->fetch(PDO::FETCH_ASSOC);
      $id_L2 = $row['Id_Langue'];

    $sqlBio = "SELECT *
    FROM bio_artist
    JOIN langue ON bio_artist.Id_Langue = langue.Id_Langue
    JOIN artiste ON bio_artist.Id_Artiste = artiste.Id_Artiste
    WHERE  bio_artist.Id_Langue = $id_L2 AND bio_artist.Id_Artiste = $id2";
    $requestbio = $db->query($sqlBio);
    $Bios = $requestbio->fetchAll(PDO::FETCH_ASSOC);
}






if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT *
    FROM exposition
    WHERE  exposition.Id_Exposition = $id";
    $request = $db->query($sql);
    $expos = $request->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Il n'y a plus d'exposition";
}



if (isset($_GET['id'])) {
    $id1 = $_GET['id'];
    $sql1 = "SELECT *
    FROM artiste 
    JOIN exposition ON artiste.Id_Artiste= exposition.Id_Artiste
    JOIN bio_artist ON artiste.Id_Artiste= bio_artist.Id_Artiste
    WHERE  exposition.Id_Exposition = $id1 AND bio_artist.Id_Langue=1";
    $request1 = $db->query($sql1);
    $cardExpos = $request1->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Il n'y a plus d'exposition";
}

/**/FAAAAAALLLLLLLCCCCCCC */
<?php
 
require_once "config/pdo.php";
$titre = "descriptionOeuvre";
$nav = "descriptionOeuvre";
include_once "includes/pages/nav.php";
 
// Check if both id and lang parameters are present
if (isset($_GET['id']) && isset($_GET['lang'])) {
    $id = $_GET['id'];
    $lang = $_GET['lang'];

    $sqllangue = "SELECT *
    FROM langue
    WHERE langue.value_Langue = :value_Langue";
    $queryLangue = $db->prepare($sqllangue);
    $queryLangue->bindParam(":value_Langue", $lang, PDO::PARAM_STR);
    $queryLangue->execute();
    $row = $queryLangue->fetch(PDO::FETCH_ASSOC);
    $id_L = $row['Id_Langue'];
 
    // Fetch object details based on ID
    $sql = "SELECT *
            FROM oeuvres
            JOIN image ON oeuvres.Id_Oeuvres = image.Id_Oeuvres
            JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
            JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
            JOIN contenu ON contenu.Id_Oeuvres = oeuvres.Id_Oeuvres
            JOIN langue ON contenu.Id_Langue = langue.Id_Langue
            WHERE oeuvres.Id_Oeuvres = $id AND langue.Id_Langue = $id_L";
        $request = $db->query($sql);
        $arts = $request->fetchAll(PDO::FETCH_ASSOC);
    
} elseif(isset($_GET['id']) && !isset($_GET['lang'])) {
    $id = $_GET['id'];
    $id_L = 1;
    $sql1 = "SELECT *
    FROM oeuvres
    JOIN image ON oeuvres.Id_Oeuvres = image.Id_Oeuvres
    JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
    JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
    JOIN contenu ON contenu.Id_Oeuvres = oeuvres.Id_Oeuvres
    JOIN langue ON contenu.Id_Langue = langue.Id_Langue
    WHERE oeuvres.Id_Oeuvres = $id AND langue.Id_Langue = $id_L";
$request1 = $db->query($sql1);
$arts = $request1->fetchAll(PDO::FETCH_ASSOC);
  
} 

?>
 <div class="bkgfalc">
<div class="artwork">
<i class="fa-solid fa-chart-bar"></i>
 <div><?php echo DESC_H3; ?></div>
<?php foreach ($arts as $art) : ?>
<img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">
<?php endforeach ?>
</div>
 
<div class="description">
<i class="fa-solid fa-palette"></i>
<div><?php echo DESC1_H3; ?></div>
<p>
<?php 
            foreach ($arts as $art) {
                echo $art['description_Contenu'];
            }
?>
</p>
</div>
            
<div class="additional-info">
<i class="fa-solid fa-file-circle-plus"></i>
 <div><?php echo ADDINFOS_H3; ?></div>
<ul>
<?php foreach($arts as $art){ ?>
<li><strong><?php echo ARTISTE; ?> : </strong><?= $art['Prenom_Artiste'] . " "; ?><?= $art['Nom_Artiste']; ?></li>
<li><strong><?php echo LIBELLEO; ?> : </strong><?= $art['libelle_contenu']; ?></li>
<li><strong><?php echo PRIXO; ?> : </strong><?= $art['prix']; ?>€</li>
<li><strong><?php echo DIMO; ?> : </strong><?= " " . $art['hauteur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['largeur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['profondeur_Oeuvre'] . " "; ?></li>
<li><strong><?php echo DATE; ?> : </strong><?= date('d-m-y', strtotime($art['Date_Debut'])) . " "; ?>-<?= " " . date('d-m-y', strtotime($art['Date_Fin'])); ?></li>
<?php } ?>

</ul>
</div>
 
<div class="exhibition-video">
<i class="fa-regular fa-circle-play"></i>
<div><?php echo VIDEO_H3; ?></div>
 
    <iframe class="video" width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>
</div>
 
<div class="audio-file">
<i class="fa-solid fa-file-audio"></i>
<div><?php echo AUDIO_H3; ?></div>
<audio controls>
<source src="audio.mp3" type="audio/mpeg">
</audio>
</div>
</div>
<?php
include_once "includes/pages/footer.php";
?>


/*COLLECTION*/ sans le id du h2*/
<div class="filter">
  <span>Nos collections : </span>
    <select name="" id="select">
      <option value="Photographie">Photographie</option>
      <option value="Peinture">Peinture</option>
      <option value="Sculpture">Sculpture</option>
    </select>
</div>

<div class="expo-wrapper">
<?php
  $currentType = null;
  foreach ($Collecs as $Collec) :
    if ($Collec['libelle_Type'] != $currentType) :
      $currentType = $Collec['libelle_Type'];
  ?>
      <div class="rotate">
        <h2 class="colect-name"><?= $currentType; ?></h2>
      </div>
    <?php endif; ?>
    <div class="card-keeper-expo">
      <div class="card-expo" id="card-artiste">
        <div class="card-row2">
          <div class="image-artiste-ongoing" id="art-img">
            <img src="assets/images/artwork/<?= $Collec['chemin_Image']; ?>" alt="image collection">
          </div>
        </div>
        <div class="card-content-expo">
          <h4 class="libelle-expo"><?= $Collec["libelle_contenu"]; ?></h4>
          <div class="keep-expo-btn">
            <button class="voir-plus-expo">
              <a href="descriptionOeuvre.php?id=<?= $Collec["Id_Oeuvres"]; ?>"><?php echo AFFICHE; ?></a>
            </button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<button class="btn-afficher">
              <a href="descriptionOeuvre.php?id=<?= $oeuvre["Id_Oeuvres"]; ?>&lang=<?php echo $lang ?>"><?php echo AFFICHE; ?></a>
            </button>


  <header id="header">
    <div class="container">
      <div class="subcontainer">
        <nav class="navbar-container">
          <div class="navbar-left">
            <a href="accueil.php" class="nav-branding-1"><img src="assets/images/2 1.png" alt=""></a>
            <div class="nav-menu">
              <a href="accueil.php" class="nav-branding-2"><img src="assets/images/2 1.png" alt=""></a>
              <a href="listeExposition.php" class="nav-link <?php if ($current_page === 'listeExposition.php') echo 'active'; ?>"><?php echo EXPONAV; ?></a>
              <a href="listeArtiste.php" class="nav-link <?php if ($current_page === 'listeArtiste.php') echo 'active'; ?>"><?php echo ARTISTENAV; ?></a>
              <a href="collection.php" class="nav-link <?php if ($current_page === 'collection.php') echo 'active'; ?>"><?php echo COLLECNAV; ?></a>
              <a href="contact.php" class="nav-link <?php if ($current_page === 'contact.php') echo 'active'; ?>"><?php echo CONTACTNAV; ?></a>
              <ul>
                <li class="nav-liens">
                  <a href="listeExposition.php" class=""><?php echo EXPONAV; ?></a><br>
                </li>
                <li class="nav-liens">
                  <a href="listeArtiste.php" class=""><?php echo ARTISTENAV; ?></a><br>
                </li>
                <li class="nav-liens">
                  <a href="collection.php" class=""><?php echo COLLECNAV; ?></a><br>
                </li>
                <li class="nav-liens">
                  <a href="contact.php" class=""><?php echo CONTACTNAV; ?></a><br>
                </li>
              </ul>
              
              <div class="menu_langues">
                <form action="" method="GET" id="form_lang">
                  <ul>
                  <?php foreach ($langues as $lan) : ?>
                      <li>
                        <input style="display:none;" type="radio" name="lang" id="<?php echo $lan['value_Langue'] ?>" data-lang="<?php echo $lan['value_Langue'] ?>" onclick="changeLang();" value="<?php echo $lan['value_Langue'] ?>" <?php if (isset($_SESSION['lang']) && $_SESSION['lang'] ==  $lan['value_Langue']) {
                                                                                                                                                                                                                                          echo "checked";
                                                                                                                                                                                                                                        } ?>>
                      </li>
                      <label for="<?php echo $lan['value_Langue'] ?>"><img class="flag" src="./assets/images/flag/<?php echo $lan['chemin_Flag'] ?>" alt="<?php echo $lan['libelle_Langue'] ?>"><?php echo $lan['libelle_Langue'] ?></label>
                    <?php endforeach ?>
                  </ul>
                </form>
              </div>
            </div>
          </div>
 
          <div class="navbar-right">
            <div class="hamburger">
              <span class="bar"></span>
              <span class="bar"></span>
              <span class="bar"></span>
            </div>
          </div>
 
        </nav>
      </div>
    </div>
  </header>


  /*test pour partie admin card expo input */

  <?php
require_once "./config/pdo.php";
$sql = "SELECT *
FROM exposition
ORDER BY exposition.Id_Exposition ASC";
$requete = $db -> query($sql);
$expos = $requete->fetchAll(PDO::FETCH_ASSOC);
;?>
 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/styleM.css">
    <title>Document</title>
</head>
<body>
   
</body>
</html>
<div class="page-artist">
    <h2 class="title-page-artist">Liste des Expositions : </h2>
 
    <div class="search-container form-divs-list-artist">
        <form action="" method="GET">
            <label for="filter-expo">Filtrer par nom de l'exposition :</label>
      <input type="text" class="search-bar" id="filter-expo" name="filter-expo" placeholder="Entrer le nom de l'exposition">
      <button type="submit" class="search-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg></button>
    </form>
  </div>
 
<div class="art-content-now">
    <div class="expo-content-now">
        <div class="container-cards-art-now">
            <?php forEach($expos as $expo) :?>
                <div class="card-art-now-expo" id="<?= $expo['Id_Exposition'] ?>">
 
                    <div class="delete-panel" id="delete-project-overlay-<?= $expo['Id_Exposition'] ?>">
                        <div class="container-delete">
                            <div class="info-delete">
                                <p>Voulez-vous vraiment supprimer l'artiste ?</p>
                                <div>
                                    <button id="confirm-delete-next" data-expo-id="<?= $expo['Id_Exposition'] ?>">Oui, supprimer</button>
                                    <button id="cancel-delete-next">Non, pas maintenant</button>
                                </div>
                            </div>
                        </div>
                    </div>
 
                    <div class="card-row1">
                        <h2><?= $expo['libelle_Exposition'] ?></h2>
                    </div>
                    <div class="card-row2">
                        <div class="image-oeuvre-ongoing">
                            <a href=""><img src="./assets/img/exposition/<?php echo $expo['chemin_Affiche'];?>" alt=""></a>
                        </div>
                        <div class="content-infos-oeuvre-ongoing">
                            <div class="infos-oeuvre-ongoing artist-info-content">
                                <span class='info-atists'>Date de l'exposition :</span> <br>
                                <p class="expo-p"> <?= date('d-m-y', strtotime($expo['Date_Debut'])) . " "; ?>-<?= " " . date('d-m-y', strtotime($expo['Date_Fin'])); ?>
                           
                            </div>
                            <div class="action-oeuvre-ongoing">
                                <div class="modify-art-ongoing">
                                    <a href="expo-update.php?id=<?= $expo['Id_Exposition'] ?>">
                                        <svg viewBox="0 0 512 512"><path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/></svg>
                                    </a>
                                </div>
                                <div class="delete-art-ongoing">
                                <a href="#" class="delete-oeuvreNext-link link" data-id="<?= $expo['Id_Exposition'] ?>">
                                        <svg viewBox="0 0 448 512"><path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
                <?php endforeach ;?>
               
        </div>
    </div>
    <div class="container-button-art-ongoing">
        <button type="button" id="add-oeuvre-expo-now">
           <a href="./add-oeuvre-unique.php">Ajouter une exposition</a><svg viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg></button>
    </div>
</div>
 
<script>
    const nextDeleteLinks = document.querySelectorAll(".delete-oeuvreNext-link");
        nextDeleteLinks.forEach(function(nextDeleteLink){
            const oeuvreCard = nextDeleteLink.closest('.card-art-now-expo');
            const modal = oeuvreCard.querySelector('.delete-panel');
            const nextConfirmBtn = modal.querySelector("#confirm-delete-next");
            const nextCancelBtn = modal.querySelector("#cancel-delete-next");
 
            nextDeleteLink.addEventListener('click', function(event) {
                event.preventDefault();
                modal.style.display = 'block';
            })
 
            nextCancelBtn.addEventListener('click', function(event) {
                event.preventDefault();
                modal.style.display = 'none';
            })
 
            nextConfirmBtn.addEventListener('click', function(event) {
                event.preventDefault();
                const artisteId = this.getAttribute('data-artist-id');
                console.log(artisteId);
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete-artist.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function(){
                    if(xhr.status === 200) {
                        const oeuvreCard = nextDeleteLink.closest('.card-art-now-expo');
                        oeuvreCard.parentNode.removeChild(oeuvreCard);
                    } else {
                        console.error('Erreur lors de la suppression du artiste');
                    }
                };
 
                xhr.send('Id_Artiste=' + artisteId);
 
               
            })  
 
 
        })
</script>
 
 