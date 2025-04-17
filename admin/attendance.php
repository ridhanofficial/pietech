<?php
/**
 * Admin Attendance Management
 * 
 * This page allows administrators to track and manage event attendance.
 */

// Include initialization file
require_once '../includes/init.php';

// Require admin privileges
requireAdmin();

// Set page title
$pageTitle = 'Manage Attendance';

// Handle attendance marking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_attendance'])) {
    $registrationId = $_POST['registration_id'];
    $attended = isset($_POST['attended']) ? 1 : 0;
    
    if ($registrationModel->markAttendance($registrationId, $attended)) {
        setFlashMessage('Attendance marked successfully.', 'success');
    } else {
        setFlashMessage('Failed to mark attendance.', 'danger');
    }
    
    // Redirect back to the same page with filters preserved
    $redirectUrl = 'attendance.php';
    if (isset($_GET['event_id'])) {
        $redirectUrl .= '?event_id=' . $_GET['event_id'];
    }
    
    redirect($redirectUrl);
}

// Get events for the dropdown
$events = $eventModel->getAll('date', 'desc');

// Filter registrations by event if specified
$registrations = [];
$selectedEvent = null;

if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $eventId = $_GET['event_id'];
    $selectedEvent = $eventModel->getById($eventId);
    
    if ($selectedEvent) {
        // Get approved registrations for this event
        $registrations = $registrationModel->getApprovedByEventId($eventId);
    } else {
        setFlashMessage('Event not found.', 'danger');
        redirect('attendance.php');
    }
}

// Include header
include_once '../app/views/layouts/header.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Attendance Management</h1>
        <div>
            <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
    
    <!-- Event Selection -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Select Event</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="attendance.php" class="row g-3">
                <div class="col-md-8">
                    <select name="event_id" class="form-select" required>
                        <option value="" disabled <?php echo !isset($_GET['event_id']) ? 'selected' : ''; ?>>Select an event to manage attendance</option>
                        <?php foreach ($events as $event): ?>
                            <option value="<?php echo $event['id']; ?>" <?php echo (isset($_GET['event_id']) && $_GET['event_id'] == $event['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($event['title']); ?> - <?php echo formatDate($event['date']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <?php if ($selectedEvent): ?>
        <!-- Event Details -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo htmlspecialchars($selectedEvent['title']); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Date:</strong> <?php echo formatDate($selectedEvent['date']); ?></p>
                        <p><strong>Time:</strong> <?php echo formatTime($selectedEvent['time']); ?></p>
                        <p><strong>Venue:</strong> <?php echo htmlspecialchars($selectedEvent['venue']); ?> (Room: <?php echo htmlspecialchars($selectedEvent['room_no']); ?>)</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($selectedEvent['category']); ?></p>
                        <p><strong>Registration Type:</strong> <?php echo $selectedEvent['team_based'] ? 'Team-based' : 'Individual'; ?></p>
                        <p><strong>Maximum Participants:</strong> <?php echo $selectedEvent['max_participants']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attendance Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Attendance List</h5>
                    </div>
                    <div class="col-auto">
                        <div class="input-group">
                            <input type="text" id="attendanceSearch" class="form-control" placeholder="Search participants...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (count($registrations) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Participant</th>
                                    <th scope="col">Registration Type</th>
                                    <th scope="col">Registration Date</th>
                                    <th scope="col">Attendance</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registrations as $index => $registration): ?>
                                    <?php 
                                        // Get user details
                                        $user = $userModel->findById($registration['user_id']);
                                        
                                        // Skip if user not found (shouldn't happen, but just in case)
                                        if (!$user) continue;
                                        
                                        // Determine registration type
                                        $registrationType = $selectedEvent['team_based'] ? 
                                            ($registration['team_name'] ? 'Team: ' . htmlspecialchars($registration['team_name']) : 'Individual') : 
                                            'Individual';
                                    ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td>
                                            <div><?php echo htmlspecialchars($user['name']); ?></div>
                                            <div class="small text-muted"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </td>
                                        <td>
                                            <?php echo $registrationType; ?>
                                            <?php if ($registration['members']): ?>
                                                <button class="btn btn-sm btn-link p-0 ms-1" type="button" data-bs-toggle="modal" 
                                                        data-bs-target="#teamModal<?php echo $registration['id']; ?>">
                                                    <i class="fas fa-users"></i>
                                                </button>
                                                
                                                <!-- Team Members Modal -->
                                                <div class="modal fade" id="teamModal<?php echo $registration['id']; ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Team Members</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <h6 class="mb-3"><?php echo htmlspecialchars($registration['team_name']); ?></h6>
                                                                <ul class="list-group">
                                                                    <?php 
                                                                        $members = json_decode($registration['members'], true);
                                                                        if (is_array($members)) {
                                                                            foreach ($members as $member) {
                                                                                echo '<li class="list-group-item">' . 
                                                                                    htmlspecialchars($member['name']) . 
                                                                                    ' <small class="text-muted">(' . 
                                                                                    htmlspecialchars($member['reg_no']) . ')</small></li>';
                                                                            }
                                                                        }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo formatDate($registration['created_at']); ?></td>
                                        <td>
                                            <?php if ($registration['attended'] == 1): ?>
                                                <span class="badge bg-success">Present</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Absent</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <form method="POST" action="attendance.php?event_id=<?php echo $selectedEvent['id']; ?>" class="d-inline-block">
                                                <input type="hidden" name="registration_id" value="<?php echo $registration['id']; ?>">
                                                <div class="form-check form-switch d-inline-block me-2">
                                                    <input class="form-check-input" type="checkbox" id="attended<?php echo $registration['id']; ?>" 
                                                           name="attended" <?php echo $registration['attended'] == 1 ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="attended<?php echo $registration['id']; ?>"></label>
                                                </div>
                                                <button type="submit" name="mark_attendance" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-save"></i> Save
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-center">
                        <p class="text-muted mb-0">No approved registrations found for this event.</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (count($registrations) > 0): ?>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Total Registrations:</strong> <?php echo count($registrations); ?>
                        </div>
                        <div>
                            <?php 
                                $presentCount = 0;
                                foreach ($registrations as $reg) {
                                    if ($reg['attended'] == 1) $presentCount++;
                                }
                                $attendanceRate = count($registrations) > 0 ? round(($presentCount / count($registrations)) * 100) : 0;
                            ?>
                            <strong>Attendance Rate:</strong> 
                            <span class="badge bg-<?php echo $attendanceRate >= 70 ? 'success' : ($attendanceRate >= 40 ? 'warning' : 'danger'); ?>">
                                <?php echo $presentCount; ?> / <?php echo count($registrations); ?> (<?php echo $attendanceRate; ?>%)
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>Please select an event from the dropdown above to manage attendance.
        </div>
    <?php endif; ?>
</div>

<script>
    // Attendance search functionality
    document.getElementById('attendanceSearch')?.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            const participant = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const type = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            
            if (participant.includes(searchValue) || type.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<?php include_once '../app/views/layouts/footer.php'; ?>