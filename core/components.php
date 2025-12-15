<?php
/**
 * Reusable UI Components
 * adhere to KISS principle (Keep It Simple, Stupid)
 */

/**
 * Renders a Bootstrap badge for Appointment Status
 * @param string $status
 */
function renderStatusBadge($status)
{
    $colors = [
        'pending' => 'bg-warning text-dark',
        'active' => 'bg-success',
        'finished' => 'bg-primary',
        'cancelled' => 'bg-danger'
    ];
    $labels = [
        'pending' => 'Menunggu',
        'active' => 'Aktif',
        'finished' => 'Selesai',
        'cancelled' => 'Dibatalkan'
    ];

    $colorClass = $colors[$status] ?? 'bg-secondary';
    $label = $labels[$status] ?? ucfirst($status);

    echo "<span class='badge {$colorClass} rounded-pill fw-normal px-3 py-2'>{$label}</span>";
}

/**
 * Renders a simple badge for Service Type
 * @param string $service
 */
function renderServiceBadge($service)
{
    if (empty($service))
        $service = 'Daycare';
    echo "<span class='badge bg-light text-dark border'>" . htmlspecialchars($service) . "</span>";
}

/**
 * Renders a dismissible Bootstrap Alert
 * @param string $message
 * @param string $type (success, danger, warning, info)
 */
function renderAlert($message, $type = 'info')
{
    if (empty($message))
        return;
    ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php
}
?>
