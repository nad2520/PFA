<?php
// Main layout wrapper. During migration, many views will output full HTML on their own.
// This layout is used for new MVC views that output only page content.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lexora</title>
</head>
<body>
  <?php echo $content ?? ''; ?>
</body>
</html>

