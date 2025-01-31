<?php

require_once("gebruiker.php");
require_once("session.php");

use Dompdf\Dompdf;



// Deze functie geeft een lijst terug met alle posts. Het voegt ook de eigenschappen van
// iedere auteur toe aan iedere tweet om het makkelijker te maken voor de HTML implementatie
function postsOphalen()
{
  // Maak verbinding met de database dmv verbindMysqli()
  $connectie = verbindMysqli();

  try { // Probeer...
    // De vraag aan de database: Geef mij alle data van alle posts, met de nieuwste als eerste en de oudste als laatste (descending timestamp)
    $query = "SELECT * FROM posts WHERE repliesTo IS NULL ORDER BY timestamp DESC";

    $statement = $connectie->prepare($query); // Bereid de vraag aan de database voor

    if (!($statement->execute()))
      throw new Exception(); // Voer de vraag uit

    $result = array();

    // Schrijf voor iedere post de data naar de respectieve variabelen. Deze data zal veranderen iedere keer dat de fetch() functie wordt uitgevoerd, vandaar de while loop die volgt.
    $statement->bind_result($idPost, $auteur, $body, $likes, $timestamp, $reageertOp);

    while ($statement->fetch()) { // Ga over alle tweets in het resultaat
      $gebruiker = gebruikerOphalen($auteur); // Verkrijg de eigenschappen van de auteur
      $reacties = reactiesVanPost($idPost); // Verkrijg de reacties van de post

      // Voeg de tweet+auteur toe aan de lijst met posts
      $result[] = array("id" => $idPost, "auteur" => $gebruiker, "body" => $body, "likes" => $likes, "timestamp" => $timestamp, "reacties" => $reacties);
    }

    // Geef de array met alle "expanded" (als in iedere post + de auteur eigenschappen) terug
    return $result;
  } catch (Exception $e) { // Anders...
    return array(); // Geef een lege array terug als "dummy"
  } finally { // En ten slotte...
    // Probeer de connectie en statement te sluiten
    sluitMysqli($connectie, $statement);
  }
}

// Deze functie haalt alle reacties van een post op door gebruik te maken van recursie
function reactiesVanPost($id)
{
  // Maak verbinding met de database dmv verbindMysqli()
  $connectie = verbindMysqli();

  try { // Probeer...
    // De vraag aan de database: Geef mij alle data van alle posts, met de nieuwste als eerste en de oudste als laatste (descending timestamp)
    $query = "SELECT * FROM posts WHERE repliesTo = ? ORDER BY timestamp DESC";

    $statement = $connectie->prepare($query); // Bereid de vraag aan de database voor
    $statement->bind_param("i", $id); // Vervang het vraagteken met de daadwerkelijke ID

    if (!($statement->execute()))
      throw new Exception(); // Voer de vraag uit

    $result = array();

    // Schrijf voor iedere post de data naar de respectieve variabelen. Deze data zal veranderen iedere keer dat de fetch() functie wordt uitgevoerd, vandaar de while loop die volgt.
    $statement->bind_result($idPost, $auteur, $body, $likes, $timestamp, $reageertOp);

    while ($statement->fetch()) { // Ga over alle tweets in het resultaat
      $gebruiker = gebruikerOphalen($auteur); // Verkrijg de eigenschappen van de auteur

      $reacties = reactiesVanPost($idPost);

      // Voeg de tweet+auteur toe aan de lijst met posts
      $result[] = array("id" => $idPost, "auteur" => $gebruiker, "body" => $body, "likes" => $likes, "timestamp" => $timestamp, "reacties" => $reacties);
    }

    // Geef de array met alle "expanded" (als in iedere post + de auteur eigenschappen) terug
    return $result;
  } catch (Exception $e) { // Anders...
    return array(); // Geef een lege array terug als "dummy"
  } finally { // En ten slotte...
    // Probeer de connectie en statement te sluiten
    sluitMysqli($connectie, $statement);
  }
}

// Deze functie haalt de posts op van de gebruiker $id
function postsVanGebruiker($id)
{
  // Maak verbinding met de database dmv verbindMysqli()
  $connectie = verbindMysqli();

  try { // Probeer...
    // De vraag aan de database: Geef mij alle data van alle posts, met de nieuwste als eerste en de oudste als laatste (descending timestamp)
    $query = "SELECT * FROM posts WHERE auteur = ? ORDER BY timestamp DESC";

    $statement = $connectie->prepare($query); // Bereid de vraag aan de database voor
    $statement->bind_param("i", $id);

    if (!($statement->execute()))
      throw new Exception(); // Voer de vraag uit

    $result = array();

    // Schrijf voor iedere post de data naar de respectieve variabelen. Deze data zal veranderen iedere keer dat de fetch() functie wordt uitgevoerd, vandaar de while loop die volgt.
    $statement->bind_result($idPost, $auteur, $body, $likes, $timestamp, $reageertOp);

    while ($statement->fetch()) { // Ga over alle tweets in het resultaat
      $gebruiker = gebruikerOphalen($auteur); // Verkrijg de eigenschappen van de auteur

      $reacties = reactiesVanPost($idPost);

      // Voeg de tweet+auteur toe aan de lijst met posts
      $result[] = array("id" => $idPost, "auteur" => $gebruiker, "body" => $body, "likes" => $likes, "timestamp" => $timestamp, "reacties" => $reacties);
    }

    // Geef de array met alle "expanded" (als in iedere post + de auteur eigenschappen) terug
    return $result;
  } catch (Exception $e) { // Anders...
    return array(); // Geef een lege array terug als "dummy"
  } finally { // En ten slotte...
    // Probeer de connectie en statement te sluiten
    sluitMysqli($connectie, $statement);
  }
}
function postsExporteren($posts)
{
  probeerSessionStart();

  $gebruiker = gebruikerUitSessie();

  // Start the HTML with inline styles for Twitter-like light mode
  $html = <<<HTML
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #0f1419; /* Dark text */
            background-color: #ffffff; /* Light background */
            margin: 0;
            padding: 0;
        }
        .post-lijst {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
        }
        .post {
            border: 1px solid #e1e8ed; /* Light gray border */
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #ffffff; /* White card background */
            box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .auteur {
            font-weight: bold;
            font-size: 14px;
            color: #1d9bf0; /* Twitter blue for author name */
            margin-bottom: 5px;
        }
        .body {
            font-size: 15px;
            line-height: 1.5;
            margin: 10px 0;
            color: #0f1419; /* Primary text color */
        }
        .timestamp {
            font-size: 12px;
            color: #657786; /* Muted text color */
        }
    </style>
    <div class='post-lijst'>
    HTML;

  // Build the posts
  if (count($posts) == 0) {
    $html .= <<<HTML
          <p style="text-align: center; color: #657786; font-size: 14px;">
            Hier zijn geen tweets! Wat een leegte...
          </p>
        HTML;
  } else {
    foreach ($posts as $post) {
      // Escape all dynamic content
      $body = htmlspecialchars($post['body']);
      $datumtijd = date("j M - G:i", strtotime($post["timestamp"]));
      $postVanGebruiker = $gebruiker["id"] == $post["auteur"]["idGebruiker"];
      $gebruikersnaam = htmlspecialchars($post['auteur']['naam']) . ($postVanGebruiker ? " (jij)" : "");

      // Add each post with styling
      $html .= <<<HTML
              <div class='post'>
                <div class="auteur">
                  $gebruikersnaam
                </div>  
                <div class="body">$body</div>
                <div class="timestamp">
                  $datumtijd
                </div>
              </div>
            HTML;
    }
  }

  $html .= "</div>"; // Close the post list

  // Initialize Dompdf
  $dompdf = new Dompdf();
  $dompdf->loadHtml($html);

  // Set paper size
  $dompdf->setPaper("A4", "portrait");

  // Render PDF
  $dompdf->render();

  // Ensure no extra output corrupts the PDF
  ob_end_clean();

  // Stream the PDF to the browser
  $dompdf->stream("export.pdf", ["Attachment" => 0]); // Set to 1 for forced download
}


// Deze functie geeft de daadwerkelijke posts weer in de HTML
function postLijst($posts, $geenReacties = false)
{
  probeerSessionStart(); // Start de sessie voor de ingelogde gebruiker's eigenschappen

  $gebruiker = gebruikerUitSessie(); // Verkrijg de gebruiker uit de informatie die bij het inloggen is opgeslagen in de session

  echo <<<HTML
    <script>
      function reactieFormulier(id) {
        const formulier = document.getElementById(id);

        if (!formulier) return;

        formulier.classList.toggle("zichtbaar");
      }
    </script>
  HTML;

  echo "<div class='post-lijst'>"; // Open een DIV element met de class 'post-lijst'

  // Geef een melding weer als er geen tweets zijn.
  if (count($posts) == 0) {
    echo <<<HTML
      <p class="geen">
        <span class="material-icons-round">warning</span>
        <span class="bericht">Hier zijn geen tweets! Wat een leegte...</span>
      </p>
    HTML;
  }

  // Voor iedere tweet in de array $posts, doe...
  foreach ($posts as $post) {
    if ($geenReacties) {
      echo genereerMinimalePostHtml($post, $gebruiker);
    } else {
      echo genereerPostHtml($post, $gebruiker);
    }
  }

  echo "</div>";
}

// Deze functie genereert de minimale HTML van een POST (gebruikt voor het profiel)
function genereerMinimalePostHtml($post, $gebruiker)
{
  $body = $post['body']; // De content van de tweet
  $id = $post['id']; // De ID van de tweet
  $aantal_likes = $post["likes"]; // De likes van de tweet
  $datumtijd = date("j M · G:i", strtotime($post["timestamp"])); // Een nette datum en tijd die onder aan de post wordt weergegeven

  $gebruikerId = $post["auteur"]["idGebruiker"];
  $postVanGebruiker = $gebruiker["id"] == $post["auteur"]["idGebruiker"]; // Een boolean die aangeeft of de post van de ingelogde gebruiker is
  $gebruikersnaam = $post['auteur']['naam'] . ($postVanGebruiker ? " (jij)" : ""); // De gebruikersnaam die boven de post wordt weergegeven

  // Een verwijder-knop die alleen wordt weergegeven als de post van de ingelogde gebruiker is. Die conditie wordt ook gecontroleerd in /verwijder.php
  $verwijderKnop = $postVanGebruiker ?
    <<<HTML
      <a href="/verwijder.php?id=$id" class="delete-button">
        <span class='material-icons-round'>delete</span>
        <span>Verwijder</span>
      </a>
      HTML : "";

  $resultaat = "";
  $resultaat .= <<<HTML
    <div class='post'>
      <div class="post-content">
      <!-- De linker kant: hier wordt de profielfoto weergegeven (een standaard foto in dit project) -->
        <div class="left">
          <img src="/images/pfp.png" alt="">
        </div>
        <!-- De rechter kant: hier wordt de content van de tweet weergegeven -->
        <div class="right">
          <!-- Boven de content: De auteur's naam + de ID van de post -->
          <div class="auteur">
            <span class="naam"><a href="/profiel.php?id=$gebruikerId">$gebruikersnaam</a></span>
            <span class="id">· Post #$id</span>
          </div>  
          <!-- De content van de post, beschermd tegen XSS  -->
          <div class="body">$body</div>
          <!-- Boven de content: De auteur's naam + de ID van de post -->
          <div class="actions">
            <!-- De knop om een post te "liken" -->
            <a href="/like.php?id=$id" class="like-button">
              <span class='material-icons-round'>favorite_outline</span>
              $aantal_likes
            </a>
            <!-- De verwijder knop. Deze variabele is een lege string ("") als de post niet van de ingelogde gebruiker is -->
            $verwijderKnop
            <!-- De geformatteerde datum en tijd van de post -->
            <div class="timestamp">
              $datumtijd
            </div>
          </div>
        </div>
      </div>
    </div>
    HTML;

  return $resultaat;

}

// Deze functie genereert de HTML van een POST met de reacties door gebruik te maken van recursion (gebruikt voor de index pagina)
function genereerPostHtml($post, $gebruiker, $isReactie = false)
{
  $body = $post['body']; // De content van de tweet
  $id = $post['id']; // De ID van de tweet
  $aantal_likes = $post["likes"]; // De likes van de tweet
  $timestamp = date("j M · G:i", strtotime($post["timestamp"])); // Een nette datum en tijd die onder aan de post wordt weergegeven

  $gebruikerId = $post["auteur"]["idGebruiker"]; // De ID van de auteur van de post
  $postVanGebruiker = $gebruiker["id"] == $post["auteur"]["idGebruiker"]; // Een boolean die aangeeft of de post van de ingelogde gebruiker is
  $gebruikersnaam = $post['auteur']['naam'] . ($postVanGebruiker ? " (jij)" : ""); // De gebruikersnaam die boven de post wordt weergegeven

  // Een verwijder-knop die alleen wordt weergegeven als de post van de ingelogde gebruiker is. Die conditie wordt ook gecontroleerd in /verwijder.php
  $verwijderKnop = $postVanGebruiker ?
    <<<HTML
      <a href="/verwijder.php?id=$id" class="delete-button">
        <span class='material-icons-round'>delete</span>
        <span>Verwijder</span>
      </a>
      HTML : "";

  $acties = $gebruiker["naam"] == "" ? "" : <<<HTML
      <!-- De knop om een post te "liken" -->
      <a href="/like.php?id=$id" class="like-button">
        <span class='material-icons-round'>favorite_outline</span>
        $aantal_likes
      </a>
      <!-- De knop om te reageren op een post -->
      <a href="javascript:reactieFormulier('reactieForm_$id')">
        <span class="material-icons-round">chat_bubble_outline</span>
      </a>
      <!-- De verwijder knop. Deze variabele is een lege string ("") als de post niet van de ingelogde gebruiker is -->
      $verwijderKnop
    
  HTML;

  $reactieForm = reactieFormulier($post); // Het reactie-formulier van de post

  $classNaam = "post " . ($isReactie ? "reactie" : ""); // De class van de post

  // De uiteindelijke HTML van de post leeft hier
  $resultaat = <<<HTML
    <div class='$classNaam'>
      <div class="post-content">
      <!-- De linker kant: hier wordt de profielfoto weergegeven (een standaard foto in dit project) -->
        <div class="left">
          <img src="/images/pfp.png" alt="">
        </div>
        <!-- De rechter kant: hier wordt de content van de tweet weergegeven -->
        <div class="right">
          <!-- Boven de content: De auteur's naam + de ID van de post -->
          <div class="auteur">
            <span class="naam"><a href="/profiel.php?id=$gebruikerId">$gebruikersnaam</a></span>
            <span class="id">· Post #$id</span>
          </div>  
          <!-- De content van de post, beschermd tegen XSS  -->
          <div class="body">$body</div>
          <!-- Boven de content: De auteur's naam + de ID van de post -->
          <div class="actions">
            $acties
            <!-- De geformatteerde datum en tijd van de post -->
            <div class="timestamp">
              $timestamp
            </div>
          </div>
        </div>
      </div>
      <!-- De reacties van de post-->
      <div class="post-reacties">
        $reactieForm
    HTML;

  // Voor elke reactie van de post...
  foreach ($post["reacties"] as $reactie) {
    $resultaat .= genereerPostHtml($reactie, $gebruiker, true); // Voeg alle reacties toe aan de HTML
  }

  // Sluit de post-content en post-reacties divs
  $resultaat .= "</div></div>";

  // Stuur de resulterende HTML terug
  return $resultaat;
}

// Deze functie genereert de HTML voor het reactie-veld wat normaliter bij ieder bericht te zien is
function reactieFormulier($post)
{
  $id = $post["id"]; // De ID van de post
  $auteurnaam = $post["auteur"]["naam"];

  return <<<HTML
    <!-- Dit is een POST form die wordt verstuurd naar /stuurpost.php, met een hidden value die de reactie-id bevat -->
    <form class="reactie-form" method="POST" action="/stuurpost.php" id="reactieForm_$id">
      <input type="hidden" name="reactieOp" value="$id">
      <input type="text" placeholder="Wat heb je te zeggen?" name="bericht" value="@$auteurnaam " required maxlength="256" rows="2"></textarea>
      <!-- De knop om de post te versturen -->
      <input type="submit" value="Reageer">
    </form>
  HTML;
}

// Deze functie wordt gebruikt om het totaal aantal likes uit een lijst van posts te halen
function totaleLikes($posts)
{
  $likes = 0;

  foreach ($posts as $post) {
    $likes += $post["likes"];
  }

  return $likes;
}