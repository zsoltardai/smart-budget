<?php

include_once 'base/FileSystem.php';
include_once 'base/User.php';
include_once 'base/Common.php';
include_once 'base/AES.php';

$email = $password = '';

$email_error = $password_error = '';

$alerts = [];

if (isset($_POST['btn-login'])) {

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

    if ($email_error === '' && $password_error === '') {

        $path = 'users/'.md5($email);

        if (FileSystem::directory_exists($path, $error)) {

            if (FileSystem::read_file($path.'/user.json', $json, $error)) {

                $user = User::fromJSON($json);

                if ($user !== null) {

                    if (password_verify($password, $user->password_hash)) {

                        if (FileSystem::read_file($path.'/key.txt', $encryptedAESKey, $error)) {

                            $decryptedAESKey = AES::decrypt($password, $encryptedAESKey);

                            session_start();

                            $_SESSION['user'] = md5($user->email);
                            $_SESSION['firstname'] = $user->firstname;
                            $_SESSION['lastname'] = $user->lastname;
                            $_SESSION['email'] = $user->email;
                            $_SESSION['key'] = $decryptedAESKey;

                            header('location: index.php');
                        } else {
                            $alerts[] = 'Failed to decrypt the AES key!';
                        }
                    } else {
                        $password_error = 'The provided password was invalid!';
                    }
                }
            } else {
                $alerts[] = 'Failed to login due to an unknown error!';
            }
        } else {
            $alerts[] = 'There is no user with this E-mail address!';
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
        <div id="form-container" class="container w-50 card mt-5 p-4">
            <div class="card-title">
                <a class="nav-link" href="login.php"><h2>Login</h2></a>
            </div>
            <div class="card-body">
                <form method="post" action="login.php">
                    <div class="form-group">
                        <label class="text-muted" for="email">E-mail</label>
                        <input class="form-control" type="email" id="email" name="email" placeholder="e.g. user@example.com" value="<?php echo $email;?>" />
                        <p id="email-error" class="text-danger mt-2"><?php echo $email_error;?></p>
                    </div>
                    <div class="form-group">
                        <label class="text-muted" for="password">Password</label>
                        <input class="form-control" type="password" id="password" name="password" placeholder="e.g. password" />
                        <p id="password-error" class="text-danger mt-2"><?php echo $password_error;?></p>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary w-100" type="submit" value="Login" name="btn-login" />
                    </div>
                    <div class="form-group">
                        <p>Don't you have an account? Register <a href="register.php">here.</a></p>
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

            function removeElementById(id) {
                let element = document.getElementById(id);
                element.parentElement.removeChild(element);
            }

            window.addEventListener('load', onWindowResize);

            window.addEventListener('resize', onWindowResize);

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
        </script>
    </body>
</html>