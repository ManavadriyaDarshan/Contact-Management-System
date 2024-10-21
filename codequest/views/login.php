<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 0;
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 0;
            font-size: 2rem;
            opacity: 1;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            transition: transform 0.2s;
            z-index: 1;
            position: relative;
            margin-top: 40px;
        }

        .card:hover {
            transform: scale(1.02);
        }

        form {
            margin: 0;
        }

        form div {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: #5cb85c;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #4cae4c;
        }

        .error {
            color: red;
            font-size: 14px;
            text-align: center; /* Center align the error message */
            margin-top: 10px; /* Add some space above the error message */
        }
    </style>
</head>
<body>
    <h1>Contact Management System</h1>
    <div class="card">
        <form action="../api/auth.php" method="POST">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <?php if (isset($_GET['error'])): ?>
                <p class="error"><?= htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
