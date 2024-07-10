<?php
session_start(); // Start session to store to-do list items

// Initialize session variable for to-do list if it doesn't exist
if (!isset($_SESSION['todos'])) {
  $_SESSION['todos'] = array();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Add a new to-do item
  if (isset($_POST['add'])) {
    $new_todo = trim($_POST['new_todo']);
    if (!empty($new_todo)) {
      $_SESSION['todos'][] = ['task' => $new_todo, 'completed' => false];
    } else {
      $error = "Please enter a valid to-do item.";
    }
  }
  // Delete a to-do item
  elseif (isset($_POST['delete'])) {
    $index = $_POST['index'];
    if (isset($_SESSION['todos'][$index])) {
      unset($_SESSION['todos'][$index]);
      $_SESSION['todos'] = array_values($_SESSION['todos']); // Re-index array
    }
  }
  // Mark a to-do item as completed/uncompleted
  elseif (isset($_POST['toggle'])) {
    $index = $_POST['index'];
    if (isset($_SESSION['todos'][$index])) {
      $_SESSION['todos'][$index]['completed'] = !$_SESSION['todos'][$index]['completed'];
    }
  }
  // Edit a to-do item
  elseif (isset($_POST['edit'])) {
    $index = $_POST['index'];
    $edited_todo = trim($_POST['edited_todo']);
    if (isset($_SESSION['todos'][$index]) && !empty($edited_todo)) {
      $_SESSION['todos'][$index]['task'] = $edited_todo;
    } else {
      $error = "Please enter a valid to-do item.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Advanced To-Do List</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background: url('https://scientific-publishing.webshop.elsevier.com/wp-content/uploads/2022/08/what-background-study-how-to-write.jpg') no-repeat center center fixed; /* Background image */
      background-size: cover; /* Cover the entire page */
    }

    .container {
      width: 90%;
      max-width: 600px;
      margin: 50px auto;
      padding: 30px;
      border-radius: 10px;
      background-color: rgba(255, 255, 255, 0.9); /* White background with transparency */
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }

    h1 {
      font-family: 'Open Sans', sans-serif;
      text-align: center;
      font-size: 28px;
      margin-bottom: 20px;
      color: #2c3e50; /* Darker blue-gray for heading */
    }

    .todo-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .todo-list li {
      margin-bottom: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      border-radius: 5px;
      background-color: #ecf0f1; /* Light gray background for list items */
      transition: background-color 0.3s ease;
    }

    .todo-list li:hover {
      background-color: #dfe6e9; /* Slightly darker gray on hover */
    }

    .todo-list li:before {
      content: "\2714"; /* Checkmark symbol (Unicode character) */
      color: #bdc3c7; /* Light gray for unchecked items */
      font-size: 18px;
      margin-right: 10px;
    }

    .todo-list li.completed:before {
      color: #27ae60; /* Green for checked items */
    }

    .todo-list li.completed .task {
      text-decoration: line-through;
      color: #95a5a6; /* Gray for completed tasks */
    }

    .todo-list li .task {
      flex-grow: 1;
      margin-right: 10px;
      color: #2c3e50; /* Darker blue-gray for to-do items */
    }

    .todo-list li form {
      margin: 0;
      display: inline;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }

    input[type="text"] {
      width: calc(100% - 100px);
      padding: 10px;
      border: 1px solid #bdc3c7; /* Light gray border */
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      padding: 10px 15px;
      margin-left: 5px;
      border: none;
      border-radius: 5px;
      background-color: #3498db; /* Blue button background */
      color: #fff; /* White button text */
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #2980b9; /* Darker blue on hover */
    }

    @media (max-width: 600px) {
      .container {
        width: 100%;
        padding: 15px;
      }

      input[type="text"] {
        width: calc(100% - 90px);
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>To-Do List</h1>

    <?php if (isset($error)): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="post">
      <input type="text" name="new_todo" placeholder="Enter your to-do" required>
      <button type="submit" name="add">Add</button>
    </form>

    <ul class="todo-list">
      <?php foreach ($_SESSION['todos'] as $index => $todo): ?>
        <li <?php echo $todo['completed'] ? 'class="completed"' : ''; ?>>
          <span class="task"><?php echo htmlspecialchars($todo['task']); ?></span>
          <form action="" method="post" style="display: inline;">
            <input type="hidden" name="index" value="<?php echo $index; ?>">
            <button type="submit" name="toggle"><?php echo $todo['completed'] ? 'Uncomplete' : 'Complete'; ?></button>
          </form>
          <form action="" method="post" style="display: inline;">
            <input type="hidden" name="index" value="<?php echo $index; ?>">
            <input type="text" name="edited_todo" placeholder="Edit your to-do" required>
            <button type="submit" name="edit">Edit</button>
          </form>
          <form action="" method="post" style="display: inline;">
            <input type="hidden" name="index" value="<?php echo $index; ?>">
            <button type="submit" name="delete">Delete</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</body>
</html>
