<?php

    include_once 'base/FileSystem.php';
    include_once 'base/User.php';
    include_once 'base/Common.php';
    include_once 'base/AES.php';

    $firstname = $lastname = $email = $password = $confirm_password = '';

    $firstname_error = $lastname_error = $email_error = $password_error = $confirm_password_error = '';

    $alerts = [];

    if (isset($_POST['btn-register'])) {

        if (empty(trim($_POST['first-name']))) {
            $firstname_error = 'You did not provide a valid first name!';
        } else {
            $firstname = $_POST['first-name'];
        }

        if (empty(trim($_POST['last-name']))) {
            $lastname_error = 'You did not provide a valid last name!';
        } else {
            $lastname = $_POST['last-name'];
        }

        if (empty(trim($_POST['email']))) {
            $email_error = 'You did not provide a valid E-mail address!';
        } else {
            $email = $_POST['email'];
        }

        if (empty(trim($_POST['password']))) {
            $password_error = 'You did not provide a valid password!';
        } else {
            $password = $_POST['password'];
        }

        if (empty(trim($_POST['confirm-password']))) {
            $confirm_password_error = 'You did not confirm the your password!';
        } else {
            $confirm_password = $_POST['confirm-password'];
        }

        if ($firstname_error === '' && $lastname_error === '' && $email_error === ''&& $password_error === ''
            && $password_error === '' &$confirm_password_error === '') {

            if ($password === $confirm_password) {

                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                $user = new User($firstname, $lastname, $email, $password_hash);

                $path = 'users/'.md5($email);

                if (!FileSystem::directory_exists($path, $error)) {

                    if (FileSystem::create_directory($path, $error)) {

                        $json = $user->toJSON();

                        if (FileSystem::write_file($path.'/user.json', $json, $error)) {

                            $AESKey = AES::generateKey();

                            $encryptedAESKey = AES::encrypt($password, $AESKey);

                            if (FileSystem::write_file($path.'/key.txt', $encryptedAESKey, $error)) {

                                header('location: login.php');

                            } else {
                                $alerts[] = 'Failed to create AES key!';
                            }

                        } else {
                            $alerts[] = 'Failed to create the user due to an unknown reason!';
                        }

                    } else {
                        $alerts[] = 'Failed to create the user due to an unknown reason!';
                    }

                } else {
                    $alerts[] = 'There is a user with this E-mail address!';
                }
            }
            else {
                $confirm_password_error = 'The two passwords did not match!';
            }
        }

    }

?>

<html lang="eng">
<head>
    <title>SmartBudget - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="bg-light">
<div class="container w-75">
    <?php
        foreach ($alerts as $alert) {
            echo createAlert($alert);
        }
    ?>
</div>
<div id="form-container" class="container w-50 card mt-5 mb-5 p-4">
    <div class="card-title">
        <a class="nav-link" href="register.php"><h2>Registration</h2></a>
    </div>
    <div class="card-body">
        <form method="post" action="register.php">
            <div class="form-group">
                <label class="text-muted" for="first-name">First name</label>
                <input class="form-control" type="text" id="first-name" name="first-name" placeholder="Jhon" value="<?php echo $firstname;?>" />
                <p id="first-name-error" class="text-danger"><?php echo $firstname_error;?></p>
            </div>
            <div class="form-group">
                <label class="text-muted" for="last-name">Last name</label>
                <input class="form-control" type="text" id="last-name" name="last-name" placeholder="Jhonson" value="<?php echo $lastname;?>" />
                <p id="last-name-error" class="text-danger"><?php echo $lastname_error;?></p>
            </div>
            <div class="form-group">
                <label class="text-muted" for="email">E-mail</label>
                <input class="form-control" type="email" id="email" name="email" placeholder="e.g. user@example.com" value="<?php echo $email;?>" />
                <p id="email-error" class="text-danger"><?php echo $email_error;?></p>
            </div>
            <div class="form-group">
                <label class="text-muted" for="password">Password</label>
                <input class="form-control" type="password" id="password" name="password" placeholder="e.g. password" />
                <p id="password-error" class="text-danger"><?php echo $password_error;?></p>
            </div>

            <div class="form-group">
                <label class="text-muted" for="confirm-password">Confirm password</label>
                <input class="form-control" type="password" id="confirm-password" name="confirm-password" placeholder="e.g. password" />
                <p id="confirm-password-error" class="text-danger"><?php echo $confirm_password_error;?></p>
            </div>
            <div class="form-group">
                <input class="btn btn-primary w-100" type="submit" value="Register" name="btn-register" />
            </div>
            <div class="form-group">
                <p>Do you have an account? Login <a href="login.php">here.</a></p>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/1c819c2c7e.js" crossorigin="anonymous"></script>
<script>
    function onWindowResize() {
        if (window.innerWidth < 600) {
            document.getElementById('form-container').classList.remove('w-50');
            document.getElementById('form-container').classList.add('w-75');
            return;
        }

        if (window.innerWidth > 1200) {
            document.getElementById('form-container').classList.remove('w-50');
            document.getElementById('form-container').classList.add('w-25');
            return;
        }

        document.getElementById('form-container').classList.remove('w-75');
        document.getElementById('form-container').classList.add('w-50');
    }

    window.addEventListener('load', onWindowResize);

    window.addEventListener('resize', onWindowResize);

    function removeElementById(id) {
        let element = document.getElementById(id);
        element.parentElement.removeChild(element);
    }

    document.getElementById('first-name').addEventListener('keyup', function () {
        let firstname = document.getElementById('first-name').value;
        let firstnameError = document.getElementById('first-name-error');
        firstnameError.innerText = firstname !== '' ?  '' : 'You have to provide your first name!';
    });

    document.getElementById('last-name').addEventListener('keyup', function () {
        let lastname = document.getElementById('last-name').value;
        let lastnameError = document.getElementById('last-name-error');
        lastnameError.innerText = lastname !== '' ?  '' : 'You have to provide your last name!';
    });

    document.getElementById('email').addEventListener('keyup', function () {
        let email = document.getElementById('email').value;
        let emailError = document.getElementById('email-error');
        emailError.innerText = email !== '' ?  '' : 'You have to provide an E-mail address!';
    });

    document.getElementById('password').addEventListener('keyup', function () {
        let password = document.getElementById('password').value;
        let passwordError = document.getElementById('password-error');
        passwordError.innerText = password !== '' ?  '' : 'You have to provide a password!';
    });

    document.getElementById('confirm-password').addEventListener('keyup', function () {

        let confirmPassword = document.getElementById('confirm-password').value;
        let confirmPasswordError = document.getElementById('confirm-password-error');

        if (confirmPassword === '') {
            confirmPasswordError.innerText = 'You have to confirm your password!';
            return;
        }

        if (confirmPassword !== document.getElementById('password').value) {
            confirmPasswordError.innerText = 'The two passwords do not match!';
            return;
        }

        confirmPasswordError.innerText = '';

    });
</script>
</body>
</html>
