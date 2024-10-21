

# Contact Management System - CodeQuest

## Overview
This project is a **Contact Management System** where users can manage their contacts efficiently. Users can perform actions such as adding, editing, deleting, and searching for contacts. Additionally, they can import/export contacts as VCF files, search by various criteria, and merge duplicate contacts based on name, number, or email.

### Features
- **User Authentication**: Secure login for users to access their contact lists.
- **CRUD Operations**: Add, edit, delete, and view contacts.
- **VCF Import/Export**: Import contacts from VCF files and export contacts as VCF files.
- **Contact Merging**: Detect and merge duplicate contacts by name, phone number, or email.
- **Contact Search**: Search contacts by name, email, or tags.
- **Tagging System**: Assign tags to contacts for easier categorization and filtering.

---

## Features Breakdown

### 1. User Authentication
- **Login**: Users can log in securely to manage their contacts.
- **Session Management**: User sessions ensure that only authenticated users can access the application.

### 2. Contact Management (CRUD)
- **Add New Contacts**: Users can add new contacts by providing details like name, phone number, email, and tags.
- **Edit Contacts**: Users can update their existing contact information.
- **Delete Contacts**: Users can delete contacts they no longer need.
- **View Contacts**: All contacts are listed with options to view details or edit them.

### 3. VCF Import/Export
- **Import Contacts**: Users can import contacts from VCF (vCard) files, which are parsed and stored in the system.
- **Export Contacts**: Contacts can be exported to VCF files for backup or sharing.

### 4. Contact Merging
- **Detect Duplicates**: The system identifies duplicate contacts based on name, phone number, or email.
- **Merge Contacts**: Users can merge duplicate contacts, keeping the relevant information and deleting redundant entries.

### 5. Contact Search & Tags
- **Search**: Users can search contacts by name, email, or tags for better organization.
- **Tags**: Users can assign tags to contacts for easier categorization and filtering during searches.

---

## How to Run This Project

### Prerequisites
- **XAMPP** (or any PHP local server): Ensure you have PHP and MySQL installed.
- **PHP 7.4 or later**
- **MySQL**
- **Composer** (for dependency management)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/contact-management-system.git
   ```
   
2. **Set Up Database**
   - Open **phpMyAdmin** or any MySQL client.
   - Create a database called `contact_management`.
   - Run the provided SQL script (`db.sql`) to create the necessary tables.
   
   Example SQL command to create the database:
   ```sql
   CREATE DATABASE contact_management;
   USE contact_management;
   ```
   Then, import the `db.sql` file to set up the tables.

3. **Configure Database Connection**
   - Open `db/db.php` and set up your database credentials:
     ```php
     $host = 'localhost';
     $dbname = 'contact_management';
     $username = 'root';
     $password = '';
     ```
   - Replace the username and password fields with your local MySQL credentials.

4. **Start XAMPP or PHP Server**
   - If using XAMPP, start **Apache** and **MySQL** services.
   - Place the project folder in the `htdocs` directory of XAMPP:
     ```
     C:\xampp\htdocs\contact-management-system
     ```
   - Access the project in the browser:
     ```
     http://localhost/contact-management-system
     ```

5. **Create User Account (Manually)**
   - For this project, you'll need to create users manually in the database.
   - Insert a new user entry into the `users` table (passwords should be hashed using `password_hash()` in PHP).

6. **Run the Application**
   - After setup, navigate to the project in your browser.
   - Log in using your user credentials.
   - Start managing your contacts (adding, editing, deleting, merging, and more).

### Using the Features

#### 1. **Login**
   - Use the credentials stored in the database to log in.
   - Upon successful login, you'll be directed to the contacts page.

#### 2. **Add New Contact**
   - Click the "Add Contact" button.
   - Fill out the name, phone number, email, and tags (optional), and save the contact.

#### 3. **Edit Contact**
   - From the contact list, select a contact to edit by clicking the "Edit" button next to it.
   - Modify the fields and click "Save" to update the contact.

#### 4. **Delete Contact**
   - Click the "Delete" button next to any contact in the list to remove it from the database.

#### 5. **Import Contacts from VCF File**
   - Navigate to the **Import** section and upload a VCF file.
   - The contacts will be imported into the system and added to your contact list.

#### 6. **Export Contacts as VCF File**
   - Click the "Export" button on the contacts page to download all your contacts in VCF format.

#### 7. **Merge Duplicate Contacts**
   - The system automatically detects duplicates based on name, phone number, or email.
   - You can review duplicate contacts and merge them using the "Merge" button.

#### 8. **Search Contacts**
   - Use the search bar on the contacts page to find contacts by name, email, or tags.
   - Results will dynamically update as you type.

---

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP (PDO for database interaction)
- **Database**: MySQL
- **VCF Parsing**: Custom PHP logic to handle VCF file import/export

---

## Future Enhancements

- **User Signup**: Implement a signup feature to allow user registration.
- **Advanced Search**: Add advanced filters for more refined searching capabilities.
- **Error Handling**: Improve error handling and validation for all inputs.
- **Mobile-Friendly Design**: Enhance UI for better mobile responsiveness.

---

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

### If you encounter any issues, feel free to open an issue or contact the project maintainer.

---

This README provides a clear overview of your project, its features, and how to set it up and run it. Let me know if you need any further adjustments!
