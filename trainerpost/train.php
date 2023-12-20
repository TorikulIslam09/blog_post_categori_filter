<?php
global $wp_query;
?>

<div class="parents-div">
    <div class="search-input">
        <div class="input-div">
            <input type="text" id="searchItem" value="" name="search">
        </div>
    </div>
    <div class="choose-cate">
        <div class="choose">
            <?php
            $taxonomies = get_terms(array(
                'taxonomy' => 'category',
                'hide_empty' => false
            ));

            if (!empty($taxonomies)) :
                foreach ($taxonomies as $category) {
                    echo '<button data-category="' . esc_attr($category->slug) . '" class="' . esc_attr($category->name) . '">' . $category->name . '</button>';
                }
            endif;
            ?>
        </div>
    </div>
</div>

<div class="hello"></div>

<script>
jQuery(document).ready(function ($) {
    let nonce = '<?= wp_create_nonce('get_filtered_img') ?>';

    var defaultCategory = $('.choose button:first').data('category');
    function fetchAndDisplayContent(category) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'test',
                key: category,
                nonce: nonce,
            },
            success: function (data) {
                $('.hello').html(data);
            }
        });
    }

    fetchAndDisplayContent(defaultCategory);
    // Handle button click event
    $('.choose button').click(function () {
        // Remove 'active' class from all buttons
        $('.choose button').removeClass('active');
        $(this).addClass('active');
        var selectedCategory = $(this).data('category');
        fetchAndDisplayContent(selectedCategory);
    });
});
</script>
