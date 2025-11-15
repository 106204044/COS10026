<?php
/**
 * Manager Dashboard - TechHive Company
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Created: October 2025
 * Purpose: HR Manager dashboard for managing job applications
 */

session_start();

// Check if manager is logged in
if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) {
    header("Location: manager_login.php");
    exit();
}

require_once 'settings.php';

$message = '';
$error = '';

// Handle actions (delete, update status)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete_by_job':
                $job_ref = mysqli_real_escape_string($conn, $_POST['job_reference']);
                $delete_sql = "DELETE FROM eoi WHERE job_reference = ?";
                $stmt = mysqli_prepare($conn, $delete_sql);
                mysqli_stmt_bind_param($stmt, "s", $job_ref);
                if (mysqli_stmt_execute($stmt)) {
                    $message = "All applications for job reference $job_ref have been deleted.";
                } else {
                    $error = "Failed to delete applications.";
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'delete_single':
                $eoi_id = intval($_POST['eoi_id']);
                $delete_sql = "DELETE FROM eoi WHERE EOInumber = ?";
                $stmt = mysqli_prepare($conn, $delete_sql);
                mysqli_stmt_bind_param($stmt, "i", $eoi_id);
                if (mysqli_stmt_execute($stmt)) {
                    $message = "Application #$eoi_id has been deleted.";
                } else {
                    $error = "Failed to delete application.";
                }
                mysqli_stmt_close($stmt);
                break;
                
            case 'update_status':
                $eoi_id = intval($_POST['eoi_id']);
                $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
                if (in_array($new_status, ['New', 'Current', 'Final'])) {
                    $update_sql = "UPDATE eoi SET status = ? WHERE EOInumber = ?";
                    $stmt = mysqli_prepare($conn, $update_sql);
                    mysqli_stmt_bind_param($stmt, "si", $new_status, $eoi_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "Status updated for application #$eoi_id.";
                    } else {
                        $error = "Failed to update status.";
                    }
                    mysqli_stmt_close($stmt);
                }
                break;
        }
    }
}

// Build query based on filters
$where_conditions = [];
$params = [];
$types = "";

if (isset($_GET['search_type']) && !empty($_GET['search_value'])) {
    $search_type = $_GET['search_type'];
    $search_value = $_GET['search_value'];
    
    switch ($search_type) {
        case 'job_reference':
            $where_conditions[] = "job_reference = ?";
            $params[] = $search_value;
            $types .= "s";
            break;
        case 'first_name':
            $where_conditions[] = "first_name LIKE ?";
            $params[] = "%$search_value%";
            $types .= "s";
            break;
        case 'last_name':
            $where_conditions[] = "last_name LIKE ?";
            $params[] = "%$search_value%";
            $types .= "s";
            break;
        case 'both_names':
            $where_conditions[] = "(first_name LIKE ? OR last_name LIKE ?)";
            $params[] = "%$search_value%";
            $params[] = "%$search_value%";
            $types .= "ss";
            break;
    }
}

// Sorting
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'submitted_date';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'ASC' ? 'ASC' : 'DESC';
$valid_sort_fields = ['EOInumber', 'job_reference', 'first_name', 'last_name', 'submitted_date', 'status'];
if (!in_array($sort_by, $valid_sort_fields)) {
    $sort_by = 'submitted_date';
}

// Build final query
$sql = "SELECT * FROM eoi";
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}
$sql .= " ORDER BY $sort_by $sort_order";

// Execute query
if (!empty($params)) {
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, $sql);
}

// Get job references for dropdown
$jobs_sql = "SELECT DISTINCT job_reference FROM eoi ORDER BY job_reference";
$jobs_result = mysqli_query($conn, $jobs_sql);

// Set page variables
$page_title = "Manage Applications | TechHive";
$page_description = "Manage job applications and EOI submissions";

include 'header.inc';
include 'nav.inc';
?>

<main>
    <h1>Manage Applications</h1>
    
    <div class="manager-info">
        <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['manager_name'] ?? $_SESSION['manager_username']); ?></strong> 
        | <a href="manager_logout.php">Logout</a></p>
    </div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <!-- Search and Filter Section -->
    <section class="filters">
        <h2>Search Applications</h2>
        <form method="get" action="" class="filter-form">
            <div class="filter-row">
                <div class="form-group">
                    <label for="search_type">Search by:</label>
                    <select name="search_type" id="search_type">
                        <option value="">All Applications</option>
                        <option value="job_reference" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'job_reference') ? 'selected' : ''; ?>>Job Reference</option>
                        <option value="first_name" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'first_name') ? 'selected' : ''; ?>>First Name</option>
                        <option value="last_name" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'last_name') ? 'selected' : ''; ?>>Last Name</option>
                        <option value="both_names" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'both_names') ? 'selected' : ''; ?>>First or Last Name</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="search_value">Search term:</label>
                    <input type="text" name="search_value" id="search_value" 
                           value="<?php echo isset($_GET['search_value']) ? htmlspecialchars($_GET['search_value']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="sort_by">Sort by:</label>
                    <select name="sort_by" id="sort_by">
                        <option value="submitted_date" <?php echo ($sort_by == 'submitted_date') ? 'selected' : ''; ?>>Date Submitted</option>
                        <option value="EOInumber" <?php echo ($sort_by == 'EOInumber') ? 'selected' : ''; ?>>EOI Number</option>
                        <option value="job_reference" <?php echo ($sort_by == 'job_reference') ? 'selected' : ''; ?>>Job Reference</option>
                        <option value="last_name" <?php echo ($sort_by == 'last_name') ? 'selected' : ''; ?>>Last Name</option>
                        <option value="status" <?php echo ($sort_by == 'status') ? 'selected' : ''; ?>>Status</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="sort_order">Order:</label>
                    <select name="sort_order" id="sort_order">
                        <option value="DESC" <?php echo ($sort_order == 'DESC') ? 'selected' : ''; ?>>Descending</option>
                        <option value="ASC" <?php echo ($sort_order == 'ASC') ? 'selected' : ''; ?>>Ascending</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-primary">Search</button>
                <a href="manage.php" class="btn-secondary">Clear Filters</a>
            </div>
        </form>
    </section>
    
    <!-- Bulk Actions -->
    <section class="bulk-actions">
        <h3>Bulk Actions</h3>
        <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete all applications for this job?');">
            <input type="hidden" name="action" value="delete_by_job">
            <label for="job_reference">Delete all applications for job:</label>
            <select name="job_reference" id="job_reference" required>
                <option value="">Select job reference</option>
                <?php 
                mysqli_data_seek($jobs_result, 0);
                while ($job = mysqli_fetch_assoc($jobs_result)): 
                ?>
                    <option value="<?php echo htmlspecialchars($job['job_reference']); ?>">
                        <?php echo htmlspecialchars($job['job_reference']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn-danger">Delete All</button>
        </form>
    </section>
    
    <!-- Applications Table -->
    <section class="applications-table">
        <h2>Applications (<?php echo mysqli_num_rows($result); ?> found)</h2>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>EOI #</th>
                            <th>Job Ref</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Skills</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['EOInumber']); ?></td>
                                <td><?php echo htmlspecialchars($row['job_reference']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td>
                                    <?php
                                    $skills = [];
                                    for ($i = 1; $i <= 8; $i++) {
                                        if (!empty($row['skill' . $i])) {
                                            $skills[] = $row['skill' . $i];
                                        }
                                    }
                                    echo htmlspecialchars(implode(', ', $skills));
                                    ?>
                                </td>
                                <td>
                                    <form method="post" action="" class="status-form">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="eoi_id" value="<?php echo $row['EOInumber']; ?>">
                                        <select name="new_status" onchange="this.form.submit()">
                                            <option value="New" <?php echo ($row['status'] == 'New') ? 'selected' : ''; ?>>New</option>
                                            <option value="Current" <?php echo ($row['status'] == 'Current') ? 'selected' : ''; ?>>Current</option>
                                            <option value="Final" <?php echo ($row['status'] == 'Final') ? 'selected' : ''; ?>>Final</option>
                                        </select>
                                    </form>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($row['submitted_date'])); ?></td>
                                <td>
                                    <form method="post" action="" style="display:inline;" onsubmit="return confirm('Delete this application?');">
                                        <input type="hidden" name="action" value="delete_single">
                                        <input type="hidden" name="eoi_id" value="<?php echo $row['EOInumber']; ?>">
                                        <button type="submit" class="btn-small btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No applications found matching your criteria.</p>
        <?php endif; ?>
    </section>
</main>

<?php
mysqli_close($conn);
include 'footer.inc';
?>
