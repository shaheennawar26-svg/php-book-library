# 📚 Personal Library Manager

The **Personal Library Manager** is a dynamic, single-file PHP web application designed to help users manage a collection of books.
This project focuses on implementing core web development concepts like CRUD operations, session-based data persistence, and secure form handling, all wrapped in a responsive UI.

---

## ✨ Key Features
* **Full CRUD Functionality:** Seamlessly Add, View, Edit, and Delete book records.
* **Advanced Search & Filtering:** Quickly find books by title or author using the integrated search bar.
* **Dynamic Table Sorting:** Organize your library by clicking on column headers to sort by ID, Title, Author, Genre, Year, or Pages.
* **Input Validation:** Robust server-side validation ensures that all entries meet specific criteria (e.g., character limits, date ranges, and numerical constraints).
* **User Feedback:** Real-time success and error messages using dismissible Bootstrap alerts.
* **Data Persistence:** Utilizes PHP Sessions to maintain your library data throughout the browsing session.
* **Delete Confirmation:** Uses Bootstrap Modals to prevent accidental deletions.

---

## 🛠️ Technologies Used
* **PHP:** Handles the backend logic, session management, and data processing.
* **Bootstrap 5 (CDN):** Provides a modern, responsive grid layout and UI components without local file dependencies.
* **HTML5 & CSS3:** Structured layout with custom styling, including backdrop filters and glassmorphism effects.
* **JavaScript:** Used via Bootstrap’s bundle for interactive elements like Modals and Alerts.

---

## 🔒 Security Measures
Security was a top priority during development to ensure data integrity and protection:
1. **XSS Protection:** Every echoed user data point is wrapped in `htmlspecialchars()` to prevent Cross-Site Scripting.
2. **Data Sanitization:** All incoming POST data is cleaned using `trim()` and `htmlspecialchars()` before processing.
3. **Post/Redirect/Get Pattern:** Implemented using `header("Location: ...")` and `exit;` to prevent form re-submission on page refresh.
4. **Input Type Casting:** Numeric values are explicitly cast or validated as integers (e.g., year, pages) to prevent injection.

---

## 🚀 How to Run
1. **Install a Local Server:** Download and install **XAMPP**, **WAMP**, or **MAMP**.
2. **Setup the Project:**
   - Clone this repository or download `index.php`.
   - Place the file in the server's root directory (e.g., `C:/xampp/htdocs/php-book-library/`).
3. **Launch:** - Start the **Apache** module from your server control panel.
   - Open your browser and navigate to `http://localhost/php-book-library/`.
