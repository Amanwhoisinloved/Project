<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "Unauthorized";
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'calendardb');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

$user_result = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'");
$user_id = $user_result->fetch_assoc()['id'];

$notes_query = $conn->query("SELECT note_date, note, note_image FROM calendar_notes WHERE user_id = $user_id ORDER BY note_date DESC");
$notes = [];
while ($row = $notes_query->fetch_assoc()) {
    $notes[] = $row;
}

$today = date('Y-m-d');
$week_from_now = date('Y-m-d', strtotime('+7 days'));
$upcoming_notes_query = $conn->query("SELECT note_date, note, note_image FROM calendar_notes 
                                     WHERE user_id = $user_id 
                                     AND note_date BETWEEN '$today' AND '$week_from_now'
                                     ORDER BY note_date ASC");
$upcoming_notes = [];
while ($row = $upcoming_notes_query->fetch_assoc()) {
    $upcoming_notes[] = $row;
}

$with_images = 0;
foreach ($notes as $note) {
    if (!empty($note['note_image'])) $with_images++;
}

// Output HTML for dashboard content (can be improved to JSON + template if needed)
?>

<div class="dashboard-grid">
    <div class="dashboard-card">
        <h3><i class="fas fa-sticky-note"></i> Upcoming Notes</h3>
        <div class="notes-container">
            <?php if (count($upcoming_notes) > 0): ?>
                <?php foreach ($upcoming_notes as $note): ?>
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
                    <i class="far fa-calendar-times"></i>
                    <p>No upcoming notes for the next 7 days</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="dashboard-card">
        <h3><i class="fas fa-chart-pie"></i> Summary</h3>

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
                <p><?= count($upcoming_notes) ?></p>
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-icon green-bg">
                <i class="fas fa-image"></i>
            </div>
            <div>
                <h4>With Images</h4>
                <p><?= $with_images ?></p>
            </div>
        </div>
    </div>

    <div class="dashboard-card" style="grid-column: span 2;">
        <h3><i class="fas fa-history"></i> Recent Notes</h3>
        <div class="notes-container">
            <?php if (count($notes) > 0): ?>
                <?php 
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

<?php $conn->close(); ?>
