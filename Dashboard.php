<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'calendardb');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Get user ID
$user_result = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'");
$user_id = $user_result->fetch_assoc()['id'];

// Get all notes for this user
$notes_query = $conn->query("SELECT note_date, note, note_image FROM calendar_notes WHERE user_id = $user_id ORDER BY note_date DESC");
$notes = [];
while ($row = $notes_query->fetch_assoc()) {
    $notes[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <style>

@font-face {
    font-family: 'Retropix';
    src: url('fonts/retropix.ttf') format('truetype');
}

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;

            background: url('images/bgwow.png') center/cover no-repeat;
            filter: blur(10px);
            z-index: -2;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }



        .sidebar-left {
            width: 240px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 30px 20px;
            background-color: rgba(255, 255, 255, 0.15); 
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(10px); 
            
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1); 
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .pixel-font {
            color: white;
            font-family: 'Retropix', sans-serif;
            stroke-width: 5%;
            stroke: black;
            stroke-opacity: 1; /* 100% opacity */
            text-shadow: 0px 4px 0px rgba(0, 0, 0, 0.8); /* Vertical shadow */
        }



        .sidebar-left ul {
            list-style: none;
            padding: 0;
            margin-left:4%;
        }

        .sidebar-left ul li {
            margin-bottom: 20px;
        }

        .sidebar-left ul li a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            padding: 8px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar-left ul li a:hover {
             background-color: #dceeff;
            color: #3399ff;
        }

        .sidebar-left ul li i {
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 300px;
            padding: 40px;
            flex: 1;
            max-width: 900px;
        }

        h1, h2, h3 {
            color: #333;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        
        .dashboard-card {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    

        .dashboard-card h3 {
            margin-top: 0;
            font-family: 'Retropix', sans-serif;
            color: rgb(0, 0, 0);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        
        .note-card {
            background-color: #e6f4ff;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid #3399ff;
        }
        
        .note-date {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .note-text {
            margin-bottom: 10px;
        }
        
        .note-image {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
        }
        
        .notes-container {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }
        
        .summary-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .summary-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
        }
        
        .pink-bg {
            background-color: rgba(26, 202, 255, 0.8);
        }
        
        .blue-bg {
            background-color: rgba(26, 202, 255, 0.8);
        }
        
        .green-bg {
            background-color: rgba(26, 202, 255, 0.8);
        }
        
        .empty-state {
            text-align: center;
            padding: 30px;
            color: #888;
        }
        
        .empty-state i {
            font-size: 40px;
            margin-bottom: 10px;
        }

        /* Gallery styling */
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            max-height: 400px; /* You can adjust this height */
            overflow-y: auto;
            padding-right: 5px;
        }

        .gallery::-webkit-scrollbar {
        width: 3px;
        }

        .gallery::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 4px;
        }

        .gallery-item {
            width: 48%;
            position: relative;
        }

        .gallery-item img {
            width: 250%;
            height: auto;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .gallery-item img:hover {
            transform: scale(1.05);
        }

        .caption {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 5px;
            border-radius: 4px;
        }


/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.9);
}

.modal-content {
    margin: auto;
    display: block;
    max-width: 80%;
    max-height: 70vh;
    border-radius: 8px;
}

#modalCaption {
    margin: 20px auto;
    text-align: center;
    color: #fff;
    font-size: 16px;
    max-width: 80%;
}

.close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #fff;
    font-size: 30px;
    font-weight: bold;
    cursor: pointer;
}

.modal-datetime {
    color: #ccc;
    font-size: 0.9em;
    text-align: center;
    margin-top: 10px;
}


    </style>
</head>
<body>

<div class="sidebar-left">
    <img src="images/logo.png" alt="My Calendar" style="width: 180%; max-width: 250px; margin-bottom:40px; margin-top:20px; display: block;">
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="Calendar.php"><i class="fas fa-calendar"></i> Calendar</a></li>
        <li><a href="menu.php"><i class="fas fa-bars"></i> Menu</a></li>
        <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

        <div class="main-content">
            <h1 class="pixel-font"style="
            background-image: url('images/welcomebackground.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            padding: 20px;
            display: inline-block;
            color: white;">
        Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!
            <img src="images/cutecar.png" alt="car" style="height: 50px; vertical-align: bottom; margin-right: 1px;">
        </h1>
    
        <div class="dashboard-grid">
            <div class="dashboard-card">
        <h3>
        <img src="images/imagegalleryicon.png" alt="Gallery Icon" style="height: 30px; vertical-align: middle; margin-right: 1px;">
        Image Gallery
        </h3>
        <div class="gallery">
            <?php foreach ($notes as $note): ?>
                <?php if (!empty($note['note_image'])): ?>
                    <div class="gallery-item">
                        <img 
                            src="<?= htmlspecialchars($note['note_image']) ?>" 
                            alt="Note Image"
                            onclick="openModal(
                                '<?= htmlspecialchars($note['note_image']) ?>', 
                                `<?= nl2br(htmlspecialchars($note['note'])) ?>`, 
                                'Uploaded on: <?= date('F j, Y - g:i A', strtotime($note['note_date'])) ?>'
                            )"
                        >
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
                </div>




        <div class="dashboard-card">
            <h3><img src="images/summaryicon.png" alt="Gallery Icon" style="height: 32px; vertical-align: middle;">
             Summary</h3>
            
            <div class="summary-item">
                <div class="summary-icon pink-bg">
                    <i class="fas fa-clipboard"></i>
                </div>
                <div>
                    <h4>Total Notes</h4>
                    <p><?= count($notes) ?></p>
                </div>
            </div>
            
            <div class="summary-item">
                <div class="summary-icon blue-bg">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div>
                    <h4>Upcoming Notes</h4>
                    <p>To be shown in calendar view</p>
                </div>
            </div>
            
            <div class="summary-item">
                <div class="summary-icon green-bg">
                    <i class="fas fa-image"></i>
                </div>
                <div>
                    <h4>With Images</h4>
                    <p><?php 
                        $with_images = 0;
                        foreach ($notes as $note) {
                            if (!empty($note['note_image'])) $with_images++;
                        }
                        echo $with_images;
                    ?></p>
                </div>
            </div>
        </div>
        

        <div class="dashboard-card" style="grid-column: span 2;">
            <h3><i class="fas fa-history"></i> Recent Notes</h3>
            <div class="notes-container">
                <?php if (count($notes) > 0): ?>
                    <?php 
                    // Display only the 5 most recent notes
                    $recent_notes = array_slice($notes, 0, 5);
                    foreach ($recent_notes as $note): 
                    ?>
                        <div class="note-card">
                            <div class="note-date"><?= date('F j, Y', strtotime($note['note_date'])) ?></div>
                            <div class="note-text"><?= nl2br(htmlspecialchars($note['note'])) ?></div>
                            <?php if (!empty($note['note_image'])): ?>
                                <img class="note-image" src="<?= htmlspecialchars($note['note_image']) ?>" alt="Note Image">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="far fa-sticky-note"></i>
                        <p>No notes found. Add notes from the calendar page.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
            </div>
        </div>

<!-- Modal for showing image details -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
    <div id="modalCaption"></div>
    <div id="modalDatetime" class="modal-datetime"></div>
</div>


<script>
function openModal(imageSrc, captionText, datetimeText) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    const caption = document.getElementById("modalCaption");
    const datetime = document.getElementById("modalDatetime");

    modal.style.display = "block";
    modalImg.src = imageSrc;
    caption.innerHTML = captionText;
    datetime.innerHTML = datetimeText;
}

function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}
</script>



</body>
</html>

<?php $conn->close(); ?>
