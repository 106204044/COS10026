<?php
/**
 * Enhancements Page - TechHive Project Part 2
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Created: November 2025
 * Purpose: Document all enhancements implemented beyond the specified requirements
 */

include 'header.inc';
include 'nav.inc';
?>

    <main>
        <h1>Project Enhancements</h1>
        
        <p class="intro-text">
            This page documents the enhancements we have implemented beyond the specified requirements 
            for COS10026 Web Technology Project Part 2. Each enhancement is designed to improve security, 
            user experience, data integrity, and system robustness.
        </p>

        <section id="enhancements-list">
            
            <!-- Enhancement 1: Sorting Functionality -->
            <article class="enhancement-item">
                <h2>1. Dynamic Sorting on Manager Dashboard</h2>
                <p class="enhancement-type"><strong>Type:</strong> Database Query Optimization</p>
                <p class="enhancement-description">
                    <strong>Implementation:</strong> Added advanced sorting functionality to the manage.php page allowing HR managers 
                    to sort EOI records by multiple fields including EOInumber, first name, last name, email, phone, job reference, 
                    and status. The sorting preference is maintained across pagination using GET parameters.
                </p>
                <p class="enhancement-details">
                    <strong>Technical Details:</strong>
                    <ul>
                        <li>Implemented SELECT dropdown with multiple sort fields</li>
                        <li>Added ASC/DESC toggle for sort direction</li>
                        <li>Integrated with existing MySQL queries using ORDER BY clause</li>
                        <li>Maintained sorting state across page navigation</li>
                        <li>Enhanced with visual indicators showing current sort field</li>
                    </ul>
                </p>
                <p class="benefits">
                    <strong>Benefits:</strong> Managers can quickly locate specific applications, identify patterns in applicant pools, 
                    and efficiently manage large volumes of EOI records.
                </p>
            </article>

            <!-- Enhancement 2: Manager Registration with Validation -->
            <article class="enhancement-item">
                <h2>2. Secure Manager Registration System</h2>
                <p class="enhancement-type"><strong>Type:</strong> Authentication & Security</p>
                <p class="enhancement-description">
                    <strong>Implementation:</strong> Created a comprehensive manager registration page with robust server-side validation, 
                    password strength requirements, and unique username constraints. Registration data is stored securely in the 
                    managers table with hashed passwords.
                </p>
                <p class="enhancement-details">
                    <strong>Technical Details:</strong>
                    <ul>
                        <li>Password strength validation (minimum 8 characters, uppercase, lowercase, numbers, special characters)</li>
                        <li>Unique username check against existing database records</li>
                        <li>Password hashing using PHP's password_hash() function</li>
                        <li>Email format validation</li>
                        <li>Duplicate registration prevention</li>
                        <li>User-friendly error messages for validation failures</li>
                    </ul>
                </p>
                <p class="benefits">
                    <strong>Benefits:</strong> Ensures only authorized personnel can manage HR functions, prevents unauthorized access, 
                    and maintains audit trail of manager activities.
                </p>
            </article>

            <!-- Enhancement 3: Login Attempt Tracking -->
            <article class="enhancement-item">
                <h2>3. Failed Login Attempt Tracking & Account Lockout</h2>
                <p class="enhancement-type"><strong>Type:</strong> Security & Access Control</p>
                <p class="enhancement-description">
                    <strong>Implementation:</strong> Implemented a sophisticated security system that tracks failed login attempts and 
                    temporarily locks accounts after 3 consecutive failed attempts, preventing brute force attacks.
                </p>
                <p class="enhancement-details">
                    <strong>Technical Details:</strong>
                    <ul>
                        <li>Tracks failed login attempts in managers table with timestamp</li>
                        <li>Automatic account lockout after 3 failed attempts</li>
                        <li>30-minute lockout period with automatic unlock</li>
                        <li>Clear error messaging distinguishing between invalid credentials and locked accounts</li>
                        <li>Session validation to prevent session hijacking</li>
                        <li>Login attempt logging for security audits</li>
                    </ul>
                </p>
                <p class="benefits">
                    <strong>Benefits:</strong> Protects manager accounts from brute force attacks, maintains system security, 
                    and provides audit trail for suspicious activities.
                </p>
            </article>

            <!-- Enhancement 4: Conditional Skills Validation -->
            <article class="enhancement-item">
                <h2>4. Conditional Skills Validation</h2>
                <p class="enhancement-type"><strong>Type:</strong> Data Integrity & Server-Side Validation</p>
                <p class="enhancement-description">
                    <strong>Implementation:</strong> Enhanced process_eoi.php to validate that the "Other skills" text area is not empty 
                    when any technical skill checkbox is selected, ensuring data consistency and meaningful applicant information.
                </p>
                <p class="enhancement-details">
                    <strong>Technical Details:</strong>
                    <ul>
                        <li>Server-side validation of skills[] array</li>
                        <li>Cross-field validation between checkbox selections and textarea content</li>
                        <li>Sanitization of textarea input to prevent injection attacks</li>
                        <li>User-friendly error messaging when validation fails</li>
                        <li>Form repopulation with previously entered data on validation failure</li>
                    </ul>
                </p>
                <p class="benefits">
                    <strong>Benefits:</strong> Ensures HR managers receive complete applicant information, prevents incomplete 
                    submissions, and maintains database data quality.
                </p>
            </article>

            <!-- Enhancement 5: Advanced Search Functionality -->
            <article class="enhancement-item">
                <h2>5. Advanced Search & Filter Options</h2>
                <p class="enhancement-type"><strong>Type:</strong> User Experience & Query Optimization</p>
                <p class="enhancement-description">
                    <strong>Implementation:</strong> Extended manage.php with advanced search capabilities allowing managers to search 
                    by multiple criteria simultaneously, including combined first/last name searches with partial matching.
                </p>
                <p class="enhancement-details">
                    <strong>Technical Details:</strong>
                    <ul>
                        <li>Multi-criteria search with AND/OR logic</li>
                        <li>Partial string matching using SQL LIKE operators</li>
                        <li>Case-insensitive searches</li>
                        <li>Filter by status (New, Current, Final)</li>
                        <li>Filter by job reference number</li>
                        <li>Date range filtering for submission dates</li>
                        <li>Search result pagination</li>
                    </ul>
                </p>
                <p class="benefits">
                    <strong>Benefits:</strong> Managers can quickly find specific applicants or groups of applicants matching 
                    specific criteria, significantly reducing time spent searching records.
                </p>
            </article>

            <!-- Enhancement 6: Data Export Functionality -->
            <article class="enhancement-item">
                <h2>6. EOI Data Export to CSV</h2>
                <p class="enhancement-type"><strong>Type:</strong> Business Intelligence & Reporting</p>
                <p class="enhancement-description">
                    <strong>Implementation:</strong> Added functionality to export filtered EOI records to CSV format for external 
                    analysis, reporting, and data backup purposes.
                </p>
                <p class="enhancement-details">
                    <strong>Technical Details:</strong>
                    <ul>
                        <li>CSV export respecting current filters and sorting</li>
                        <li>Proper CSV formatting with escaped fields</li>
                        <li>Timestamp in filename for unique identification</li>
                        <li>Download handling with appropriate headers</li>
                        <li>All visible columns included in export</li>
                    </ul>
                </p>
                <p class="benefits">
                    <strong>Benefits:</strong> Enables HR managers to perform advanced analysis in spreadsheet applications, 
                    create backups of important records, and generate reports for management review.
                </p>
            </article>

            <!-- Enhancement 7: Status Change History -->
            <article class="enhancement-item">
                <h2>7. EOI Status Change Audit Trail</h2>
                <p class="enhancement-type"><strong>Type:</strong> Audit & Compliance</p>
                <p class="enhancement-description">
                    <strong>Implementation:</strong> Implemented status change tracking that records when, by whom, and why an 
                    EOI status was changed, creating an audit trail for compliance and review purposes.
                </p>
                <p class="enhancement-details">
                    <strong>Technical Details:</strong>
                    <ul>
                        <li>Created status_history table to log all status changes</li>
                        <li>Records timestamp of change with PHP's time() function</li>
                        <li>Tracks which manager made the change via session username</li>
                        <li>Stores notes/reason for status change</li>
                        <li>Display full history on EOI detail view</li>
                    </ul>
                </p>
                <p class="benefits">
                    <strong>Benefits:</strong> Provides complete audit trail for HR processes, ensures accountability, 
                    supports compliance requirements, and helps resolve disputes about application status.
                </p>
            </article>

            <!-- Enhancement 8: Responsive Admin Dashboard -->
            <article class="enhancement-item">
                <h2>8. Mobile-Responsive Manager Dashboard</h2>
                <p class="enhancement-type"><strong>Type:</strong> User Experience & Accessibility</p>
                <p class="enhancement-description">
                    <strong>Implementation:</strong> Enhanced manage.php with responsive design using CSS media queries, 
                    allowing managers to access and manage applications on mobile and tablet devices.
                </p>
                <p class="enhancement-details">
                    <strong>Technical Details:</strong>
                    <ul>
                        <li>Mobile-first CSS approach</li>
                        <li>Responsive table layout with horizontal scrolling on small screens</li>
                        <li>Touch-friendly buttons and form controls</li>
                        <li>Collapsible search/filter sections on mobile</li>
                        <li>Optimized for tablets and phones</li>
                        <li>Viewport meta tag configuration</li>
                    </ul>
                </p>
                <p class="benefits">
                    <strong>Benefits:</strong> HR managers can review applications and manage EOI records from anywhere, 
                    improving workflow flexibility and responsiveness to business needs.
                </p>
            </article>

            <!-- Enhancement 9: Input Sanitization -->
            <article class="enhancement-item">
                <h2>9. Comprehensive Input Sanitization & XSS Prevention</h2>
                <p class="enhancement-type"><strong>Type:</strong> Security & Data Integrity</p>
                <p class="enhancement-description">
                    <strong>Implementation:</strong> Implemented comprehensive input sanitization across all forms and user input 
                    handling to prevent SQL injection, XSS attacks, and other malicious input.
                </p>
                <p class="enhancement-details">
                    <strong>Technical Details:</strong>
                    <ul>
                        <li>Prepared statements for all database queries</li>
                        <li>htmlspecialchars() escaping for output display</li>
                        <li>trim() to remove leading/trailing whitespace</li>
                        <li>stripslashes() to remove backslashes</li>
                        <li>Type casting for numeric inputs</li>
                        <li>Regular expression validation for phone and postcode</li>
                        <li>Custom validation functions for complex field types</li>
                    </ul>
                </p>
                <p class="benefits">
                    <strong>Benefits:</strong> Protects against common web vulnerabilities, ensures data integrity, 
                    and maintains system security and user data protection.
                </p>
            </article>

        </section>

        <!-- Summary -->
        <section id="enhancement-summary">
            <h2>Enhancement Summary</h2>
            <p>
                These enhancements go beyond the basic requirements to create a robust, secure, and user-friendly 
                HR management system. The implementation focuses on:
            </p>
            <ul>
                <li><strong>Security:</strong> Multi-layer approach with input validation, authentication, and authorization</li>
                <li><strong>Usability:</strong> Advanced search, sorting, and responsive design for better user experience</li>
                <li><strong>Data Integrity:</strong> Validation and sanitization at multiple levels</li>
                <li><strong>Audit & Compliance:</strong> Complete tracking of all operations and status changes</li>
                <li><strong>Scalability:</strong> Optimized queries and efficient database design</li>
                <li><strong>Accessibility:</strong> Mobile-responsive design and semantic HTML</li>
            </ul>
        </section>
    </main>

<?php
include 'footer.inc';
?>
