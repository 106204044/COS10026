<?php
/**
 * About Us Page - TechHive Company
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Created: October 2025, Updated: November 2025
 * Purpose: Display team information, skills, and demographics
 * Table structure with merged cells demonstrates HTML table techniques
 */

include 'header.inc';
include 'nav.inc';
?>

    <main>
        <h1>About Our Team</h1>

        <!-- Group Information Section -->
        <section id="group-info">
            <h2>Team Overview</h2>
            <div class="group-details">
                <h3>TechHive Development Team</h3>

                <!-- Nested List for Class Information -->
                <section class="academic-info">
                    <h4>Academic Details</h4>
                    <ul>
                        <li>Class Schedule:
                            <ul>
                                <li>Day: Wednesday</li>
                                <li>Time: 2:00 PM - 4:00 PM</li>
                                <li>Location: Building 10, Room 205</li>
                            </ul>
                        </li>
                        <li>Student IDs:
                            <ul>
                                <li>SWH03179 - Nguyen Duy Anh</li>
                                <li>SWD00440 - Tran Anh Tuan</li>
                            </ul>
                        </li>
                        <li>Tutor: Dr. SigmaSkibidi</li>
                        <li>Course: Bachelor of Computer Science</li>
                    </ul>
                </section>
            </div>
        </section>

        <!-- Group Photo -->
        <figure id="group-photo">
            <img src="./styles/images/group.jpg" alt="TechVision team members collaborating in a modern office space"
                width="800" height="450">
            <figcaption>Our TechHive team working together on innovative web solutions</figcaption>
        </figure>

        <!-- Project Contributions - UPDATED FOR PART 2 -->
        <section id="contributions">
            <h2>Project Contributions</h2>
            <dl>
                <dt>Nguyen Duy Anh (SWH03179)</dt>
                <dd>
                    <strong>Part 1:</strong> Front-end development, HTML5 structure, CSS styling, and form validation implementation
                    <br>
                    <strong>Part 2:</strong> PHP includes implementation (header.inc, nav.inc, footer.inc), database schema design for EOI table, 
                    server-side validation in process_eoi.php, manager registration system with password security, 
                    and database-driven job listings implementation
                </dd>

                <dt>Tran Anh Tuan (SWD00440)</dt>
                <dd>
                    <strong>Part 1:</strong> Front-end development, HTML5 structure, CSS styling, and form validation implementation
                    <br>
                    <strong>Part 2:</strong> settings.php configuration, database connection handling with error management, 
                    manager authentication system (login/logout), manage.php with CRUD operations for HR queries, 
                    session management and security implementation, SQL query optimization, and testing framework setup
                </dd>
            </dl>
        </section>

        <!-- Team Skills and Experience -->
        <section id="team-skills">
            <h2>Team Skills & Expertise</h2>
            <div class="skills-grid">
                <section class="skill-category">
                    <h3>Programming Languages</h3>
                    <ul>
                        <li>HTML5/CSS3</li>
                        <li>JavaScript (ES6+)</li>
                        <li>PHP 7.4+</li>
                        <li>SQL/MySQL</li>
                        <li>Python</li>
                        <li>Java</li>
                    </ul>
                </section>

                <section class="skill-category">
                    <h3>Frameworks & Tools</h3>
                    <ul>
                        <li>React.js</li>
                        <li>Vue.js</li>
                        <li>Node.js</li>
                        <li>Git & GitHub</li>
                        <li>Figma</li>
                        <li>MySQL Workbench</li>
                    </ul>
                </section>

                <section class="skill-category">
                    <h3>Areas of Expertise</h3>
                    <ul>
                        <li>Responsive Web Design</li>
                        <li>Accessibility (WCAG)</li>
                        <li>Database Management</li>
                        <li>Server-Side Development</li>
                        <li>Agile Methodology</li>
                        <li>User Experience Design</li>
                    </ul>
                </section>
            </div>
        </section>

        <!-- Team Interests Table -->
        <section id="team-interests">
            <h2>Team Interests & Hobbies</h2>
            <table>
                <caption>Our Team's Personal Interests and Favorite Media</caption>
                <thead>
                    <tr>
                        <th scope="col">Team Member</th>
                        <th scope="col">Hobbies</th>
                        <th scope="col">Favorite Books</th>
                        <th scope="col">Favorite Music</th>
                        <th scope="col">Favorite Films</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row" rowspan="2">Tran Anh Tuan</th>
                        <td>Photography, Hiking</td>
                        <td rowspan="2">"Clean Code" by Robert Martin</td>
                        <td>Indie Folk</td>
                        <td>The Matrix</td>
                    </tr>
                    <tr>
                        <td>Yoga, Cooking</td>
                        <td>Classical</td>
                        <td>Inception</td>
                    </tr>
                    <tr>
                        <th scope="row" rowspan="2">Nguyen Duy Anh</th>
                        <td>Painting, Travel</td>
                        <td>"Design of Everyday Things"</td>
                        <td rowspan="2">Bollywood, Pop</td>
                        <td>Spirited Away</td>
                    </tr>
                    <tr>
                        <td>Blogging, Dance</td>
                        <td>"Don't Make Me Think"</td>
                        <td>Parasite</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Demographic Information -->
        <section id="demographics">
            <h2>Team Background</h2>
            <div class="demographic-grid">
                <section class="demographic-info">
                    <h3>Hometowns</h3>
                    <ul>
                        <li><strong>Duy Anh:</strong> Melbourne, Victoria - Known for its coffee culture and laneway art
                        </li>
                        <li><strong>Anh Tuan:</strong> Mumbai, India - A bustling metropolitan city with rich cultural
                            heritage</li>
                    </ul>
                </section>

                <section class="demographic-info">
                    <h3>Cultural Background</h3>
                    <ul>
                        <li>Multicultural team with diverse perspectives</li>
                        <li>Fluent in 6 languages combined</li>
                        <li>Experience working in international environments</li>
                        <li>Strong understanding of global design principles</li>
                    </ul>
                </section>
            </div>
        </section>

        <!-- Career Goals -->
        <aside id="career-goals">
            <h2>Our Collective Vision</h2>
            <p>As a team, we're passionate about creating web solutions that are:</p>
            <ul>
                <li><strong>Accessible:</strong> Ensuring everyone can use our products</li>
                <li><strong>Innovative:</strong> Pushing the boundaries of web technology</li>
                <li><strong>User-Centric:</strong> Designing with real people in mind</li>
                <li><strong>Sustainable:</strong> Building products that stand the test of time</li>
                <li><strong>Secure:</strong> Implementing robust server-side validation and data protection</li>
            </ul>
        </aside>
    </main>

<?php
include 'footer.inc';
?>
