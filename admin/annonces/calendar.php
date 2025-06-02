<?php
require_once __DIR__.'db.php';
require_once __DIR__.'auth_check.php';

$adId = $_GET['id'] ?? null;
if (!$adId) {
    header('Location: list.php');
    exit;
}

$pdo = Database::getInstance();
$annonce = $pdo->prepare("SELECT * FROM annonces WHERE id = ?")->execute([$adId])->fetch();
$reservations = $pdo->prepare("
    SELECT * FROM reservations 
    WHERE ad_id = ? AND status = 'confirmed'
    ORDER BY start_date
")->execute([$adId])->fetchAll();
?>

<div class="calendar-management">
    <h2>Calendrier pour: <?= htmlspecialchars($annonce['titre']) ?></h2>
    
    <div id="calendar"></div>
    
    <div class="reservation-dates">
        <h3>Réservations confirmées</h3>
        <ul>
            <?php foreach ($reservations as $res): ?>
            <li>
                <?= date('d/m/Y', strtotime($res['start_date'])) ?> - 
                <?= date('d/m/Y', strtotime($res['end_date'])) ?>
                (<?= $res['username'] ?? 'Client' ?>)
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: [
            <?php foreach ($reservations as $res): ?>
            {
                title: 'Réservé',
                start: '<?= $res['start_date'] ?>',
                end: '<?= date('Y-m-d', strtotime($res['end_date'] . ' +1 day')) ?>',
                color: '#ff9f89'
            },
            <?php endforeach; ?>
        ]
    });
    calendar.render();
});
</script>