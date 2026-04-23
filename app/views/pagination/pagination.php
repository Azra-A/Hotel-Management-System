<div class="pagination">
    <?php if ($current_page > 1): ?>
        <a href="?page_no=<?= $current_page - 1 ?>"> << Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page_no=<?= $i ?>" class="<?= ($i == $current_page) ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($current_page < $total_pages): ?>
        <a href="?page_no=<?= $current_page + 1 ?>">Next >></a>
    <?php endif; ?>
</div>
