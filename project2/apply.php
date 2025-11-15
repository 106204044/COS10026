<?php
/**
 * Job Application Form - TechHive Company
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Created: October 2025
 * Purpose: Online application form with HTML5 validation
 */

// Include database connection
require_once 'settings.php';

// Set page-specific variables
$page_title = "Job Application | TechHive";
$page_description = "Apply for a position at TechHive - Submit your job application online";

// Include header
include 'header.inc';

// Include navigation
include 'nav.inc';

// Fetch active jobs for dropdown
$jobs_sql = "SELECT job_reference, job_title FROM jobs WHERE status = 'Active' ORDER BY job_title";
$jobs_result = mysqli_query($conn, $jobs_sql);

// Check if a specific job reference was passed
$selected_job = isset($_GET['ref']) ? $_GET['ref'] : '';
?>

<main>
    <h1>Job Application</h1>
    <p id="notice">Complete the form below to apply for your desired position at TechHive.</p>

    <!-- Form with novalidate for testing server-side validation -->
    <form id="job-application" method="post" action="process_eoi.php" novalidate="novalidate">
        <!-- Personal Information Section -->
        <fieldset>
            <legend>Personal Information</legend>
            
            <div class="form-group">
                <label for="job-reference">Job Reference Number <span class="required">*</span></label>
                <select id="job-reference" name="job_reference" required>
                    <option value="" disabled <?php echo empty($selected_job) ? 'selected' : ''; ?>>Select a position</option>
                    <?php if ($jobs_result && mysqli_num_rows($jobs_result) > 0): ?>
                        <?php while($job = mysqli_fetch_assoc($jobs_result)): ?>
                            <option value="<?php echo htmlspecialchars($job['job_reference']); ?>" 
                                    <?php echo ($selected_job == $job['job_reference']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($job['job_reference'] . ' - ' . $job['job_title']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="TVFED01">TVFED01 - Front-End Developer</option>
                        <option value="TVUXD01">TVUXD01 - Senior UX Designer</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="first-name">First Name <span class="required">*</span></label>
                <input type="text" id="first-name" name="first_name" maxlength="20" 
                       pattern="[A-Za-z\s\-']+" title="Only alphabetical characters, hyphens, and apostrophes are allowed" 
                       required>
                <small>Maximum 20 alphabetic characters</small>
            </div>

            <div class="form-group">
                <label for="last-name">Last Name <span class="required">*</span></label>
                <input type="text" id="last-name" name="last_name" maxlength="20" 
                       pattern="[A-Za-z\s\-']+" title="Only alphabetical characters, hyphens, and apostrophes are allowed" 
                       required>
                <small>Maximum 20 alphabetic characters</small>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth <span class="required">*</span></label>
                <input type="text" id="dob" name="date_of_birth" 
                       pattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/\d{4}" 
                       placeholder="DD/MM/YYYY" title="Please enter date in DD/MM/YYYY format" 
                       required>
                <small>Format: DD/MM/YYYY</small>
            </div>

            <div class="form-group">
                <fieldset class="radio-group">
                    <legend>Gender <span class="required">*</span></legend>
                    <div class="radio-options">
                        <label>
                            <input type="radio" name="gender" value="male" required>
                            Male
                        </label>
                        <label>
                            <input type="radio" name="gender" value="female">
                            Female
                        </label>
                        <label>
                            <input type="radio" name="gender" value="non-binary">
                            Non-binary
                        </label>
                        <label>
                            <input type="radio" name="gender" value="prefer-not-to-say">
                            Prefer not to say
                        </label>
                    </div>
                </fieldset>
            </div>
        </fieldset>

        <!-- Address Information Section -->
        <fieldset>
            <legend>Address Information</legend>
            
            <div class="form-group">
                <label for="street-address">Street Address <span class="required">*</span></label>
                <input type="text" id="street-address" name="street_address" maxlength="40" required>
                <small>Maximum 40 characters</small>
            </div>

            <div class="form-group">
                <label for="suburb">Suburb/Town <span class="required">*</span></label>
                <input type="text" id="suburb" name="suburb" maxlength="40" required>
                <small>Maximum 40 characters</small>
            </div>

            <div class="form-group">
                <label for="state">State <span class="required">*</span></label>
                <select id="state" name="state" required>
                    <option value="" selected disabled>Select your state</option>
                    <option value="VIC">Victoria</option>
                    <option value="NSW">New South Wales</option>
                    <option value="QLD">Queensland</option>
                    <option value="NT">Northern Territory</option>
                    <option value="WA">Western Australia</option>
                    <option value="SA">South Australia</option>
                    <option value="TAS">Tasmania</option>
                    <option value="ACT">Australian Capital Territory</option>
                </select>
            </div>

            <div class="form-group">
                <label for="postcode">Postcode <span class="required">*</span></label>
                <input type="text" id="postcode" name="postcode" 
                       pattern="\d{4}" title="Postcode must be exactly 4 digits" 
                       required>
                <small>Exactly 4 digits</small>
            </div>
        </fieldset>

        <!-- Contact Information Section -->
        <fieldset>
            <legend>Contact Information</legend>
            
            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" 
                       pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" 
                       title="Please enter a valid email address" 
                       required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number <span class="required">*</span></label>
                <input type="tel" id="phone" name="phone" 
                       pattern="[\d\s]{8,12}" title="Phone number must be 8 to 12 digits" 
                       required>
                <small>8 to 12 digits, spaces allowed</small>
            </div>
        </fieldset>

        <!-- Skills and Qualifications Section -->
        <fieldset>
            <legend>Skills and Qualifications</legend>
            
            <div class="form-group">
                <fieldset class="checkbox-group">
                    <legend>Required Technical Skills <span class="required">*</span></legend>
                    <div class="checkbox-options">
                        <label>
                            <input type="checkbox" name="skills[]" value="html5" checked>
                            HTML5
                        </label>
                        <label>
                            <input type="checkbox" name="skills[]" value="css3">
                            CSS3
                        </label>
                        <label>
                            <input type="checkbox" name="skills[]" value="javascript">
                            JavaScript
                        </label>
                        <label>
                            <input type="checkbox" name="skills[]" value="react">
                            React.js
                        </label>
                        <label>
                            <input type="checkbox" name="skills[]" value="vue">
                            Vue.js
                        </label>
                        <label>
                            <input type="checkbox" name="skills[]" value="typescript">
                            TypeScript
                        </label>
                        <label>
                            <input type="checkbox" name="skills[]" value="git">
                            Git Version Control
                        </label>
                        <label>
                            <input type="checkbox" name="skills[]" value="responsive">
                            Responsive Design
                        </label>
                    </div>
                </fieldset>
            </div>

            <div class="form-group">
                <label for="other-skills">Other Skills and Experience</label>
                <textarea id="other-skills" name="other_skills" 
                          rows="5" 
                          placeholder="Please describe any other relevant skills, experience, or qualifications..."></textarea>
                <small>Optional: Include any additional information about your skills and experience</small>
            </div>
        </fieldset>

        <!-- Submit Button -->
        <div class="form-actions">
            <button type="submit" class="btn-primary">Apply</button>
            <button type="reset" class="btn-secondary">Reset Form</button>
        </div>
    </form>
</main>

<?php
// Close database connection
mysqli_close($conn);

// Include footer
include 'footer.inc';
?>
