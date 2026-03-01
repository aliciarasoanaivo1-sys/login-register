<?php
include "layout/header.php";

// Check if the user is logger in, if yes then redirect him to the home page
if (isset($_SESSION["email"])) {
    header("location: /index.php");
    exit;
}

// test
$email = "";
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email and Password are required";
    }
    else {
        include "tools/db.php";
        $dbConnection = getDatabaseConnection();

        $statement = $dbConnection->prepare(
            "SELECT id, first_name, last_name, phone, address, password, created_at FROM users WHERE email = ?"
        );

        // Bind variables to the propared statemetent as parameters
        $statement->bind_param('s', $email);

        // execute statement
        $statement->execute();

        // bind result statement
        $statement->bind_result($id, $first_name, $last_name, $phone, $address, $stored_password, $created_at);

        // fetch values
        if ($statement->fetch()) {
            if (password_verify($password, $stored_password)) {
                // Password is correct

                // Store data in session variables
                $_SESSION["id"] = $id;
                $_SESSION["first_name"] = $first_name;
                $_SESSION["last_name"] = $last_name;
                $_SESSION["email"] = $email;
                $_SESSION["address"] = $address;
                $_SESSION["phone"] = $phone;

                $_SESSION["created_at"] = $created_at;

                // Redirect user to the home page
                header("location: /index.php");
                exit;
            }
        }


        $statement->close();

        $error = "Email or Password invalid";
    }
}
?>


<div class="container py-5">
    <div class="mx-auto border shadow p-4" style="width: 400px">
        <h2 class="text-center mb-4">Login</h2>
        <hr />


        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?= $error ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" name="email" value="<?= $email?>" />
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input class="form-control" name="password" />
            </div>
                <div class="row mb-3">
                    <div class="col d-grid">
                        <button type="submit" class="btn btn-primary">Log in</button>
                    </div>
                    <div class="col d-grid">
                        <a href="/index.php" class="btn btn-outligne-primary">Cancel</a>
                    </div>
                </div>
        </form>
    </div>
</div>




<?php
include "layout/footer.php";
?>
