<?php
/**
 * Auth on Session using Plain Password on Database [Example 1]
 * sign-in: Sign in page
 */

// initialize session
session_start();

// check for session and redirect to homepage if 'example1_user_id' session already exists
if(isset($_SESSION['example1_user_id']))
{
    header('location: index.php');
    exit();
}
// otherwise, continue with the rest of this page:


// set database connection
require_once 'config/database.php';
if(!isset($conn)) exit();

// initialize global data
$view     = 'sign-in';
$title    = 'Sign in';
$username = '';
$error    = '';

// process sign-in request when the form is submitted
if(isset($_POST['sign-in']))
{
    // get sign-in credentials
    $username = $_POST['username'];
    $password = $_POST['password'];

    // query for the user with these sign-in credentials
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE (`email` = ? OR `username` = ?) AND `password` = ?");
    $stmt->bind_param("sss", $username, $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // if no user is found, set the error message
    if($result->num_rows <= 0) {
        $error = 'Invalid credentials.';
    }
    // if a user is found, store the user id in session and redirect to homepage
    else {
        $row = $result->fetch_assoc();
        $_SESSION['example1_user_id'] = $row['id'];

        header('location: index.php');
        exit();
    }
}
?>

<!-- html top -->
<?php require_once 'partials/html-1-top.php'; ?>
<!-- navbar -->
<?php require_once 'partials/navbar.php'; ?>


<!-- main content -->
<main class="container pt-3">
    <!-- content header -->
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="m-0"><?= $title ?></h2>
    </div>
    <p>
        Join the community of positive vibes!
        Sign in to post your shout-outs and recognize the amazing people.
    </p>

    <!-- sign-in form -->
    <div class="row pt-3">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="sign-in.php">
                        <!-- email or username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Email address or Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
                        </div>

                        <!-- password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <!-- error message (if there's any) -->
                        <?php if($error != '') { ?>
                            <p class="text-danger"><i class="fas fa-fw fa-exclamation-circle"></i> <?= $error ?></p>
                        <?php } ?>

                        <!-- sign-in button -->
                        <button type="submit" name="sign-in" class="btn btn-dark">Sign in</button>

                        <!-- sign-up link -->
                        <div class="mt-2 d-flex justify-content-end">
                            <small>No account yet? <a href="sign-up.php" class="text-decoration-none">Sign up</a></small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>


<!-- html bottom -->
<?php require_once 'partials/html-2-bot.php'; ?>