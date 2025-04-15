<?php
/**
 * Front end display of shortcode loop
 * can be overridden in child themes / themes or in wp-content/widget-for-eventbrite-api folder if you don't have a child theme and you don't want to lose changes due to themes updates
 *
 * To customise create a folder in your theme directory called widget-for-eventbrite-api and a modified version of this file or any template_parts renamed as appropriate
 *
 * The main structure is in get_template_part( 'loop__free_widget' );
 *
 * @var mixed $data Custom data for the template.
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- template files escaped at output
 */

$data->template_loader->get_template_part( 'paginate_links_top' . $data->event->plan );
if ( $data->utilities->get_element( 'widgetwrap', $data->args ) ) {?>
    <section class="widget">
    <?php }
// Recent posts wrapper
printf( '<section %1$s class="wfea eaw-block %2$s %3$s">',
	( ! empty( $data->utilities->get_element( 'cssid', $data->args ) ) ? 'id="' . esc_attr( $data->utilities->get_element( 'cssid', $data->args ) ) . '"' : '' ),
	( ! empty( $data->utilities->get_element( 'css_class', $data->args ) ) ? '' . esc_attr( $data->utilities->get_element( 'css_class', $data->args ) ) . '' : '' ),
	( ! empty( $data->utilities->get_element( 'style', $data->args ) ) ? '' . esc_attr( $data->utilities->get_element( 'style', $data->args ) ) . '' : '' )
);
if ( ( $data->events->post_count ?? 0) > 0 )  {
	$data->template_loader->get_template_part( 'paginate_links_top' . $data->event->plan);
	?>
    <div class="eaw-ulx">
		<?php foreach ( $data->events->posts as $event ) {
		    $data->utilities->set_event( $event );
			$data->event->booknow = $data->utilities->get_booknow_link( $data->args );
			$data->event->cta     = $data->utilities->get_cta( $data->args );
			$data->event->classes = $data->utilities->get_event_classes( $data->args );
			$data->event->classes = ' ' . $data->event->cta->availability_class;
			$data->template_loader->get_template_part( 'loop_widget' );
		}
		?>
    </div><?php
	$data->template_loader->get_template_part( 'paginate_links_bottom' . $data->event->plan);
} else {
	$data->template_loader->get_template_part( 'not_found' . $data->event->plan );
}
?>
    </section><!-- Generated by https://wordpress.org/plugins/widget-for-eventbrite-api/ -->
<?php
if ( $data->utilities->get_element( 'widgetwrap', $data->args ) ) { ?>
    </section><!-- End widget wrap -->
    <?php }
$data->template_loader->get_template_part( 'paginate_links_bottom' . $data->event->plan );
$data->template_loader->get_template_part( 'full_modal' );


