
<!-- Info Alert Box -->
<?php if (isset($data['restrictions'])): ?>
    <div class="alert alert-info mb-4">
        <h5><?php echo $data['restrictions']['title']; ?></h5>
        <ul class="mb-0">
            <?php foreach ($data['restrictions']['items'] as $item): ?>
                <li><?php echo $item; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Global Alert Styles -->
<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.mb-4 {
    margin-top: 1.5rem;
    margin-bottom: 1.5rem;
}

.mb-0 {
    margin-bottom: 0;
}
</style> 