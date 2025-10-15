EduHub 

This project is a web-based school management system developed using PHP and an MVC (Model-View-Controller) architecture. The system provides separate panels for Admin, Teacher, Reception, and Student roles, enabling digital management of school processes.

🌟 Features

User Roles: Four different user roles: Admin, Teacher, Reception, and Student.

Secure Login: Users can securely log in to the system with their email address and password.

Dynamic Panels: A dashboard interface that dynamically changes based on user role.

Student Management (Teacher): Teachers can list, search, and view the profiles of all students enrolled in the system.

Class Management (Teacher): Teachers can create new classes, edit, or delete existing classes.

Exam Management (Teacher): Teachers can create new tests, manage existing tests, and set their active/inactive status.

Exam Grading (Teacher): Students can view, grade, and save completed exams to the system.

Exam Participation (Student): Students can view and participate in their assigned active exams.

Profile Management: All users can view their profile information.

🛠️ Technologies Used

Backend: PHP

Architecture: MVC (Model-View-Controller)

Frontend: HTML, CSS, JavaScript

Database: MySQL

<img width="1919" height="921" alt="image" src="https://github.com/user-attachments/assets/3a4c4098-35c2-422e-b416-40b8d35e9fd7" />
Login Screen
The interface through which users log in to the system.

<img width="1919" height="921" alt="image" src="https://github.com/user-attachments/assets/b66380e9-3d19-4e16-a087-e2d7e4e0b983" />

Teacher Dashboard
Homepage where teachers can quickly access all modules.

<img width="1919" height="922" alt="image" src="https://github.com/user-attachments/assets/c015bf25-b201-4d59-a5a0-45075d0c8172" />

Student List

This page lists all students in the system.


<img width="1919" height="918" alt="image" src="https://github.com/user-attachments/assets/479f8561-58e5-4fab-bad6-0587978bfe9a" />

Class Management

This screen allows you to manage created classes.


<img width="1919" height="918" alt="image" src="https://github.com/user-attachments/assets/dcf5ba90-b899-4aca-9cc8-715c20ced3bb" />

Exam Management

This screen allows you to list and manage created exams.


<img width="1919" height="918" alt="image" src="https://github.com/user-attachments/assets/4523098c-918a-405b-a35b-1d836f8b4ad5" />

Exam Evaluation

Exams completed by students and awaiting grading.


<img width="1919" height="924" alt="image" src="https://github.com/user-attachments/assets/583ea868-42d4-470c-9749-06e3f8dd0afc" />

Graded Exams

The results of exams that have been graded.


<img width="1919" height="921" alt="image" src="https://github.com/user-attachments/assets/c5af10b4-6960-4022-a6e5-ec05e09babea" />

Student Dashboard

This page provides access to your modules.


<img width="1919" height="920" alt="image" src="https://github.com/user-attachments/assets/8a890443-e525-475d-8d27-9ddacf7a3631" />
Classes

This list of classes the student is enrolled in.


<img width="1919" height="924" alt="image" src="https://github.com/user-attachments/assets/1796e1c9-3ea1-40fa-b220-a343536d0d7d" />

Exams

This list of active exams the student can take.

🚀 Installation

To run the project on your local machine, follow these steps:

Clone the Project

Install the Database:

Import the database.sql file (if you have one) into your MySQL database.

If an SQL file doesn't exist, manually create the necessary tables.

Edit the Configuration File:

Open the file containing the database connection settings (such as app/core/config.php) in the project's root directory.

Enter your database information (username, password, database name).

Start the Server:

Move the project to the htdocs or www folder of a local server such as XAMPP or WAMP.

Start the Apache and MySQL services.

Open the Application:

Open your web browser and go to http://localhost/SchoolSystem.
