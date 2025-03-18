<?php

require_once("helpers/config.php");

$title = $_POST['title'] ?? "";
$imageOld = $_POST['image'] ?? "";
$image = $_FILES['image'] ?? null;
$description = $_POST['description'] ?? "";
$price = $_POST['price'] ?? "";
$id = $_POST['id'] ?? null;

// scenario: the page form is submitted via POST method
if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(!empty($_GET['id'])){

        $id = $_GET['id'];
        $query = "SELECT * from products WHERE id = :id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
       
        if(!empty($record)){
            $title = $record['title'];
            $imageOld = $record['image'];
            $description = $record['description'];
            $price = $record['price'];
            $id = $record['id'];
            $stmt = null;
        } else{
            header('location: index.php');
            exit;
        }

    } else{
        header('location: index.php');
        exit;
    }
}

// scenario: the page is targeted externally through a get GET request and an id is sent via query string
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once("helpers/stringGen.php");

    $flags = [];

    $query = "";
    if(!empty($title)){
        $flags['title'] = ':title';
    }

    if(!empty($description)){
        $flags['description'] = ':description';
    }

    if(!empty($price)){
        $flags['price'] = ':price';
    }

    if(!empty($image['tmp_name'])){
        $flags['image']= ':image';
        $flag_img = true;
    }

    if(!empty($flags)){
       
        $modified_at = date('Y-m-d H:i:s');
        
        foreach($flags as $k=>$v){
            $query .= $k . '=' . $v . ', ';
        }

        $query = 'UPDATE products SET ' . $query . 'modified_at = :modified_at WHERE id = :id';

        $stmt = $connection->prepare($query);
        if(array_key_exists('title', $flags)){
            $stmt->bindParam(':title', $title);
        }

        if(array_key_exists('description', $flags)){
            $stmt->bindParam(':description', $description);
        }

        if(array_key_exists('price', $flags)){
            $stmt->bindParam(':price', $price);
        }

        if(array_key_exists('image', $flags)){
            if(!empty($imageOld)){
               $imagePath = dirname($imageOld);
               unlink($imageOld);
               $imagePath .= '/' . $image['name'];
               move_uploaded_file($image['tmp_name'], $imagePath);
            } else {
                $imagePath = 'img/' . stringGenerator($n);
                mkdir($imagePath);
                $imagePath .= '/'. $image['name'];
                move_uploaded_file($image['tmp_name'], $imagePath);
            }
            $stmt->bindParam(':image', $imagePath);
        }
        $stmt->bindParam(':modified_at', $modified_at);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
    header("Location: index.php");
    exit;
} 

include_once "html/header.html";

?>

<main>
    <h2>Edit an existing content</h2>
    <div class="img-container">
       <?php if(!empty($imageOld)) echo "<img src='$imageOld' class='img-update' alt='Actual content'>"; ?>
    </div>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product-image" class="form-label">Content Image</label>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="image" value="<?php echo $imageOld; ?>">
            <input type="file" name="image" id="product-image">
        </div><br>
        
        <div class="form-group">
            <label for="product-title" class="form-label">Content Name</label>
            <input type="text" name="title" class="form-control" id="product-title" value='<?php echo $title; ?>'>
        </div>

        <div class="form-group">
            <label for="product-description" class="form-label">Content Description</label>
            <textarea class="form-control" name="description" id="product-description"><?php echo $description; ?></textarea>
        </div>

        <div class="form-group">
            <label for="product-price" class="form-label">Content Price</label>
            <input type="number" step="0.01" name="price" class="form-control" id="product-price" value='<?php echo $price; ?>'>
        </div><br>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</main>

<?php
    include_once "html/footer.html";
?>