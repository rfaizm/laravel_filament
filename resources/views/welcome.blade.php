<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to PrintMaster</title>
    
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #333;
}

.welcome-container {
    text-align: center;
    background-color: #ffffff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
}

.logo {
    font-size: 48px;
    font-weight: bold;
    margin-bottom: 20px;
}

.logo-red {
    color: #e74c3c;
}

.logo-yellow {
    color: #f1c40f;
}

h1 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #2c3e50;
}

p {
    font-size: 14px;
    color: #7f8c8d;
    margin-bottom: 30px;
}

.get-started-btn {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 15px 30px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.get-started-btn:hover {
    background-color: #c0392b;
}
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="logo">
            <span class="logo-red">Dwi Printing</span>
        </div>
        <h1>Welcome to Dwi Printing</h1>
        <p>Your ultimate solution for custom t-shirt printing and more. Design, print, and wear your creativity!</p>
        <a href="{{ url('admin') }}" class="get-started-btn">Get Started</a>
    </div>
</body>
</html>