<?php
require_once '../vendor/autoload.php';
echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Validácia formulár</title>';
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">';

echo '</head>';
echo '<body>';

$form = new PersonForm();
if (count($_POST)) {
    if ($form->isValid($_POST)) {
        echo '<ul>';
        foreach ($form->getValues() as $key => $value) {
            echo sprintf('<li><strong>%s:</strong> %s</li>', $form->getElement($key)->getLabel(), $value);
        }
        echo '</ul>';
    } else {
        echo ' <div class="alert alert-danger" role="alert">';
        foreach ($form->getMessages() as $key => $value) {
            echo sprintf('<strong>%s:</strong> %s <br>', $form->getElement($key)->getLabel(), implode(', ', $value));
        }
        echo '</div>';
    }

} else {
    $form->setDefaults([
        'age' => '4X',
        'email' => 'nic vám nedám',
        'phone' => '777 123 456',
    ]);
}
echo $form;

echo '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>';

echo '</body>';
echo '</html>';
