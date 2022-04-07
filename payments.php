<?php

include_once 'header.php';

$alerts = [];

$id = $_SESSION['user'];

function displayListOfPaymentsByMonth() : string {

    global $id;

    $months = [];
    $spentAmount = [];

    if (isset($_GET['id']) && ($id = $_GET['id']) !== '') {

        $path = 'users/'.$id;

        if (FileSystem::directory_exists($path, $error)) {

            if (FileSystem::read_file($path.'/budget.json', $json, $error)) {

                $budget = json_decode(AES::decrypt($_SESSION['key'], $json), true);

                foreach ($budget as $key => $value) {
                    $months[] = $key;
                    $spentAmount[$key] = 0;
                    foreach ($value as $item) {
                        $spentAmount[$key] += $item['price'];
                    }
                }

            } else {
                $alerts[] = 'An unknown error occurred while trying to open the users\'s payments!';
            }

        } else {
            $alerts[] = 'The user with the id <b>'.$id.'</b>, does not exist.';
        }
    }

    $listOfPayments = '<div class="list-group">';

    foreach ($months as $month) {
        $date = date_create($month);
        $date = date_format($date, 'Y - F');
        $listOfPayments.= <<<ENDPAYENT
        <a href="payment.php?id=$id&month=$month" class="list-group-item list-group-item-action flex-column align-items-start mb-4">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">$date</h5>
            </div>
            <div class="d-flex w-100 justify-content-between">
                <p class="mb-1">Spent amount: $spentAmount[$month]</p>
                <small>HUF</small>
            </div>
        </a>
ENDPAYENT;

    }

    $listOfPayments.='</div>';

    return $listOfPayments;
}

if (count($alerts) !== 0) {
    foreach ($alerts as $alert) {
        echo createAlert($alert);
    }
}

?>

<main class="container w-75" id="content-container">
    <h2 class="mt-4 mb-2">List of payments by month</h2>
<?php echo displayListOfPaymentsByMonth(); ?>
</main>

<?php include_once 'footer.php'; ?>