<?php

session_start();


if (!isset($_SESSION['login_form_csrf_token'])) {
    $_SESSION['login_form_csrf_token'] = bin2hex(random_bytes(32));
}

var_dump($_SESSION['login_form_csrf_token']);

$database = new PDO(
    'mysql:host=devkinsta_db;
    dbname=Classroom_Management',
    'root',
    'qQs06NBbdQOEMav6'
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if($_POST['login_form_csrf_token'] !== $_SESSION['login_form_csrf_token']) {
        die("Nice try buddy! But I'm smarter than you ;)");
    }
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    $statement = $database->prepare('SELECT * FROM management where email = :email');
    $statement->execute([
        'email' => $email
    ]);

    $user = $statement->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = ['id' => $user['id'], 'email' => $user['email']];

            header('Location:/');
            exit;
        } else {
            echo "Invalid email or password";
        }
    } else {
        echo "Invalid email or password";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Simple Auth - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <style type="text/css">
        body {
            background: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="card rounded shadow-sm mx-auto my-4" style="max-width: 500px;">
        <div class="card-body">
            <h5 class="card-title text-center mb-3 py-3 border-bottom">
                Login To Your Account
            </h5>
            <!-- login form-->
            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" />
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-fu">Login</button>
                </div>

                <input type="hidden" name="login_form_csrf_token" value="<?= $_SESSION['login_form_csrf_token']; ?>">

            </form>
        </div>
    </div>
    <!-- Go back link -->
    <div class="text-center">
        <a href="index.php" class="text-decoration-none"><i class="bi bi-arrow-left-circle"></i> Go back</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>