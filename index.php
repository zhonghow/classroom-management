<?php
session_start();
$database = new PDO(
    'mysql:host=devkinsta_db;
        dbname=Classroom_Management',
    'root',
    'qQs06NBbdQOEMav6'
);

$query = $database->prepare('SELECT * FROM students');
$query->execute();

$students = $query->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['action'] == 'add') {
        $statement = $database->prepare('INSERT INTO students(`people`) VALUE (:people)');
        $statement->execute([
            'people' => $_POST['students']
        ]);
        header('Location:/');
        exit;
    }

    if ($_POST['action'] == 'delete') {
        $statement = $database->prepare('DELETE FROM students where id = :id');
        $statement->execute([
            'id' => $_POST['id']
        ]);
        header('Location:/');
        exit;
    }

    if ($_POST['logout'] == 'logout') {
        unset($_SESSION['user']);
        header('Location:/');
        exit;
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Classroom Management</title>
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
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0">My Classroom</h3>
                <div class="d-flex">
                    <?php if (isset($_SESSION['user'])) : ?>
                        <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="POST">
                            <input type="hidden" name="logout" value="logout">
                            <h5><input type="submit" value="Log Out" class="border-0 bg-white text-primary" style="text-decoration: underline;"></h5>
                        </form>
                    <?php else : ?>
                        <h5><a href="login.php">Login</a></h5>
                        <h5 class="ms-3"><a href="signup.php">Sign Up</a></h5>
                    <?php endif ?>
                </div>
            </div>
            <div class="mt-4">

                <?php if (isset($_SESSION['user'])) : ?>
                    <form method="POST" action="<?= $_SERVER['REQUEST_URI']; ?>" class="d-flex justify-content-between align-items-center">
                        <input type="hidden" name="action" value="add">

                        <input type="text" class="form-control" placeholder="Add new student..." name="students" required />

                        <button class="btn btn-primary btn-sm rounded ms-2">Add</button>
                    </form>
                <?php else : ?>

                <?php endif ?>

            </div>
        </div>
    </div>

    <div class="card rounded shadow-sm mx-auto my-4" style="max-width: 500px;">
        <div class="card-body">
            <h3 class="card-title mb-3">Students</h3>
            <div>
                <ol>

                    <?php foreach ($students as $student) : ?>
                        <?php if (isset($_SESSION['user'])) : ?>
                            <form method="POST" action="<?= $_SERVER['REQUEST_URI']; ?>" class="d-flex justify-content-between align-items-center my-3">
                                <li><?= $student['people']; ?> </li>

                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                <button class="btn btn-danger btn-sm rounded ms-2">Remove</button>
                            </form>
                        <?php else : ?>
                            <li><?= $student['people']; ?> </li>
                        <?php endif ?>
                    <?php endforeach ?>

                </ol>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
</body>

</html>