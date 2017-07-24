<!DOCTYPE html>
<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use Test\Entity\OptionValue;
use Test\Entity\ProductOption;
use Test\Repository\Repository;

$host = 'localhost';
$dbname = 'ksis';
$charset = 'utf8';
$user = 'root';
$pass = '1Q8aTQ36';

$dbConnection = new PDO(
    sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $dbname, $charset),
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]
);
$repository = new Repository($dbConnection);
?>

<html>
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h1> Параметрический поиск:</h1>
<form method="get" action="index.php">
    <?php
    $productsOptions = $repository->getOptionsValues();
    /** @var ProductOption $productsOption */
    foreach ($productsOptions as $productsOption) {
        echo '<h3>' . $productsOption . '</h3>';
        /** @var OptionValue $value */
        foreach ($productsOption->getValues() as $value) {
            echo '<input 
                type="checkbox"
                name="' . $productsOption->getId() . '[]"
                value="' . $value->getId() . '"
            >' . $value . '</input>';
        }
    }
    ?>
    <br>
    <button name="find">Искать</button>
</form>
<?php
if (array_key_exists('find', $_GET)) {
    unset($_GET['find']);
    $products = $repository->findProducts($_GET);
    foreach ($products as $product) {
        echo $product->getModel() . ',<br>';
        echo $product->getPrice() . " руб., ";
        /** @var ProductOption $option */
        foreach ($product->getOptions() as $option) {
            echo $option->getName() . ': ';
            foreach ($option->getValues() as $value) {
                echo $value->getValue();
                if (null !== $value->getProductOption()->getUnit()) {
                    echo ' ' . $value->getProductOption()->getUnit();
                }
                echo ', ';
            }
        }
        echo '<br><br> ';
    }
}
?>
</body>
</html>
