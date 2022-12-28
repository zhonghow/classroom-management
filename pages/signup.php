<?php

session_start();


if(!isset($_SESSION['signup_form_csrf_token'])) {
    $_SESSION['signup_form_csrf_token'] = bin2hex(random_bytes(32));
}

var_dump($_SESSION['signup_form_csrf_token']);

$database = new PDO(
    'mysql:host=devkinsta_db;
    dbname=Classroom_Management',
    'root',
    'qQs06NBbdQOEMav6'
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if($_POST['signup_form_csrf_token'] !== $_SESSION['signup_form_csrf_token']) {
        die('Nice try buddy!');
    }
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $statement = $database->prepare('SELECT * FROM management where email = :email');
    $statement->execute([
        'email' => $email
    ]);

    $user = $statement->fetch();

    if ($user) {
        echo "Email already exists";
    } else {
        if ($password != $confirm_password) {
            echo "Password must be identicle";
        } else {
            $statement = $database->prepare(
                "INSERT INTO management (email,password) VALUES (:email, :password)"
            );
            $statement->execute([
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
            header('Location:/');
            exit;
        }
    }
}



?>

<!DOCTYPE html>
<html>

<head>
    <title>Simple Auth - Sign Up</title>
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
                Sign Up a New Account
            </h5>
            <!-- sign up form-->
            <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required />
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required />
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required />
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-fu">
                        Sign Up
                    </button>
                </div>

                <input type="hidden" name="signup_form_csrf_token" value="<?= $_SESSION['signup_form_csrf_token']; ?>">
                
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