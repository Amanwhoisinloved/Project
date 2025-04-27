<!-- logo.html -->
<div class="logo-panel">
    <div style="font-weight: bold; font-size: 32px;">CALENDER CHOCHO</div>
    <div class="nav-buttons">
        <button onclick="window.location.href='login.php'">Login</button>
        <button onclick="window.location.href='signup.php'">Signup</button>
    </div>
</div>

<style>
    .logo-panel {
        background: rgb(39, 79, 124);
        width: 100%;
        padding: 20px;
        text-align: center;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        position: relative;
        top: 300px;
    }
    .nav-buttons {
        margin-top: 15px;
    }
    .nav-buttons button {
        margin: 5px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        background: white;
        border: 2px solid #4CAF50;
        border-radius: 5px;
        transition: background 0.3s, color 0.3s;
    }
    .nav-buttons button:hover {
        background: #4CAF50;
        color: white;
    }
</style>
