<?php 
include 'check_login.php';
include 'db.php'; 
include 'navbar.php'; 

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM duty_logs WHERE user_id = ? AND status = 1 ORDER BY id DESC LIMIT 1");
$stmt->execute([$user_id]);
$current_duty = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>POLICE ALL STAR PD — DUTY TERMINAL</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .duty-wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding-bottom: 40px;
        }

        /* Tactical Status Card */
        .status-panel {
            background: linear-gradient(145deg, var(--surface), oklch(from var(--primary) 0.12 0.05 250));
            border: 1px solid var(--border-mid);
            border-radius: 24px;
            padding: 48px;
            text-align: center;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .status-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            background: <?= $current_duty ? 'var(--success)' : 'var(--danger)' ?>;
            box-shadow: 0 0 20px <?= $current_duty ? 'var(--success)' : 'var(--danger)' ?>;
        }

        .status-badge-large {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 28px;
            border-radius: 40px;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 32px;
            background: <?= $current_duty ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' ?>;
            color: <?= $current_duty ? 'var(--success)' : 'var(--danger)' ?>;
            border: 1px solid <?= $current_duty ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)' ?>;
        }

        .timer-tactical {
            font-family: 'Rajdhani', sans-serif;
            font-size: 5rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
            margin-bottom: 8px;
            letter-spacing: -2px;
            font-variant-numeric: tabular-nums;
        }

        .timer-label {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 1px;
            margin-bottom: 48px;
            text-transform: uppercase;
        }

        .btn-action-duty {
            width: 100%;
            max-width: 320px;
            padding: 20px;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 1px;
        }

        .btn-start {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 8px 24px rgba(0, 102, 255, 0.3);
        }

        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0, 102, 255, 0.4);
            filter: brightness(1.1);
        }

        .btn-stop {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        .btn-stop:hover {
            background: var(--danger);
            color: #fff;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
        }

        /* Active Personnel List */
        .personnel-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 24px;
        }

        .personnel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .personnel-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .active-count {
            background: var(--primary-dim);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
        }

        .personnel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 16px;
        }

        .personnel-item {
            background: var(--surface-2);
            border: 1px solid var(--border);
            padding: 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .avatar-mini {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--primary-dim);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .personnel-info {
            flex: 1;
        }

        .personnel-name {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .personnel-time {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .pulse-live {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            display: inline-block;
            margin-right: 4px;
            box-shadow: 0 0 10px var(--success);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="duty-wrapper">
        
        <div class="status-panel">
            <div class="status-badge-large">
                <?php if($current_duty): ?>
                    <span class="pulse-live" style="width:10px; height:10px;"></span>
                    ปฏิบัติหน้าที่อยู่ในขณะนี้
                <?php else: ?>
                    <i class="fas fa-power-off"></i>
                    เลิกปฏิบัติหน้าที่
                <?php endif; ?>
            </div>

            <div class="timer-tactical" id="timer">00:00:00</div>
            <div class="timer-label">ระยะเวลาการปฏิบัติงานต่อเนื่อง</div>

            <form id="duty-form" action="action_duty.php" method="POST">
                <input type="hidden" name="client_time" id="client_time">
                <input type="hidden" name="action" id="duty-action">
                
                <?php if(!$current_duty): ?>
                    <button type="button" onclick="submitDuty('start')" class="btn-action-duty btn-start">
                        <i class="fas fa-play"></i> เริ่มเข้าเวรใหม่
                    </button>
                <?php else: ?>
                    <button type="button" onclick="submitDuty('stop')" class="btn-action-duty btn-stop">
                        <i class="fas fa-stop"></i> จบการปฏิบัติหน้าที่
                    </button>
                <?php endif; ?>
            </form>
        </div>

        <div class="personnel-card">
            <?php
                $stmt = $conn->query("SELECT * FROM duty_logs WHERE status = 1");
                $active = $stmt->fetchAll();
            ?>
            <div class="personnel-header">
                <div class="personnel-title">
                    <i class="fas fa-users-cog"></i> เจ้าหน้าที่กำลังปฏิบัติงาน
                </div>
                <div class="active-count"><?= count($active) ?> นาย</div>
            </div>

            <div class="personnel-grid">
                <?php if(empty($active)): ?>
                    <div style="grid-column: 1/-1; text-align: center; padding: 32px; color: var(--text-muted); font-size: 14px;">
                        <i class="fas fa-ghost" style="font-size: 2rem; margin-bottom: 12px; display: block; opacity: 0.5;"></i>
                        ไม่มีเจ้าหน้าที่กำลังปฏิบัติงานในขณะนี้
                    </div>
                <?php else: ?>
                    <?php foreach($active as $row): ?>
                        <div class="personnel-item">
                            <div class="avatar-mini">👮</div>
                            <div class="personnel-info">
                                <div class="personnel-name"><?= htmlspecialchars($row['user_name']) ?></div>
                                <?php
                                    $cts = $row['client_timestamp'] ?? null;
                                ?>
                                <div class="personnel-time duty-start-time" 
                                     data-cts="<?= $cts ? intval($cts) : '' ?>"
                                     data-fallback="<?= htmlspecialchars($row['start_time']) ?>">
                                    เริ่มเมื่อ: --:--
                                </div>
                            </div>
                            <span class="pulse-live"></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    <?php if($current_duty): ?>
    const startTimestamp = <?= 
        isset($current_duty['client_timestamp']) && $current_duty['client_timestamp']
            ? intval($current_duty['client_timestamp'])
            : strtotime($current_duty['start_time']) * 1000
    ?>;
    <?php else: ?>
    const startTimestamp = 0;
    <?php endif; ?>

    function updateTimer() {
        if (!startTimestamp) {
            document.getElementById('timer').innerText = "00:00:00";
            return;
        }
        const diff = Date.now() - startTimestamp;
        if (diff < 0) return;
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        document.getElementById('timer').innerText =
            h.toString().padStart(2,'0') + ':' +
            m.toString().padStart(2,'0') + ':' +
            s.toString().padStart(2,'0');
    }
    setInterval(updateTimer, 1000);
    updateTimer();

    document.querySelectorAll('.duty-start-time').forEach(function(td) {
        const cts = td.getAttribute('data-cts');
        let d;
        if (cts) {
            d = new Date(parseInt(cts));
        } else {
            const raw = td.getAttribute('data-fallback');
            d = new Date(raw.replace(' ', 'T'));
        }
        if (!isNaN(d.getTime())) {
            const h = d.getHours().toString().padStart(2, '0');
            const m = d.getMinutes().toString().padStart(2, '0');
            td.textContent = 'เริ่มเมื่อ: ' + h + ':' + m + ' น.';
        }
    });

    function submitDuty(action) {
        document.getElementById('client_time').value = Date.now();
        document.getElementById('duty-action').value = action;
        document.getElementById('duty-form').submit();
    }
</script>
    <footer style="text-align:center; padding:28px 0 20px; color:#4a5568; font-size:12px; letter-spacing:0.5px; font-family:'Noto Sans Thai',sans-serif;">
    © Police All Star. by Four Fxpl .achikp_43035
</footer>
</body>
</html>
