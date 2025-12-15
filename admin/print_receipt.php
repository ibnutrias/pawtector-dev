<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 0) {
    header("Location: " . url('masuk'));
    exit;
}

require_once "../core/koneksi.php";

if (!isset($_GET['id'])) {
    die("Appointment ID missing.");
}

$appointId = intval($_GET['id']);

// Fetch Appointment Details
$sql = "
    SELECT 
        a.id, a.appointment_date, a.appointment_time, a.status, a.notes, a.service,
        u.fullname as client_name, u.email as client_email,
        p.nama as pet_name, p.ras as pet_breed
    FROM appointments a
    JOIN users u ON a.user_id = u.id
    JOIN pets p ON a.pet_id = p.id
    WHERE a.id = ?
";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $appointId);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("Booking not found.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receipt #<?= $booking['id'] ?></title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            color: #333;
        }

        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #444;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #777;
        }

        .divider {
            border-bottom: 2px dashed #eee;
            margin: 20px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            text-align: right;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #333;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #999;
        }

        @media print {
            body {
                padding: 0;
            }

            .receipt-container {
                border: none;
                padding: 0;
                width: 100%;
                max-width: none;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="receipt-container">
        <div class="header">
            <img src="<?= url('assets/images/pawpark-logo.svg') ?>" width="100" />
            <p>Taman Kucing Ijen</p>
            <p>Kel. Paw Paw, Kec. Lowokmeong</p>
            <p>Kota Malang, Jawa Timur, Indonesia</p>
        </div>

        <div class="divider"></div>

        <div class="info-row">
            <span class="info-label">ID Resi</span>
            <span class="info-value">#<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak</span>
            <span class="info-value"><?= date('d M Y H:i') ?></span>
        </div>

        <div class="divider"></div>

        <div class="info-row">
            <span class="info-label">Nama Klien</span>
            <span class="info-value"><?= htmlspecialchars($booking['client_name']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Nama Heiwan</span>
            <span class="info-value"><?= htmlspecialchars($booking['pet_name']) ?>
                (<?= htmlspecialchars($booking['pet_breed']) ?>)</span>
        </div>

        <div class="divider"></div>

        <div class="info-row">
            <span class="info-label">Tanggal Booking</span>
            <span class="info-value"><?= date('d M Y', strtotime($booking['appointment_date'])) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Jam</span>
            <span class="info-value"><?= date('h:i A', strtotime($booking['appointment_time'])) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Layanan</span>
            <span class="info-value"><?= htmlspecialchars($booking['service'] ?? 'Daycare') ?></span>
        </div>

        <div class="divider"></div>

        <div class="info-row" style="align-items: center;">
            <span class="info-label">Status</span>
            <span class="info-value">
                <span
                    class="status-badge"><?= strtoupper($booking['status']) == 'FINISHED' ? 'SELESAI' : strtoupper($booking['status']) ?></span>
            </span>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>Terima kasih telah mempercayakan hewan kesayangan Anda pada PawPark!</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;" class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; font-size: 16px;">Cetak
            Resi</button>
        <br><br>
        <a href="bookings.php" style="color: #666; text-decoration: none;">&larr; Kembali ke Booking</a>
    </div>

</body>

</html>