<?php


use App\Entity\Product;
use App\Entity\ProductManufacturer;
use App\Entity\ProductParameter;

ini_set('memory_limit', '16M');
require_once __DIR__ . '/../vendor/autoload.php';
$entityManager = require_once __DIR__ . '/../config/bootstrap.php';
$message = "";
$alertType = "";
$batchSize = 100;
$i = 0;
$manufacturers = [];

if (isset($_POST['download'])) {
    $xmlUrl = $_POST['xmlUrl'];

    $xml = simplexml_load_file($xmlUrl);

    if ($xml === false) {
        echo "Nepodarilo sa načítať XML";
        exit;
    } else {

        foreach ($xml->SHOPITEM as $item) {
            $productName = (string)$item->PRODUCTNAME;
            $manufacturerName = (string)$item->MANUFACTURER;

            if (!isset($manufacturers[$manufacturerName])) {
                $manufacturer = $entityManager->getRepository(ProductManufacturer::class)->findOneBy(['name' => $manufacturerName]);

                if (!$manufacturer) {
                    $manufacturer = new ProductManufacturer();
                    $manufacturer->setName($manufacturerName);
                    $entityManager->persist($manufacturer);
                    $entityManager->flush();
                }

                $manufacturers[$manufacturerName] = $manufacturer;
            }

            /** @var Product $product */
            $manufacturer = $manufacturers[$manufacturerName] ?? $entityManager->getRepository(ProductManufacturer::class)->findOneBy(['name' => $manufacturerName]);
            $product = $entityManager->getRepository(Product::class)->findOneBy(['name' => $productName, 'manufacturer' => $manufacturer]);

            if (!$product) {
                $product = new Product();
                $product->setName($productName);
                $product->setPrice((float)$item->PRICE_VAT);
                $product->setManufacturer($manufacturer);
                $product->setDescription((string)$item->DESCRIPTION);
                $manufacturer->addProduct($product);
                $entityManager->persist($product);
            }

            foreach ($item->PARAM as $param) {
                $paramType = (string)$param->PARAM_NAME;
                $paramValue = (string)$param->VAL;

                $productParam = $entityManager->getRepository(ProductParameter::class)->findOneBy([
                    'type' => $paramType,
                    'value' => $paramValue
                ]);

                if (!$productParam) {
                    $productParam = new ProductParameter();
                    $productParam->setType($paramType);
                    $productParam->setValue($paramValue);
                    $entityManager->persist($productParam);
                }

                if (!$product->getProductParameters()->contains($productParam)) {
                    $product->getProductParameters()->add($productParam);
                }
            }

            if (($i % $batchSize) === 0) {
                $entityManager->flush();
                $entityManager->clear();
            }

            $i++;
        }

        $entityManager->flush();
        $entityManager->clear();
        $message = "Produkty boli úspešne stiahnuté a uložené.";
        $alertType = "success";
    }
}

//Pagination
$perPage = 200;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;
$products = $entityManager->getRepository(Product::class)->findBy([], null, $perPage, $offset);
$totalProductsCount = $entityManager->getRepository(Product::class)->count([]);
$totalPages = ceil($totalProductsCount / $perPage);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sťahovanie XML</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script
            src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous">
    </script>
</head>
<body>
<div class="card">
    <div class="card-body">
        <form method="post">
            <form method="post">
                <input type="hidden" name="xmlUrl" value="https://demo.shopio.cz/export/zbozi-heureka/xml">
                <input type="submit" class="btn btn-warning" name="download" value="Stáhnout XML">
            </form>
        </form>
    </div>
</div>

<?php if ($message != ""): ?>
    <div id="success-message" class="alert alert-<?= $alertType; ?>" role="alert">
        <?= $message; ?>
    </div>
<?php endif; ?>

<?php if ($totalProductsCount > 0): ?>
    <div class="card">
        <nav class="m-3">
            <ul class="pagination justify-content-end">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Názov</th>
                    <th scope="col">Cena</th>
                    <th scope="col">Výrobca</th>
                    <th scope="col">Popis</th>
                    <th scope="col">Parametre</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <th scope="row"><?= $product->getId(); ?></th>
                        <td><?= $product->getName(); ?></td>
                        <td><?= $product->getPrice(); ?> €</td>
                        <td><?= $product->getManufacturer() ? $product->getManufacturer()->getName() : 'Neznámy'; ?></td>
                        <td><?= $product->getDescription(); ?></td>
                        <td>
                            <?php foreach ($product->getProductParameters() as $parameter): ?>
                                <span class="badge bg-info text-dark"><?= $parameter->getType() ?> - <?= $parameter->getValue() ?> </span>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous">
</script>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous">
</script>

<!--JAVASCRIPT-->
<script>
    $(document).ready(function () {
        const successMessage = $('#success-message');
        if (successMessage.length) {
            $('#success-message').delay(2000).fadeOut('slow');
        }
    });
</script>


</html>
