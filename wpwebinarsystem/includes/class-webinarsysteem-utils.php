<?php

class WebinarSysteemUtils {
    public static function show_admin_pointer(
        $selector,
        $title,
        $content_ = '',
        $primary_button = false,
        $primary_action = '',
        $secondary_button = false,
        $secondary_action = '',
        $options = array()) {

        if (!current_user_can('administrator') || (get_bloginfo( 'version' ) < '3.3')) {
            return;
        }

        $content  = '';
        $content .= '<h3>' . $title . '</h3>';
        $content .= '<p>' . $content_ . '</p>';

        ?>
        <script type="text/javascript">
          //<![CDATA[
          jQuery(function($) {
            var wpwsPointer = $('<?php echo $selector; ?>' ).pointer({
              'content': <?php echo json_encode( $content ); ?>,
              'position': { 'edge': '<?php echo isset( $options['edge'] ) ? $options['edge'] : 'top'; ?>',
                'align': '<?php echo isset( $options['align'] ) ? $options['align'] : 'center'; ?>' },
              'buttons': function(e, t) {
                  <?php if (!$secondary_button): ?>
                return $('<a id="wpws-pointer-b1" class="button button-primary">' + '<?php echo $primary_button; ?>' + '</a>');
                  <?php else: ?>
                return $('<a id="wpws-pointer-b2" class="button" style="margin-right: 15px;">' + '<?php echo $secondary_button; ?>' + '</a>');
                  <?php endif; ?>
              }
            }).pointer('open');

              <?php if ($secondary_button): ?>

            $('#wpws-pointer-b2').before('<a id="wpws-pointer-b1" class="button button-primary">' + '<?php echo $primary_button; ?>' + '</a>');
            $('#wpws-pointer-b2').click(function(e) {
              e.preventDefault();
                <?php if ( $secondary_action ): ?>
                <?php echo $secondary_action; ?>
                <?php endif; ?>
              wpwsPointer.pointer( 'close' );
            });

              <?php endif; ?>

            $('#wpws-pointer-b1').click(function(e) {
              e.preventDefault();
                <?php if ( $primary_action ): ?>
                <?php echo $primary_action; ?>
                <?php endif; ?>
              wpwsPointer.pointer( 'close' );
            });
          });
          //]]>
        </script>
        <?php
    }
}
