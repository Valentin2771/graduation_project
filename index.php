<?php
require_once("helpers/config.php");

$query = "SELECT * FROM products ORDER BY create_date DESC";

$stmt = $connection->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$connection = null;

?>
<?php include_once("html/header.html"); ?>

        <main>
        <h2>Display inventory</h2>
        <p>
            <a href="create.php" class="btn btn-sm btn-success">Create</a>
        </p>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Image</th>
                <th scope="col">Content Name</th>
                <th scope="col">Content price</th>
                <th scope="col">Content description</th>
                <th scope="col">Date added</th>
                <th scope="col">Date modified</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $ind => $rec): ?>
                   <tr>
                    <th scope='row'> <?php echo ($ind + 1); ?> </th>
                    <td> <img src="<?php echo $rec['image']; ?>" class="thumb-image"></td>
                    <td> <?php echo $rec['title']; ?> </td>
                    <td> <?php echo $rec['price']; ?> </td>
                    <td> <?php echo $rec['description']; ?> </td>
                    <td> <?php echo $rec['create_date']; ?> </td>
                    <td> <?php echo $rec['modified_at'] ?? $rec['create_date']; ?> </td>
                    <td>
                        <form form method="GET" action="edit.php">
                            <input type="hidden" name="id" value="<?php echo $rec['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Edit</button>
                        </form>
                        
                        <form method="POST" action="delete.php">
                            <input type="hidden" name="id" value="<?php echo $rec['id']; ?>">
                            <input type="hidden" name="image" value="<?php echo $rec['image']; ?>">
                            <button type="submit"class="btn btn-sm btn-outline-danger">Erase</button>
                        </form>
                    </td>
                   </tr>
                
                <?php endforeach; ?>
            </tbody>
        </table>
        </main>
<?php include_once("html/footer.html");
