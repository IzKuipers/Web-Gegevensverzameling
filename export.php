<?php

require_once ("./util/session.php");
require_once ("./util/error.php");
require_once ("./util/posts.php");
require_once ("./ui/headerbar.php");
require_once ("./ui/hero.php");

require_once "./vendor/autoload.php";

geefFoutmeldingWeer(); // Geef een potentiele foutmelding weer

postsExporteren(postsOphalen());