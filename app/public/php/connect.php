<?php
$pdo = new PDO("mysql:host=mysql;dbname=karaj_restaurant", "root", "root");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
