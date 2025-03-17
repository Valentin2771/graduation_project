<?php
// echo "Hello from " . __FILE__ . "<br>";

$image = "";
$title = "";
$description = "";
$price = "";

$error = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    require_once("helpers/config.php");
    require_once("helpers/stringGen.php");

    $date = date('Y-m-d H:i:s');
   
    if(!empty($_POST['title'])){
        $title = $_POST['title'];
    } else {
        $error .= "Title can't be missing<br>";
    }
    
    if(!empty($_POST['price'])){
        $price = $_POST['price'];
    } else {
        $error .= "Price can't be missing<br>";
    }

    if(!empty($_POST['description'])){
        $description = $_POST['description'];
    }
    // Make sure an image directory exists; if not, create it
    if(!is_dir('img')){
        mkdir('img');
    }

    // On submit, $_FILES gets populated with its first element, $_FILES['image']
    
    $image = $_FILES['image'];
    $imagePath = '';
    // If an actual image has been uploaded, then $_FILES['image']['tmp_name'] refers to an actual temporary file name
    // So, we can create a path and a directory name for that image

    if(!empty($image['tmp_name'])){
        $imagePath = 'img/' . stringGenerator($n);
    }

    if(empty($error)){
        // If no errors and and image file has been submitted, we can create the designated folder for that file
        if(!empty($imagePath)){
            mkdir($imagePath);
            $imagePath .= '/'. $image['name'];
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        $query = "INSERT INTO products (title, description, image, price, create_date) VALUES(:title, :description, :image, :price, :create_date)";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $imagePath);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':create_date', $date);
        $stmt->execute();
        header("location: index.php");
    }   
    $connection = null;
}

include_once "html/header.html";

?>

<main>
    <h2>Create new content</h2>
    <?php if(!empty($error)) echo "<p class='alert alert-danger'>$error</p>"; ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product-image" class="form-label">Content Image</label>
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