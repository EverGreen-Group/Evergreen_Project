<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link href="<?php echo URLROOT; ?>/public/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<main>
    <div class="edit-announcement-container">
        <h2><?php echo empty($data['announcement']->announcement_id) ? 'Create Announcement' : 'Edit Announcement'; ?></h2>
        <div class="form-card">
            <form action="<?php echo URLROOT; ?>/manager/<?php echo empty($data['announcement']->announcement_id) ? 'createAnnouncement' : 'updateAnnouncement'; ?>" method="POST" enctype="multipart/form-data" id="announcement-form" novalidate>
                <input type="hidden" name="announcement_id" value="<?php echo htmlspecialchars($data['announcement']->announcement_id); ?>">
                <div class="form-group">
                    <label for="title">Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($data['announcement']->title); ?>" required>
                </div>
                <div class="form-group">
                    <label for="content">Content <span class="required">*</span></label>
                    <textarea id="content" name="content" class="form-control" rows="8" required><?php echo htmlspecialchars($data['announcement']->content); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="banner">Banner Image (JPEG, max 2MB, optional)</label>
                    <input type="file" id="banner" name="banner" class="form-control" accept="image/jpeg">
                    <div id="banner-preview" class="banner-preview"></div>
                    <?php if (!empty($data['announcement']->banner) && file_exists('public/uploads/announcements/' . $data['announcement']->banner)): ?>
                        <div class="current-banner">
                            <p>Current Banner:</p>
                            <img src="<?php echo URLROOT . '/public/public/uploads/announcements/' . htmlspecialchars($data['announcement']->banner); ?>" alt="Current Banner">
                            <!-- Debug: Print URLROOT and banner (comment out in production) -->
                            <!-- <p>URLROOT: <?php echo htmlspecialchars(URLROOT); ?></p> -->
                            <!-- <?php var_dump($data['announcement']->banner); ?> -->
                            <label class="remove-banner-label">
                                <input type="checkbox" name="remove_banner" value="1"> Remove Banner
                            </label>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="button-row">
                    <button type="submit" class="btn btn-primary"><?php echo empty($data['announcement']->announcement_id) ? 'Create' : 'Update'; ?></button>
                    <a href="<?php echo URLROOT; ?>/manager/announcements" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>

// Client-side banner preview
document.getElementById('banner')?.addEventListener('change', function(e) {
    const preview = document.getElementById('banner-preview');
    preview.innerHTML = '';
    const file = e.target.files[0];
    if (file) {
        if (file.type !== 'image/jpeg') {
            alert('Please upload a JPEG image.');
            e.target.value = '';
            return;
        }
        // if (file.size > 2 * 1024 * 1024) {
        //     alert('Banner file size exceeds 2MB.');
        //     e.target.value = '';
        //     return;
        // }
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Banner Preview';
            img.style.maxWidth = '200px';
            img.style.borderRadius = '8px';
            img.style.marginTop = '10px';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});
</script>

<style>
@font-face {
    font-family: 'boxicons';
    src: url('<?php echo URLROOT; ?>/public/boxicons/fonts/boxicons.woff2') format('woff2'),
         url('<?php echo URLROOT; ?>/public/boxicons/fonts/boxicons.woff') format('woff'),
         url('<?php echo URLROOT; ?>/public/boxicons/fonts/boxicons.ttf') format('truetype');
    font-display: swap;
}

.edit-announcement-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

.edit-announcement-container h2 {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 30px;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.form-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 30px;
    animation: fadeIn 0.5s ease-in-out;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.form-group .required {
    color: #e53e3e;
    font-size: 14px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 16px;
    color: #4a5568;
    background: #f7fafc;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
    outline: none;
}

.form-group textarea {
    resize: vertical;
    min-height: 150px;
}

.banner-preview img,
.current-banner img {
    max-width: 200px;
    border-radius: 8px;
    margin-top: 10px;
    border: 1px solid #e2e8f0;
}

.current-banner {
    margin-top: 15px;
}

.current-banner p {
    font-size: 14px;
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 10px;
}

.remove-banner-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    font-size: 14px;
    color: #4a5568;
}

.button-row {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
}

.btn-primary {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-primary:hover {
    background-color: #218838;
    transform: translateY(-2px);
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-secondary:hover {
    background-color: #4a5568;
    transform: translateY(-2px);
}

#announcement-form #content {
    width: 100%;
    left: 0;
    max-height: 100px;
}

#announcement-form textarea{
    resize:vertical;
}

#announcement-form > div:nth-child(4) > div.current-banner > label > input[type=checkbox] {
    width: 25px;
}
/* Fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive design */
@media (max-width: 600px) {
    .edit-announcement-container {
        margin: 20px auto;
        padding: 0 15px;
    }

    .form-card {
        padding: 20px;
    }

    .form-group input,
    .form-group textarea {
        font-size: 14px;
        padding: 10px;
    }

    .btn-primary,
    .btn-secondary {
        padding: 10px 20px;
        font-size: 14px;
    }

    .button-row {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<script src="<?php echo URLROOT; ?>/public/css/script.js"></script>
<?php require APPROOT . '/views/inc/components/footer.php'; ?>