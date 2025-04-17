<?php
/**
 * Admin Dashboard
 * 
 * This page displays the admin dashboard with summary statistics and quick actions.
 */

// Include initialization file
require_once '../includes/init.php';

// Require admin privileges
requireAdmin();

// Set page title
$pageTitle = 'Admin Dashboard';

// Get statistics for the dashboard
$totalUsers = count($userModel->getAll());
$totalEvents = count($eventModel->getAll());
$totalRegistrations = count($registrationModel->getAll());

// Get upcoming events
$upcomingEvents = $eventModel->getUpcoming();

// Get recent registrations
$recentRegistrations = $registrationModel->getRecent(5);

// Include header
include_once '../app/views/layouts/header.php';
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Admin Dashboard</h1>
            <p class="lead">Welcome to the PIETECH Events Platform admin panel</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="../index.php" class="btn btn-outline-primary">
                <i class="fas fa-home me-2"></i>Return to Site
            </a>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Users</h6>
                            <h2 class="mt-2 mb-0"><?php echo $totalUsers; ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="users.php" class="text-white text-decoration-none small">
                        Manage Users <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Events</h6>
                            <h2 class="mt-2 mb-0"><?php echo $totalEvents; ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="events.php" class="text-white text-decoration-none small">
                        Manage Events <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Registrations</h6>
                            <h2 class="mt-2 mb-0"><?php echo $totalRegistrations; ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-ticket-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="registrations.php" class="text-white text-decoration-none small">
                        View Registrations <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Upcoming Events</h6>
                            <h2 class="mt-2 mb-0"><?php echo count($upcomingEvents); ?></h2>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="events.php" class="text-white text-decoration-none small">
                        View Schedule <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="event_create.php" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2 w-100 py-3">
                                <i class="fas fa-plus-circle"></i>
                                <span>Create New Event</span>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="users.php" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100 py-3">
                                <i class="fas fa-user-cog"></i>
                                <span>Manage Users</span>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="registrations.php" class="btn btn-outline-info d-flex align-items-center justify-content-center gap-2 w-100 py-3">
                                <i class="fas fa-clipboard-list"></i>
                                <span>View Registrations</span>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="attendance.php" class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2 w-100 py-3">
                                <i class="fas fa-check-square"></i>
                                <span>Manage Attendance</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="row">
        <!-- Recent Events -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Events</h5>
                    <a href="events.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <?php if (count($upcomingEvents) > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($upcomingEvents, 0, 5) as $event): ?>
                                <a href="../event_details.php?id=<?php echo $event['id']; ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                        <small><?php echo formatDate($event['date']); ?></small>
                                    </div>
                                    <p class="mb-1 text-muted small"><?php echo htmlspecialchars(substr($event['description'], 0, 100)) . '...'; ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><?php echo htmlspecialchars($event['venue']); ?></small>
                                        <span class="badge bg-primary rounded-pill"><?php echo formatTime($event['time']); ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="p-4 text-center">
                            <p class="text-muted mb-0">No upcoming events found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Registrations -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Registrations</h5>
                    <a href="registrations.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <?php if (count($recentRegistrations) > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentRegistrations as $registration): ?>
                                <a href="registrations.php?id=<?php echo $registration['id']; ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($registration['user_name']); ?></h6>
                                        <small><?php echo formatDate($registration['created_at']); ?></small>
                                    </div>
                                    <p class="mb-1">Registered for: <strong><?php echo htmlspecialchars($registration['event_title']); ?></strong></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><?php echo $registration['team_name'] ? 'Team: ' . htmlspecialchars($registration['team_name']) : 'Individual Registration'; ?></small>
                                        <span class="badge <?php echo getStatusBadgeClass($registration['status']); ?> rounded-pill">
                                            <?php echo ucfirst($registration['status']); ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="p-4 text-center">
                            <p class="text-muted mb-0">No recent registrations found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Get the appropriate badge class for a registration status
 */
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'pending':
            return 'bg-warning text-dark';
        case 'approved':
            return 'bg-success';
        case 'rejected':
            return 'bg-danger';
        case 'cancelled':
            return 'bg-secondary';
        default:
            return 'bg-info';
    }
}
?>

<?php include_once '../app/views/layouts/footer.php'; ?>