<?php
    include_once 'header.php';

    global $categories;

    $id = $_SESSION['user'];

?>

<main class="container d-flex" id="content-container">
    <div class="card m-2 w-100">
        <div class="card-title mt-2">
            <h3 class="nav-link">Add item to your budget</h3>
        </div>
        <div class="card-body">
            <form action="budget.php" method="post">
                <input hidden name="id" value="<?php echo $id; ?>" />
                <div class="form-group">
                    <label class="text-muted" for="product-name">Name</label>
                    <input class="form-control" type="text" name="product-name" id="product-name" placeholder="e.g. Mushrooms" />
                    <p class="text-danger" id="product-name-error"></p>
                </div>
                <div class="form-group">
                    <label class="text-muted" for="product-price">Price</label>
                    <input class="form-control" type="number" step="1" name="product-price" id="product-price" placeholder="e.g. 100"/>
                    <p class="text-danger" id="product-price-error"></p>
                </div>
                <div class="form-group">
                    <label class="text-muted" for="product-category">Category</label>
                    <select required class="form-control" name="product-category" id="product-category">
                        <option hidden selected>Select</option>
                        <?php
                        foreach ($categories as $category) {
                            echo "<option value='$category'>$category</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input class="btn btn-primary w-100" type="submit" name="btn-add" value="Add" />
                </div>
            </form>
        </div>
    </div>
    <div class="card m-2 w-100">
        <div class="card-title mt-2">
            <h3 class="nav-link">Statistics of <?php echo date('Y-m'); ?></h3>
        </div>
        <div class="card-body">
            <div>
                <canvas id="statistics"></canvas>
            </div>
            <script>
                <?php

                    if (!FileSystem::read_file('users/'.$id.'/budget.json', $json, $error)) {
                        Logger::log('Sys', $error);
                    }

                    if ($json !== null) {
                        echo 'let budget = '.AES::decrypt($_SESSION['key'], $json).';';
                    } else {
                        echo 'let budget = {};';
                    }

                    echo 'let categories = '.json_encode($categories).';';

                ?>

                let values = [];

                for (let i = 0; i < categories.length; i++) {
                    let category = categories[i];

                    let currentMonth = budget['<?php echo date('Y-m'); ?>'];

                    for (let j = 0; j < currentMonth.length; j++) {
                        let transaction = currentMonth[j];
                        if (transaction['category'] === category) {
                            if (values[i] === undefined) {
                                values[i] = transaction['price'];
                            }
                            else {
                                values[i] += transaction['price'];
                            }
                        }
                    }
                }

                let backgroundColors = [];

                for (let i = 0; i < values.length; i++) {
                    let red = Math.random()*256|0;
                    let green = Math.random()*256|0;
                    let blue = Math.random()*256|0;
                    backgroundColors.push(`rgb(${ red }, ${ green }, ${ blue })`);
                }

                const data = {
                    labels: categories,
                    datasets: [{
                        label: 'Monthly expenses',
                        data: values,
                        backgroundColor: backgroundColors,
                        hoverOffset: 4
                    }]
                };

                const config = {
                    type: 'doughnut',
                    data: data,
                    options: {}
                };

                const myChart = new Chart(
                    document.getElementById('statistics'),
                    config
                );
            </script>
        </div>
    </div>
</main>

<script>
    document.getElementById('product-name').addEventListener('keyup', function () {
        let productName = document.getElementById('product-name').value;
        let productNameError = document.getElementById('product-name-error');
        productNameError.innerText = productName !== '' ?  '' : 'You have to provide a name for the product!';
    });

    document.getElementById('product-price').addEventListener('keyup', function () {
        let productPrice = document.getElementById('product-price').value;
        let productPriceError = document.getElementById('product-price-error');
        productPriceError.innerText = productPrice !== '' ?  '' : 'You have to provide a price for the product!';
    });

    function onWindowResize() {
        if (window.innerWidth < 1000) {
            document.getElementById('content-container').classList.remove('flex-row');
            document.getElementById('content-container').classList.add('flex-column');
            return;
        }

        if (window.innerWidth >= 1000) {
            document.getElementById('content-container').classList.remove('flex-column');
            document.getElementById('content-container').classList.add('flex-row');
            return;
        }

        document.getElementById('content-container').classList.remove('flex-column');
        document.getElementById('content-container').classList.add('flex-row');
    }

    window.addEventListener('load', onWindowResize);

    window.addEventListener('resize', onWindowResize);
</script>

<?php
    include_once 'footer.php';
?>

