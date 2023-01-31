<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отправить заявку</title>
</head>
<body>

    <form action="/" method="post">
        <label for="name" style="display: block;">Имя</label>
        <input type="text" id="name" name="name" required>
        <label for="phone" style="display: block;">Телефон</label>
        <input type="text" id="phone" name="phone" required>
        <label for="email" style="display: block;">Email</label>
        <input type="text" id="email" name="email" required>
        <label for="price" style="display: block;">Цена</label>
        <input type="text" id="price" name="price" required>
        <br>
        <input type="submit" value="Отправить" style="display: block;">
    </form>
    
    
</body>
</html>

<?php
    include_once "./src/classes/LeadManager.class.php";
    if ($_POST['phone']){
        LeadManager::send($_POST); 
    }
    
