<?php
/**
 * Job Listings Page - TechHive Company
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Created: October 2025
 * Purpose: Display career opportunities dynamically from database
 */

// Include database connection
require_once 'settings.php';

// Set page-specific variables
$page_title = "Career Opportunities | TechHive";
$page_description = "Explore exciting career opportunities at TechHive - Current openings for Front-End Developer and Senior UX Designer positions";

// Include header
include 'header.inc';

// Include navigation
include 'nav.inc';

// Fetch jobs from database
$sql = "SELECT * FROM jobs WHERE status = 'Active' ORDER BY posted_date DESC";
$result = mysqli_query($conn, $sql);
?>

<main>
    <h1>Career Opportunities at TechHive</h1>
    
    <section id="job-listings">
        <h2>Current Open Positions</h2>
        
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($job = mysqli_fetch_assoc($result)): ?>
                <article class="job-posting">
                    <header>
                        <h3><?php echo htmlspecialchars($job['job_title']); ?></h3>
                        <p class="job-reference">Reference: <strong><?php echo htmlspecialchars($job['job_reference']); ?></strong></p>
                    </header>
                    
                    <section class="job-overview">
                        <h4>Position Overview</h4>
                        <p><?php echo nl2br(htmlspecialchars($job['job_overview'])); ?></p>
                    </section>
                    
                    <section class="job-details">
                        <h4>Position Details</h4>
                        <dl>
                            <dt>Salary Range:</dt>
                            <dd><?php echo htmlspecialchars($job['salary_range']); ?> per annum</dd>
                            <dt>Reports to:</dt>
                            <dd><?php echo htmlspecialchars($job['reports_to']); ?></dd>
                            <dt>Location:</dt>
                            <dd><?php echo htmlspecialchars($job['location']); ?></dd>
                            <dt>Employment Type:</dt>
                            <dd><?php echo htmlspecialchars($job['job_type']); ?></dd>
                        </dl>
                    </section>
                    
                    <section class="key-responsibilities">
                        <h4>Key Responsibilities</h4>
                        <ul>
                            <?php 
                            $responsibilities = explode("\n", $job['key_responsibilities']);
                            foreach($responsibilities as $resp):
                                $resp = trim($resp, '•');
                                if(!empty(trim($resp))):
                            ?>
                                <li><?php echo htmlspecialchars(trim($resp)); ?></li>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </ul>
                    </section>
                    
                    <section class="qualifications">
                        <h4>Required Qualifications & Skills</h4>
                        
                        <section class="essential-skills">
                            <h5>Essential Requirements</h5>
                            <ul>
                                <?php 
                                $essentials = explode("\n", $job['essential_requirements']);
                                foreach($essentials as $req):
                                    $req = trim($req, '•');
                                    if(!empty(trim($req))):
                                ?>
                                    <li><?php echo htmlspecialchars(trim($req)); ?></li>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </ul>
                        </section>
                        
                        <section class="preferable-skills">
                            <h5>Preferable Skills</h5>
                            <ol>
                                <?php 
                                $preferable = explode("\n", $job['preferable_skills']);
                                foreach($preferable as $skill):
                                    $skill = trim($skill, '•');
                                    if(!empty(trim($skill))):
                                ?>
                                    <li><?php echo htmlspecialchars(trim($skill)); ?></li>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </ol>
                        </section>
                    </section>
                    
                    <div class="job-actions">
                        <a href="apply.php?ref=<?php echo urlencode($job['job_reference']); ?>" class="btn-primary">Apply for this Position</a>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-jobs">No current job openings available. Please check back later.</p>
        <?php endif; ?>
    </section>
    
    <aside id="career-benefits">
        <h2>Why Join TechHive?</h2>
        <p>At TechHive, we value our employees and offer comprehensive benefits:</p>
        <ul>
            <li>Flexible working arrangements</li>
            <li>Professional development budget</li>
            <li>Health and wellness programs</li>
            <li>Competitive compensation packages</li>
            <li>Inclusive and diverse workplace</li>
        </ul>
        <p>We're committed to creating an environment where talented people can do their best work.</p>
    </aside>
</main>

<?php
// Close database connection
mysqli_close($conn);

// Include footer
include 'footer.inc';
?>
