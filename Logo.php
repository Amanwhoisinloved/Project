<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <title>Logo</title>
    <style>

        @font-face {
        font-family: 'Retropix';
        src: url('fonts/Retropix.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
        }

        body {
            margin: 0;
            height: 100vh;
            background-image: url("images/samplebg.png");
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            font-family: 'Retropix', sans-serif;
        }

        img.logo {
            width: 500px;
            margin-top: 50px;
            margin-bottom: 1px;
        }

        .title {
            font-weight: bold;
            font-size: 36px;
            color: white;
            margin-top: -10px;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
        }

        .nav-links {
            display: flex;
            gap: 25px;
        }

        .nav-links a {
            font-size: 20px;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            color: white;
            text-decoration: underline;
            cursor: pointer;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color:rgb(196, 228, 197);
        }


        body {
        font-family: 'Retropix', sans-serif;
        }

        p {
        font-family: 'Retropix', sans-serif;
        font-size: 20px;
        letter-spacing: 1px;
        }

        .nav-btn img {
            height: 100px;
            width: auto; /* maintain aspect ratio */
            transition: transform 0.3s;
        }

        .nav-btn img:hover {
            transform: scale(1.05);
        }

        
    </style>
</head>
<body>
 
    <img src="images/logoandbranding.png" alt="Logo" class="logo">
    <p>Memo. Memories. Nostalgia.</p>
    <div class="nav-links">
<div class="nav-links">
    <a href="Login.php" class="nav-btn">
        <img src="images/loginbutton.png" alt="Login">
    </a>
    <a href="Signup.php" class="nav-btn">
        <img src="images/signupbutton.png" alt="Signup">
    </a>
    </div>

</body>
</html>
