<?php

include_once 'header.php';

$alerts = [];

$id = $_SESSION['user'];

function displayListOfPayments() : string {

    global $id;

    $budgetOfMonth = [];

    if ((isset($_GET['id']) && ($id = $_GET['id']) !== '') && (isset($_GET['month']) && ($month = $_GET['month']))) {

        $path = 'users/'.$id;

        if (FileSystem::directory_exists($path, $error)) {

            if (FileSystem::read_file($path.'/budget.json', $json, $error)) {

                $budget = json_decode(AES::decrypt($_SESSION['key'], $json), true);

                $budgetOfMonth = $budget[$month];

            } else {
                $alerts[] = 'An unknown error occurred while trying to open the users\'s payments!';
            }

        } else {
            $alerts[] = 'The user with the id <b>'.$id.'</b>, does not exist.';
        }
    }

    $listOfPayments = <<<ENDTABLEHEAD
        <table class="table" style="overflow: scroll;">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Category</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
ENDTABLEHEAD;

    $counter = 0;

    foreach ($budgetOfMonth as $payment) {
        $counter += 1;
        $name = $payment['name'];
        $price = $payment['price'];
        $category = $payment['category'];
        $date = $payment['date'];
        $listOfPayments.= <<<ENDPAYENT
        <tr>
          <th scope="row">$counter</th>
          <td>$name</td>
          <td>$price</td>
          <td>$category</td>
          <td>$date</td>
        </tr>
ENDPAYENT;

    }

    $listOfPayments.= <<<ENDTABLEFOOTER
            </tbody>
        </table>
ENDTABLEFOOTER;

    return $listOfPayments;
}

if (count($alerts) !== 0) {
    foreach ($alerts as $alert) {
        echo createAlert($alert);
    }
}

?>

    <main class="container w-75" id="content-container">
        <h2 class="mt-4 mb-2">List of payments</h2>
        <?php echo displayListOfPayments(); ?>
    </main>

<?php include_once 'footer.php'; ?>