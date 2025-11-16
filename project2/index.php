<?php
/**
 * Home Page - TechHive Company
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Created: October 2025
 * Purpose: Landing page with hero section and call-to-action buttons
 */

// Start session
session_start();

// Set page-specific variables
$page_title = "TechHive - Building Tomorrow's Technology Today";
$page_description = "TechHive is a leading IT solutions company offering exciting career opportunities in web development, UX design, and technology innovation.";
$body_class = "index-page";

// Include header
include 'header.inc';

// Include navigation
include 'nav.inc';
?>

<main>
    <section class="hero-section">
        <div class="container">
            <h1>Build Your Future with TechHive</h1>
            <p>Be a Part of our IT company</p>
        </div>
        <div class="hero-features">
            <div class="feature">
                <h2>BEYOND LIMITS</h2>
                <p>Where Top-Tier Coders Meet and Innovate</p>
            </div>
            <div class="feature">
                <h2>Innovations</h2>
                <p>We lead the world in cutting-edge technology solutions</p>
            </div>
        </div>
        <div class="cta-buttons">
            <a href="apply.php" class="cta-button">Get Started</a>
            <a href="about.php" class="cta-button secondary">Learn More</a>
        </div>
    </section>
</main>

<?php
// Include footer
include 'footer.inc';
?>
