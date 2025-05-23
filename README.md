# PHP-Simple-Entry-Manager
A basic PHP tool to add, edit, delete, and list simple entries (with name and content) stored in a JSON file, accessible via a web interface.

---

This is a basic web application for managing small pieces of information related to websites, potentially useful for tracking backlinks, login details, or content snippets. It's built with PHP for server-side logic and uses a JSON file for data storage. The frontend uses HTML, Tailwind CSS (via CDN), and Font Awesome (via CDN).

Features:
- Add new website entries (Name and Content).
- Edit existing website entries.
- Delete website entries.
- Copy the 'Content' of an entry to the clipboard.
- Stores data in a simple JSON file (`data.json`).
- Displays creation and update timestamps for each entry.
- Uses a modal for add/edit forms.
- Simple toast notification for copy action.

Requirements:
- A web server (like Apache, Nginx, Caddy) with PHP support.
- PHP installed and enabled.
- Write permissions for the web server process in the directory where the files are placed, specifically for the `data.json` file.

Installation:
1.  Place the provided files (`index.php`, `save.php`, `delete.php`, `data.json`) into a directory accessible by your web server (e.g., `htdocs/list/`).
2.  Ensure the `data.json` file exists in the same directory (an empty file is provided).
3.  Ensure that the web server user has permissions to *write* to the `data.json` file. This is crucial for saving and deleting data.
4.  Access `index.php` through your web browser (e.g., `http://localhost/list/`).

Usage:
1.  Open `index.php` in your web browser.
2.  Click the "Add Website" button to add a new entry. Fill in the name and content, then click "Save".
3.  Existing entries will be listed. Use the icons:
    -   Copy (Clipboard icon): Copies the content of the entry to your clipboard.
    -   Edit (Pencil icon): Opens the entry in the modal for editing.
    -   Delete (Trash icon): Prompts for confirmation and deletes the entry.

Data Storage:
- All data is stored in the `data.json` file in the root directory of the application.
- The data is stored as a JSON object where keys are unique IDs (generated using client-side `Date.now()` for new entries) and values are objects containing `name`, `content`, `created`, and `updated` fields.
- **Note:** Storing data in a plain file like this is simple but **not secure** for sensitive information. It's suitable for personal, internal, or low-security use cases on a protected server. It is also not designed for multi-user concurrent access or large amounts of data.

Customization:
- Styles are handled by Tailwind CSS via CDN and a small custom CSS block in `index.php`.
- Font Awesome icons are loaded via CDN.
- The `data.json` file format is a simple key-value store.

Files Included:
- `list/index.php`: Main page, displays list, handles UI, calls save/delete via JavaScript/Fetch.
- `list/save.php`: Handles saving (adding or updating) data to `data.json` via POST requests.
- `list/delete.php`: Handles deleting data from `data.json` via POST requests.
- `list/data.json`: The data storage file (starts empty).
