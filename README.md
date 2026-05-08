# 📚 Personal Library Manager

[span_0](start_span)[span_1](start_span)The **Personal Library Manager** is a dynamic, single-file PHP web application designed to help users manage a collection of books[span_0](end_span)[span_1](end_span). [span_2](start_span)[span_3](start_span)This project focuses on implementing core web development concepts like CRUD operations, session-based data persistence, and secure form handling, all wrapped in a responsive UI[span_2](end_span)[span_3](end_span).

---

## ✨ Key Features
* **[span_4](start_span)[span_5](start_span)[span_6](start_span)Full CRUD Functionality:** Seamlessly Add, View, Edit, and Delete book records[span_4](end_span)[span_5](end_span)[span_6](end_span).
* **[span_7](start_span)Advanced Search & Filtering:** Quickly find books by title or author using the integrated search bar[span_7](end_span).
* **[span_8](start_span)Dynamic Table Sorting:** Organize your library by clicking on column headers to sort by ID, Title, Author, Genre, Year, or Pages[span_8](end_span).
* **[span_9](start_span)[span_10](start_span)Input Validation:** Robust server-side validation ensures that all entries meet specific criteria (e.g., character limits, date ranges, and numerical constraints)[span_9](end_span)[span_10](end_span).
* **[span_11](start_span)User Feedback:** Real-time success and error messages using dismissible Bootstrap alerts[span_11](end_span).
* **[span_12](start_span)[span_13](start_span)Data Persistence:** Utilizes PHP Sessions to maintain your library data throughout the browsing session[span_12](end_span)[span_13](end_span).
* **[span_14](start_span)Delete Confirmation:** Uses Bootstrap Modals to prevent accidental deletions[span_14](end_span).

---

## 🛠️ Technologies Used
* **[span_15](start_span)[span_16](start_span)PHP:** Handles the backend logic, session management, and data processing[span_15](end_span)[span_16](end_span).
* **[span_17](start_span)[span_18](start_span)Bootstrap 5 (CDN):** Provides a modern, responsive grid layout and UI components without local file dependencies[span_17](end_span)[span_18](end_span).
* **HTML5 & CSS3:** Structured layout with custom styling, including backdrop filters and glassmorphism effects.
* **[span_19](start_span)[span_20](start_span)JavaScript:** Used via Bootstrap’s bundle for interactive elements like Modals and Alerts[span_19](end_span)[span_20](end_span).

---

## 🔒 Security Measures
Security was a top priority during development to ensure data integrity and protection:
1. **[span_21](start_span)[span_22](start_span)[span_23](start_span)XSS Protection:** Every echoed user data point is wrapped in `htmlspecialchars()` to prevent Cross-Site Scripting[span_21](end_span)[span_22](end_span)[span_23](end_span).
2. **[span_24](start_span)Data Sanitization:** All incoming POST data is cleaned using `trim()` and `htmlspecialchars()` before processing[span_24](end_span).
3. **[span_25](start_span)[span_26](start_span)Post/Redirect/Get Pattern:** Implemented using `header("Location: ...")` and `exit;` to prevent form re-submission on page refresh[span_25](end_span)[span_26](end_span).
4. **[span_27](start_span)[span_28](start_span)Input Type Casting:** Numeric values are explicitly cast or validated as integers (e.g., year, pages) to prevent injection[span_27](end_span)[span_28](end_span).

---

## 🚀 How to Run
1. **Install a Local Server:** Download and install **XAMPP**, **WAMP**, or **MAMP**.
2. **Setup the Project:**
   - [span_29](start_span)Clone this repository or download `index.php`[span_29](end_span).
   - Place the file in the server's root directory (e.g., `C:/xampp/htdocs/php-book-library/`).
3. **Launch:** - Start the **Apache** module from your server control panel.
   - Open your browser and navigate to `http://localhost/php-book-library/`.

---

## 📝 Academic Context
[span_30](start_span)Developed as part of the **Web 2 Practical** course (SDEV 2106 / WDMM 2010 / MOBC 2102)[span_30](end_span).
**[span_31](start_span)Semester:** Spring 2024/2025[span_31](end_span).
**[span_32](start_span)Institution:** Islamic University of Gaza - Faculty of Information Technology[span_32](end_span).
