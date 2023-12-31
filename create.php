<?php
include 'functions.php';
displays_errors();
$pdo = pdo_connect_mysql();
$msg = '';

// Check if POST data is not empty
if (!empty($_POST)) {
    
    // If the POST variable is not empty then insert a new record into 
    // both our polls and poll_answers tables.

    // Check if title exists, otherwise make the title blank
    // note we also have a required input for title and answer
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    
    // Check if description exists, otherwise make the description blank
    $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
    // Insert new record into the "polls" table

    $timestamp = date("Y-m-d H:i:s");

    $stmt = $pdo->prepare('INSERT INTO polls VALUES (NULL, ?, ?, ?)');
    
    $stmt->execute([$title, $desc, $timestamp]);
    
    // Last inserted ID
    $poll_id = $pdo->lastInsertId();
    
    
    // Get the answers and convert the multiline string to an array, 
    // so we can add each answer to the "poll_answers" table
    $answers = isset($_POST['answers']) ? explode(PHP_EOL, $_POST['answers']) : '';
    foreach ($answers as $answer) {
        // If the answer is empty there is no need to insert
        if (empty($answer)) continue;
        // Add answer to the "poll_answers" table
        $stmt = $pdo->prepare('INSERT INTO poll_answers VALUES (NULL, ?, ?, 0)');
        
        $stmt->execute([$poll_id, $answer]);
    }
    // Output message
    $msg = 'Poll Successfully Created!';
}
?>

<?=template_header("Create", "fas fa-vote-yea fa-2x")?>


	
    
<div class="container mt-5 mb-5">
    <h2 class="mt-3 pt-3">Create Poll</h2>

    <form method="POST" action="create.php"> 
    <div class="container mt-5 mb-5">
        <div class="col-md-6-offset-6">
            <label for="title" class="form-label">Poll Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Enter title of poll/survey" maxlength="30" required>
        </div>
        <div class="col-md-6-offset-3">
            <label for="desc" class="form-label mt-5">Poll Description</label>
            <input type="text" name="desc" id="desc" class="form-control" placeholder="Enter the description" maxlength="256" required>
        </div>
        <div class="col-md-6-offset-3">
            <label for="answers" class="form-label mt-5">Poll answers</label>
            <textarea style="height: 200px;" name="answers" id="answers" class="form-control" placeholder="One answer per line" required></textarea><br><br>
        </div>
        <div class="col-md-6-offset-3 mb-5 pb-5">
            <input type="submit" value="Create" class="btn btn-primary">
            <a class="btn btn-success" href="index.php">Home</a>
        </div>
    </div>
    </form>
 </div>
    
    <!-- Confirmation Message Area -->
    <?php if ($msg): ?>
        <div class="alert alert-success text-center" role="alert">
            <p><?=$msg?></p>                
        </div>
        
    <?php endif; ?>
</div>

<?=template_footer()?>