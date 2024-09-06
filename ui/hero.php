<?php

function Hero() {
  echo <<<HTML
  <header class="hero">
    <div class="left">
      <h1>Laat alle creativiteit los</h1>
      <p>Twitter PHP is een verbeterde versie van Twitter die meer mogelijkheden voor creatieve uitting biedt dan de oude Twitter.</p>
      <a href="/login.php">
        <button class="cta">Inloggen</button>
      </a>
    </div>
    <div class="right">
      <img src="/images/logo.png" alt="">
    </div>
  </header>
  HTML;
}