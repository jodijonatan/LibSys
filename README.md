# BukuNet - Digital Library Management System

A comprehensive web-based library management system built with PHP and MySQL, designed to streamline book lending, user management, and administrative operations for modern libraries.

## Features

### User Management

- **Role-based Access Control**: Three user levels - Admin, Staff (Petugas), and Member (Anggota)
- **Secure Authentication**: Password hashing with PHP's built-in functions
- **User Registration**: Self-registration for new members
- **Profile Management**: User information and access level management

### Book Management

- **Book Catalog**: Complete book inventory with title, author, and availability tracking
- **CRUD Operations**: Add, edit, delete, and search books
- **Stock Management**: Real-time availability and circulation tracking

### Circulation Management

- **Loan Processing**: Easy book lending with date tracking
- **Return Management**: Streamlined book return process
- **Overdue Tracking**: Monitor late returns and fines
- **Activity Logs**: Complete history of all transactions

### Dashboard & Analytics

- **Real-time Statistics**: Total books, active loans, and member counts
- **Recent Activities**: Live feed of latest library activities
- **Role-specific Interfaces**: Customized dashboards for each user type

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Tailwind CSS
- **Database Access**: PDO (PHP Data Objects)
- **Session Management**: PHP Sessions

## Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx recommended)
- Composer (optional, for dependency management)

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/yourusername/digital-library-system.git
   cd digital-library-system
   ```

2. **Database Setup**
   - Create a new MySQL database named `perpustakaan`
   - Import the database schema (if provided) or create tables manually:

     ```sql
     -- Users table
     CREATE TABLE users (
       id INT PRIMARY KEY AUTO_INCREMENT,
       nama VARCHAR(255) NOT NULL,
       email VARCHAR(255) UNIQUE NOT NULL,
       password VARCHAR(255) NOT NULL,
       level ENUM('admin', 'petugas', 'anggota') NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );

     -- Books table
     CREATE TABLE books (
       id INT PRIMARY KEY AUTO_INCREMENT,
       judul VARCHAR(255) NOT NULL,
       pengarang VARCHAR(255) NOT NULL,
       penerbit VARCHAR(255),
       tahun_terbit YEAR,
       jumlah INT DEFAULT 1,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );

     -- Peminjaman (Loans) table
     CREATE TABLE peminjaman (
       id INT PRIMARY KEY AUTO_INCREMENT,
       user_id INT NOT NULL,
       book_id INT NOT NULL,
       tgl_pinjam DATE NOT NULL,
       tgl_kembali DATE,
       status ENUM('dipinjam', 'dikembalikan') DEFAULT 'dipinjam',
       FOREIGN KEY (user_id) REFERENCES users(id),
       FOREIGN KEY (book_id) REFERENCES books(id)
     );
     ```

3. **Configuration**
   - Update database credentials in `includes/db.php` if necessary
   - Ensure the web server has write permissions to the project directory

4. **Web Server Configuration**
   - Point your web server document root to the project directory
   - Ensure URL rewriting is enabled if using Apache with .htaccess

5. **Access the Application**
   - Open your browser and navigate to the application URL
   - Default login credentials (create via database or registration):
     - Admin: Create a user with level 'admin'
     - Staff: Create a user with level 'petugas'
     - Member: Register through the application

## Usage

### For Administrators

- **User Management**: Add, edit, and remove users with different access levels
- **System Overview**: Monitor overall library statistics and activities
- **Book Inventory**: Manage the complete book collection

### For Staff (Petugas)

- **Loan Processing**: Handle book lending and returns
- **Member Assistance**: Help members with their library needs
- **Record Keeping**: Maintain accurate circulation records

### For Members (Anggota)

- **Book Browsing**: Search and view available books
- **Loan History**: Track personal borrowing history
- **Account Management**: Update personal information

## Project Structure

```
perpustakaan/
├── index.php              # Application entry point
├── login.php              # User authentication
├── dashboard.php          # Main dashboard
├── logout.php             # Session termination
├── hash.php               # Password hashing utilities
├── admin/                 # Admin-specific pages
│   ├── manage_users.php   # User management
│   ├── manage_books.php   # Book management
│   └── record.php         # Administrative records
├── petugas/               # Staff pages
│   ├── peminjaman.php     # Loan processing
│   └── records.php        # Staff records
├── anggota/               # Member pages
│   └── riwayat.php        # Loan history
├── pages/                 # General pages
│   └── register.php       # User registration
├── includes/              # Shared components
│   ├── config.php         # Configuration utilities
│   ├── db.php             # Database connection
│   ├── header.php         # HTML header template
│   └── footer.php         # HTML footer template
└── README.md              # Project documentation
```

## Security Features

- **Password Hashing**: Secure password storage using PHP's password_hash()
- **SQL Injection Protection**: Prepared statements with PDO
- **Session Security**: Proper session management and validation
- **Access Control**: Role-based permissions throughout the application
- **Input Validation**: Server-side validation for all user inputs

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions, please open an issue on the GitHub repository or contact the development team.

## Acknowledgments

- Built with modern PHP practices and security standards
- Responsive design powered by Tailwind CSS
- Inspired by the need for efficient library management solutions
