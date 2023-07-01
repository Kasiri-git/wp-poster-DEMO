<?php
/*
Plugin Name: WP Poster
Description: This plugin automatically posts content to your WordPress site.
Version: 1.0
Author: Kasiri
*/

function wp_poster_publish_test_post() {
    $post_title = 'Test Post';
    $post_content = 'This is a test post created by the WP Poster plugin.';
    $post_status = 'publish';
    $post_author = 1;

    $selected_category = $_POST['wp_poster_category'] ?? 0;

    if ($selected_category) {
        $post_category = array($selected_category);
    } else {
        $post_category = array(1); // デフォルトのカテゴリ ID
    }

    $post_data = array(
        'post_title'    => $post_title,
        'post_content'  => $post_content,
        'post_status'   => $post_status,
        'post_author'   => $post_author,
        'post_category' => $post_category
    );

    $post_id = wp_insert_post($post_data);

    if (!is_wp_error($post_id)) {
        echo 'Test post created successfully with ID: ' . $post_id;
    } else {
        echo 'Error creating test post: ' . $post_id->get_error_message();
    }
}

// メニューリンクの作成
function wp_poster_add_menu_item() {
    add_menu_page(
        'WP Poster',
        'WP Poster',
        'manage_options',
        'wp-poster',
        'wp_poster_page_content',
        'dashicons-editor-kitchensink',
        99
    );
}

function wp_poster_page_content() {
    if (isset($_POST['wp_poster_submit'])) {
        wp_poster_publish_test_post();
    }
    ?>
    <div class="wrap">
        <h1>WP Poster</h1>
        <form method="post" action="<?php echo admin_url('admin.php?page=wp-poster'); ?>">
            <p>Click the button below to create a test post:</p>
            <select name="wp_poster_category">
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                }
                ?>
            </select>
            <button type="submit" class="button button-primary" name="wp_poster_submit">Create Test Post</button>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'wp_poster_add_menu_item');
