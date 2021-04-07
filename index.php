<?php
require_once 'inc/functions.php';
// echo $_GET['task'];

// exit();
$task = $_GET['task'] ?? 'report';
$info = '';
$error = $_GET['error'] ?? '0';

if ('delete' == $task) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    if ($id > 0) {
        deleteStudent($id);
        header('location: index.php?task=report');
    }
}

if ('seed' == $task) {
    seed();
    $info = 'Seeding is complete';
}

if (isset($_POST['submit'])) {
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $roll = filter_input(INPUT_POST, 'roll', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

    if ($id) {
        // Update existing student
        if ($fname != '' && $lname && $roll != '') {
            $result = updateStudent($id, $fname, $lname, $roll);
            if ($result) {
                header('location: index.php?task=report');
            } else {
                $error = 1;
            }
        }
    } else {
        // add new student
        if ($fname != '' && $lname && $roll != '') {
            $result = addStudent($fname, $lname, $roll);
            if ($result) {
                header('location: index.php?task=report');
            } else {
                $error = 1;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD-with Raw PHP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <style>
        body{
            margin-top: 30px;
        }
        .text-center{
            text-align:center;
        }
        .success{
            color:green;
            font-size:2rem;
            padding-top: 20px;
        }
    
    </style>
</head>
<body>
<div class="container">
    <div class="text-center row">
        <div class="column column-60 column-offset-20">
            <h2>Project CRUD With Raw PHP</h2>
            <p>A sample project to perform CRUD operations using plan file and PHP</p>
            <?php include_once 'inc/template/nav.php'; ?>
            <hr>
            
            <?php if ($info != '') {
                echo "<pre><p class='success'>{$info}</p></pre>";
            } ?>
            
        </div>
    </div>
    <?php if ('1' == $error): ?>
    <div class="row">
        <div class="column column-60 column-offset-20">
            <blockquote>
            Duplicate Roll Number.
            </blockquote>
        </div>  
    </div>
    <?php endif; ?>
    <?php if ('report' == $task): ?>
    <div class="row">
        <div class="column column-60 column-offset-20">
            <?php generateReport(); ?>
        </div>  
    </div>
    
    <?php endif; ?>
    <?php if ('add' == $task): ?>
    <div class="row">
        <div class="column column-60 column-offset-20">
            <form action="index.php?task=report" method="POST">
                <label for="fname">First Name</label>
                <input type="text" name="fname" id="fname">
                <label for="lname">Last Name</label>
                <input type="text" name="lname" id="lname">
                <label for="roll">Roll</label>
                <input type="number" name="roll" id="roll" min="1">
                <input type="submit" name="submit" value="Save">
            </form>
        </div>  
    </div>
    <?php endif; ?>
    <?php if ('edit' == $task):

        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $student = getStudent($id);
        if ($student);
        ?>
    <div class="row">
        <div class="column column-60 column-offset-20">
            <form method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="fname">First Name</label>
                <input type="text" name="fname" id="fname" value="<?php echo $student[
                    'fname'
                ]; ?>">
                <label for="lname">Last Name</label>
                <input type="text" name="lname" id="lname" value="<?php echo $student[
                    'lname'
                ]; ?>">
                <label for="roll">Roll</label>
                <input type="number" name="roll" id="roll" min="1" value="<?php echo $student[
                    'roll'
                ]; ?>">
                <input type="submit" name="submit" value="Update">
            </form>
        </div> 
        
    </div>
    <?php
    endif; ?>
</div>

<script src="assets/js/script.js"></script>
</body>
</html>