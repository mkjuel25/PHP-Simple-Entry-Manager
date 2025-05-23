<?php
// index.php
$data_file = 'data.json';

// Read existing data
$websites = file_exists($data_file) ? json_decode(file_get_contents($data_file), true) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backlink Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles for better mobile touch targets */
        .action-btn {
            min-width: 32px;
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-2 sm:p-4 max-w-6xl">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl sm:text-2xl font-bold">Backlink Manager</h1>
        <button onclick="showModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded flex items-center gap-1">
            <i class="fas fa-plus"></i>
            <span class="hidden sm:inline">Add Website</span>
        </button>
    </div>

    <!-- Websites List -->
    <div class="grid gap-3 sm:gap-4">
        <?php if (empty($websites)): ?>
            <div class="bg-white p-4 rounded shadow text-center text-gray-500">
                No websites added yet. Click the button above to add one.
            </div>
        <?php else: ?>
            <?php foreach ($websites as $id => $website): ?>
            <div class="bg-white p-3 sm:p-4 rounded shadow hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start gap-2">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg sm:text-xl font-semibold truncate"><?= htmlspecialchars($website['name']) ?></h2>
                        <p class="text-gray-600 text-xs sm:text-sm mt-1">
                            Created: <?= $website['created'] ?> | 
                            Updated: <?= $website['updated'] ?>
                        </p>
                    </div>
                    <div class="flex gap-1 sm:gap-2">
                        <button onclick="copyToClipboard('<?= $id ?>')" 
                                class="action-btn text-gray-500 hover:text-blue-500" 
                                title="Copy content">
                            <i class="far fa-copy text-sm sm:text-base"></i>
                        </button>
                        <button onclick="editWebsite('<?= $id ?>')" 
                                class="action-btn text-blue-500 hover:text-blue-700" 
                                title="Edit">
                            <i class="far fa-edit text-sm sm:text-base"></i>
                        </button>
                        <button onclick="deleteWebsite('<?= $id ?>')" 
                                class="action-btn text-red-500 hover:text-red-700" 
                                title="Delete">
                            <i class="far fa-trash-alt text-sm sm:text-base"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-t text-sm sm:text-base break-words">
                    <?= $website['content'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-2 z-50">
        <div class="bg-white p-4 sm:p-6 rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg sm:text-xl font-bold" id="modalTitle">Add New Website</h2>
                <button onclick="hideModal()" class="text-gray-500 hover:text-gray-700 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="websiteForm" onsubmit="saveWebsite(event)" class="space-y-4">
                <input type="hidden" id="websiteId">
                <div>
                    <label class="block mb-2 text-sm sm:text-base">Website Name</label>
                    <input type="text" id="websiteName" required
                           class="w-full p-2 border rounded text-sm sm:text-base">
                </div>
                <div>
                    <label class="block mb-2 text-sm sm:text-base">Content/Notes</label>
                    <textarea id="websiteContent" rows="8" 
                              class="w-full p-2 border rounded text-sm sm:text-base"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="hideModal()" 
                            class="px-4 py-2 border rounded hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="toast" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span>Copied to clipboard!</span>
    </div>
</div>

<script>
let currentEditId = null;

function showModal() {
    document.getElementById('modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideModal() {
    document.getElementById('modal').classList.add('hidden');
    document.body.style.overflow = '';
    resetForm();
}

function resetForm() {
    document.getElementById('websiteForm').reset();
    document.getElementById('modalTitle').textContent = 'Add New Website';
    currentEditId = null;
}

function editWebsite(id) {
    const website = <?= json_encode($websites) ?>[id];
    document.getElementById('websiteId').value = id;
    document.getElementById('websiteName').value = website.name;
    document.getElementById('websiteContent').value = website.content;
    document.getElementById('modalTitle').textContent = 'Edit Website';
    currentEditId = id;
    showModal();
}

async function saveWebsite(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('id', currentEditId || Date.now().toString());
    formData.append('name', document.getElementById('websiteName').value);
    formData.append('content', document.getElementById('websiteContent').value);

    try {
        const response = await fetch('save.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function deleteWebsite(id) {
    if (confirm('Are you sure you want to delete this website?')) {
        try {
            const formData = new FormData();
            formData.append('id', id);
            
            await fetch('delete.php', {
                method: 'POST',
                body: formData
            });
            
            window.location.reload();
        } catch (error) {
            console.error('Error:', error);
        }
    }
}

function copyToClipboard(id) {
    const website = <?= json_encode($websites) ?>[id];
    const content = website.content;
    
    navigator.clipboard.writeText(content).then(() => {
        showToast();
    }).catch(err => {
        console.error('Failed to copy: ', err);
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = content;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast();
    });
}

function showToast() {
    const toast = document.getElementById('toast');
    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 2000);
}

// Close modal when clicking outside
document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('modal').classList.contains('hidden')) {
        hideModal();
    }
});
</script>
</body>
</html>
