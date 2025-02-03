<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notificación de Humanitarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header img {
            max-width: 100px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="https://example.com/logo.png" alt="Logo de Humanitarios">
            <h2>Notificación de Humanitarios</h2>
        </div>
        <p>Estimado/a <?php echo $author->display_name; ?>,</p>
