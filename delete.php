<?php

if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    require_once("helpers/config.php");
    
    $id = $_POST['id'];
    $query = "DELETE FROM products WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $image = $_POST['image'];
    $imagePath = dirname($image);
   
    unlink($image);
    rmdir($imagePath);
    $connection = null;
    header("Location: index.php");
} else{
    header("Location: index.php");
    exit;
}



?>