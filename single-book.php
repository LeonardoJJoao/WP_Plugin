<?php
/**
 * Template Name: book
 */
?>

<?php get_header() ?>

<?php 
/**
 * Sends info in form to the DB 
 */
$frases = $_POST['contactsentence'];

if (isset($_POST['submitted'])) {

    $content_post = get_post(28);
    $oldContent = $content_post->post_content;

    $currentContent = $oldContent . $frases;

    $wpdb->update(
        "wp_posts",
        array("post_content" => $currentContent),
        array("ID" => 28),
        array("%s"),
        array("%d")
    );
}
?>

<!-- Stops the form from resubmitting when the page is refreshed -->
<script>
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}
</script>

<!-- First Form - Info that is going to be sent to DataBase -->
<h1>DB Info</h1>
<form action="<?php the_permalink(); ?>" id="contactForm" method="post">
    <ul>
        <li>
            <label for="contactsentence">Frase:</label>
            <input type="text" name="contactsentence" id="contactsentence" value="" />
        </li>
        <li>
            <button type="submit">Send to DB</button>
        </li>
    </ul>
    <input type="hidden" name="submitted" id="submitted" value="true" />
</form>

<?php get_sidebar() ?>
<?php get_footer() ?>