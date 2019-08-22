
<!doctype html>

<html>

    <head>

        <?php wp_head(); ?>

    </head>

    <body <?php body_class(); ?>>

        <div id="app">
        
            <h1>Facade</h1>

            <?php echo apply_filters( 'the_content' , $post->post_content ); ?>

        </div>

        <?php wp_footer(); ?>

    </body>

</html>