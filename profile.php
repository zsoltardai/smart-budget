<?php
    include_once 'header.php';

    $id = $firstname = $lastname = $email = $password_hash = '';

    $alerts = [];

    $edit = false;

    if (isset($_GET['id']) && ($id = $_GET['id']) !== '') {

        $path = 'users/'.$id;

        if (FileSystem::directory_exists($path, $error)) {

            if (FileSystem::read_file($path.'/user.json', $json, $error)) {

                $user = User::fromJSON($json);

                $firstname = $user->firstname;
                $lastname = $user->lastname;
                $email = $user->email;
                if (md5($user->email) === $_SESSION['user']) {
                    $password_hash = $user->password_hash;
                }

            } else {
                $alerts[] = 'An unknown error occurred while trying to open the profile!';
            }

        } else {
            $alerts[] = 'The user with the id <b>'.$id.'</b>, does not exist.';
        }
    }

    if (isset($_GET['a']) && $_GET['a'] === 'edit') $edit = true;
?>

<?php

if (count($alerts) !== 0) {
    foreach ($alerts as $alert) {
        echo createAlert($alert);
    }
}

?>

<?php

function displayProfileCard() : string {

    global $id, $firstname, $lastname, $email;

    $btnEdit = ($id === $_SESSION['user']) ? "<a class='btn card' href='profile.php?id=$id&a=edit'>Edit</a>" : '' ;
    return <<<PROFILE
        <div class="container mt-5 mb-5 p-4 card">
            <div class="d-flex flex-column">
                <div class="d-flex flex-row">
                    <b class="mr-4">Name:</b>
                    <p>$firstname $lastname</p>
                </div>
                <div class="d-flex flex-row">
                    <b class="mr-4">E-mail:</b>
                    <p>$email</p>
                </div>
                $btnEdit
            </div>
        </div>
PROFILE;
}


function displayEditProfileCard() : string {

    global $id, $firstname, $lastname, $email;

    return <<<EDITPROFILE
        <div class="container card mt-5 mb-5 p-4">
            <div class="card-title m-2">
                <h2>Edit profile</h2>
            </div>
            <div class="card-body">
                <form method="post" action="edit_profile.php">
                    <input hidden name="id" value="$id" />
                    <div class="form-group">
                        <label class="text-muted" for="first-name">First name</label>
                        <input class="form-control" type="text" id="first-name" name="first-name" placeholder="e.g. Jhon" value="$firstname">
                    </div>
                    <div class="form-group">
                        <label class="text-muted" for="last-name">Last name</label>
                        <input class="form-control" type="text" id="last-name" name="last-name" placeholder="e.g. Jhonson" value="$lastname">
                    </div>
                    <div class="form-group">
                        <label class="text-muted" for="email">E-mail</label>
                        <input class="form-control" disabled type="email" id="email" name="email" placeholder="e.g. jhon@example.com" value="$email">
                    </div>
                    <div class="form-group mt-4">
                        <input class="btn btn-primary w-100" type="submit" value="Save">
                    </div>
                </form>
            </div>
        </div>
EDITPROFILE;
}

?>

<main class="container w-75" id="content-container">
    <?php
        if (!$edit) { echo displayProfileCard(); }
        else { echo displayEditProfileCard(); };
    ?>
</main>

<?php
    include_once 'footer.php';
?>